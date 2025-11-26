<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Auction;
use App\Models\AuctionPayment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected array $gateways = [];

    public function __construct()
    {
        $this->gateways = [
            'midtrans' => new MidtransGateway(),
            'xendit' => new XenditGateway(),
        ];
    }

    public function topUp(User $user, float $amount, string $paymentMethodCode): array
    {
        $paymentMethod = PaymentMethod::where('code', $paymentMethodCode)->firstOrFail();
        $fee = $this->calculateFee($amount, $paymentMethod);
        $totalAmount = $amount + $fee;

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amount,
            'fee' => $fee,
            'balance_before' => $user->balance,
            'balance_after' => $user->balance,
            'payment_method' => $paymentMethod->name,
            'payment_gateway' => $paymentMethod->gateway,
            'status' => 'pending',
            'description' => 'Top up saldo',
            'transactionable_type' => User::class,
            'transactionable_id' => $user->id,
        ]);

        $gateway = $this->gateways[$paymentMethod->gateway];
        $paymentResponse = $gateway->createPayment([
            'order_id' => $transaction->transaction_code,
            'amount' => $totalAmount,
            'payment_type' => $paymentMethod->type,
            'customer' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);

        $transaction->update([
            'gateway_transaction_id' => $paymentResponse['transaction_id'],
            'metadata' => $paymentResponse,
        ]);

        return [
            'transaction' => $transaction,
            'payment_url' => $paymentResponse['payment_url'] ?? null,
            'virtual_account' => $paymentResponse['virtual_account'] ?? null,
            'qr_code' => $paymentResponse['qr_code'] ?? null,
        ];
    }

    public function handleCallback(string $gateway, array $data): void
    {
        $gatewayHandler = $this->gateways[$gateway];
        $result = $gatewayHandler->verifyCallback($data);

        if (! $result['valid']) {
            throw new \Exception('Invalid callback signature');
        }

        $transaction = Transaction::where('transaction_code', $result['order_id'])->first();
        
        if (! $transaction) {
            throw new \Exception('Transaction not found');
        }

        if ($result['status'] === 'success') {
            DB::transaction(function () use ($transaction) {
                $user = $transaction->user;
                $newBalance = $user->balance + $transaction->amount;

                $transaction->update([
                    'status' => 'completed',
                    'balance_after' => $newBalance,
                ]);

                $user->update(['balance' => $newBalance]);
            });
        } elseif ($result['status'] === 'failed') {
            $transaction->update(['status' => 'failed']);
        }
    }

    public function payAuction(Auction $auction, User $user, string $paymentMethodCode): AuctionPayment
    {
        if ($auction->winner_id !== $user->id) {
            throw new \Exception('Anda bukan pemenang lelang ini.');
        }

        $deposit = $auction->deposits()
            ->where('user_id', $user->id)
            ->where('status', 'held')
            ->first();

        $depositAmount = $deposit ?  $deposit->amount : 0;
        $remainingAmount = $auction->winning_amount - $depositAmount;

        return DB::transaction(function () use ($auction, $user, $remainingAmount, $depositAmount, $paymentMethodCode, $deposit) {
            $payment = AuctionPayment::create([
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'total_amount' => $auction->winning_amount,
                'deposit_used' => $depositAmount,
                'amount_paid' => $remainingAmount,
                'payment_method' => $paymentMethodCode,
                'payment_gateway' => 'balance',
                'status' => 'pending',
            ]);

            if ($user->balance < $remainingAmount) {
                throw new \Exception('Saldo tidak mencukupi.');
            }

            $user->decrement('balance', $remainingAmount);
            
            if ($deposit) {
                $user->decrement('deposit', $depositAmount);
                $deposit->update(['status' => 'used']);
            }

            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            $auction->update(['status' => 'sold']);

            return $payment;
        });
    }

    public function purchaseMembership(User $user, int $packageId, string $paymentMethodCode): Transaction
    {
        $package = \App\Models\MembershipPackage::findOrFail($packageId);
        
        if ($user->balance < $package->price) {
            throw new \Exception('Saldo tidak mencukupi untuk membeli paket member.');
        }

        return DB::transaction(function () use ($user, $package, $paymentMethodCode) {
            $balanceBefore = $user->balance;
            $user->decrement('balance', $package->price);

            $membership = \App\Models\UserMembership::create([
                'user_id' => $user->id,
                'membership_package_id' => $package->id,
                'started_at' => now(),
                'expired_at' => now()->addDays($package->duration_days),
                'status' => 'active',
            ]);

            $user->update([
                'role' => 'member',
                'member_expired_at' => $membership->expired_at,
            ]);

            return Transaction::create([
                'user_id' => $user->id,
                'type' => 'membership',
                'amount' => $package->price,
                'fee' => 0,
                'balance_before' => $balanceBefore,
                'balance_after' => $user->balance,
                'payment_method' => 'balance',
                'payment_gateway' => 'internal',
                'status' => 'completed',
                'description' => "Pembelian paket {$package->name}",
                'transactionable_type' => \App\Models\UserMembership::class,
                'transactionable_id' => $membership->id,
            ]);
        });
    }

    protected function calculateFee(float $amount, PaymentMethod $method): float
    {
        return $method->fee_fixed + ($amount * $method->fee_percentage / 100);
    }
}

