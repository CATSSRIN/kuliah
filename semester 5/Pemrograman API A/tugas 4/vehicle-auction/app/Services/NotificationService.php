<?php

namespace App\Services;

use App\Models\User;
use App\Models\Auction;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuctionNotification;

class NotificationService
{
    public function send(User $user, string $type, array $data, array $channels = null): void
    {
        $settings = $user->notificationSettings;
        
        if (! $settings) {
            $settings = $user->notificationSettings()->create([]);
        }

        $channels = $channels ?? $this->getEnabledChannels($settings, $type);

        foreach ($channels as $channel) {
            $this->sendViaChannel($user, $channel, $type, $data);
        }
    }

    protected function getEnabledChannels($settings, string $type): array
    {
        $channels = [];
        
        $typeMapping = [
            'outbid' => 'notify_outbid',
            'auction_end' => 'notify_auction_end',
            'win' => 'notify_win',
            'payment' => 'notify_payment',
            'new_bid' => 'notify_new_bid',
        ];

        $settingKey = $typeMapping[$type] ??  null;
        
        if ($settingKey && ! $settings->$settingKey) {
            return [];
        }

        if ($settings->email_enabled) $channels[] = 'email';
        if ($settings->telegram_enabled) $channels[] = 'telegram';
        if ($settings->whatsapp_enabled) $channels[] = 'whatsapp';

        return $channels;
    }

    protected function sendViaChannel(User $user, string $channel, string $type, array $data): void
    {
        $log = NotificationLog::create([
            'user_id' => $user->id,
            'channel' => $channel,
            'type' => $type,
            'message' => $this->formatMessage($type, $data),
            'data' => $data,
            'status' => 'pending',
        ]);

        try {
            match ($channel) {
                'email' => $this->sendEmail($user, $type, $data),
                'telegram' => $this->sendTelegram($user, $type, $data),
                'whatsapp' => $this->sendWhatsApp($user, $type, $data),
                default => throw new \Exception("Unknown channel: {$channel}"),
            };

            $log->update(['status' => 'sent', 'sent_at' => now()]);
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        }
    }

    protected function sendEmail(User $user, string $type, array $data): void
    {
        Mail::to($user->email)->send(new AuctionNotification($type, $data));
    }

    protected function sendTelegram(User $user, string $type, array $data): void
    {
        if (!$user->telegram_chat_id) {
            throw new \Exception('User has no Telegram chat ID');
        }

        $botToken = config('services.telegram.bot_token');
        $message = $this->formatMessage($type, $data);

        Http::post("https://api. telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $user->telegram_chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);
    }

    protected function sendWhatsApp(User $user, string $type, array $data): void
    {
        if (!$user->whatsapp_number) {
            throw new \Exception('User has no WhatsApp number');
        }

        // Using Fonnte API (or you can use other WhatsApp API providers)
        $apiKey = config('services.fonnte.api_key');
        $message = $this->formatMessage($type, $data);

        Http::withHeaders(['Authorization' => $apiKey])
            ->post('https://api.fonnte. com/send', [
                'target' => $user->whatsapp_number,
                'message' => $message,
            ]);
    }

    protected function formatMessage(string $type, array $data): string
    {
        return match ($type) {
            'outbid' => $this->formatOutbidMessage($data),
            'win' => $this->formatWinMessage($data),
            'auction_end' => $this->formatAuctionEndMessage($data),
            'payment_reminder' => $this->formatPaymentReminderMessage($data),
            'forfeit' => $this->formatForfeitMessage($data),
            'reopen' => $this->formatReopenMessage($data),
            default => 'Notifikasi dari Vehicle Auction',
        };
    }

    protected function formatOutbidMessage(array $data): string
    {
        $auction = $data['auction'];
        return "⚠️ ANDA TELAH DI-OUTBID!\n\n" .
               "Lelang: {$auction->vehicle->title}\n" . 
               "Bid tertinggi saat ini: Rp " . number_format($data['new_amount'], 0, ',', '.') . "\n\n" . 
               "Segera pasang bid baru untuk memenangkan lelang ini!";
    }

    protected function formatWinMessage(array $data): string
    {
        $auction = $data['auction'];
        return "🎉 SELAMAT!  ANDA MEMENANGKAN LELANG!\n\n" .
               "Kendaraan: {$auction->vehicle->title}\n" .
               "Harga: Rp " . number_format($auction->winning_amount, 0, ',', '.') . "\n" .
               "Batas Pembayaran: {$auction->payment_deadline->format('d M Y H:i')}\n\n" . 
               "Segera lakukan pembayaran untuk menyelesaikan transaksi. ";
    }

    protected function formatAuctionEndMessage(array $data): string
    {
        $auction = $data['auction'];
        return "🔔 LELANG TELAH BERAKHIR\n\n" . 
               "Kendaraan: {$auction->vehicle->title}\n" . 
               "Harga Akhir: Rp " . number_format($auction->current_price ??  0, 0, ',', '.') . "\n" . 
               "Total Bid: {$auction->total_bids}";
    }

    protected function formatPaymentReminderMessage(array $data): string
    {
        $auction = $data['auction'];
        return "⏰ PENGINGAT PEMBAYARAN\n\n" . 
               "Anda memiliki waktu tersisa untuk membayar lelang:\n" .
               "Kendaraan: {$auction->vehicle->title}\n" . 
               "Deadline: {$auction->payment_deadline->format('d M Y H:i')}\n\n" . 
               "Jika tidak dibayar, deposit Anda akan hangus! ";
    }

    protected function formatForfeitMessage(array $data): string
    {
        $auction = $data['auction'];
        return "❌ DEPOSIT HANGUS\n\n" .
               "Deposit Anda untuk lelang berikut telah hangus karena tidak melakukan pembayaran:\n" .
               "Kendaraan: {$auction->vehicle->title}";
    }

    protected function formatReopenMessage(array $data): string
    {
        $auction = $data['auction'];
        return "🔄 LELANG DIBUKA KEMBALI\n\n" .
               "Lelang berikut dibuka kembali karena pemenang sebelumnya tidak membayar:\n" .
               "Kendaraan: {$auction->vehicle->title}\n" . 
               "Berakhir: {$auction->end_time->format('d M Y H:i')}\n\n" .
               "Kesempatan Anda untuk memenangkan lelang ini! ";
    }

    // Specific notification methods
    public function sendWinNotification(Auction $auction): void
    {
        $this->send($auction->winner, 'win', ['auction' => $auction]);
    }

    public function sendForfeitNotification(Auction $auction): void
    {
        $user = User::find($auction->winner_id);
        if ($user) {
            $this->send($user, 'forfeit', ['auction' => $auction]);
        }
    }

    public function sendReopenNotification(Auction $auction): void
    {
        // Notify all previous bidders
        $bidders = $auction->bids()
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id');

        foreach ($bidders as $user) {
            $this->send($user, 'reopen', ['auction' => $auction]);
        }
    }
}