// Gateway Interfaces
interface PaymentGatewayInterface
{
    public function createPayment(array $data): array;
    public function verifyCallback(array $data): array;
}

class MidtransGateway implements PaymentGatewayInterface
{
    public function createPayment(array $data): array
    {
        $serverKey = config('services. midtrans.server_key');
        $isProduction = config('services.midtrans.is_production');
        
        $baseUrl = $isProduction 
            ? 'https://api.midtrans.com' 
            : 'https://api. sandbox.midtrans.com';

        $response = Http::withBasicAuth($serverKey, '')
            ->post("{$baseUrl}/v2/charge", [
                'payment_type' => $data['payment_type'],
                'transaction_details' => [
                    'order_id' => $data['order_id'],
                    'gross_amount' => $data['amount'],
                ],
                'customer_details' => $data['customer'],
            ]);

        $result = $response->json();

        return [
            'transaction_id' => $result['transaction_id'] ?? null,
            'payment_url' => $result['redirect_url'] ?? null,
            'virtual_account' => $result['va_numbers'][0]['va_number'] ??  null,
        ];
    }

    public function verifyCallback(array $data): array
    {
        $serverKey = config('services.midtrans. server_key');
        $signatureKey = hash('sha512', 
            $data['order_id'] . $data['status_code'] . $data['gross_amount'] . $serverKey
        );

        return [
            'valid' => $signatureKey === $data['signature_key'],
            'order_id' => $data['order_id'],
            'status' => $data['transaction_status'] === 'settlement' ? 'success' : 'failed',
        ];
    }
}

class XenditGateway implements PaymentGatewayInterface
{
    public function createPayment(array $data): array
    {
        $apiKey = config('services.xendit.secret_key');
        
        $response = Http::withBasicAuth($apiKey, '')
            ->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $data['order_id'],
                'amount' => $data['amount'],
                'payer_email' => $data['customer']['email'],
                'description' => 'Top up saldo',
            ]);

        $result = $response->json();

        return [
            'transaction_id' => $result['id'] ?? null,
            'payment_url' => $result['invoice_url'] ?? null,
        ];
    }

    public function verifyCallback(array $data): array
    {
        $callbackToken = config('services.xendit.callback_token');
        $receivedToken = request()->header('x-callback-token');

        return [
            'valid' => $callbackToken === $receivedToken,
            'order_id' => $data['external_id'],
            'status' => $data['status'] === 'PAID' ? 'success' : 'failed',
        ];
    }
}