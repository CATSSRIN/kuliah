<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
$code = $_GET['code'] ?? '';

if (empty($code)) {
    header('Location: index.php');
    exit;
}

// Get exchange info
$exchange = $db->fetchOne("SELECT * FROM exchanges WHERE code = :code", ['code' => $code]);

if (!$exchange) {
    die("Exchange not found");
}

// Get latest tickers
$tickers = $db->fetchAll("
    SELECT t.*, tp.base_currency, tp.quote_currency
    FROM tickers t
    JOIN trading_pairs tp ON t.trading_pair_id = tp.id
    WHERE t.exchange_id = :exchange_id
    AND t.created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
    ORDER BY t.volume_24h DESC
    LIMIT 50
", ['exchange_id' => $exchange['id']]);

// Get market stats
$stats = $db->fetchOne("
    SELECT * FROM market_stats
    WHERE exchange_id = :exchange_id
    ORDER BY created_at DESC
    LIMIT 1
", ['exchange_id' => $exchange['id']]);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($exchange['name']); ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-currency-exchange"></i> Crypto Exchange Aggregator
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="category.php?cat=<?php echo $exchange['category']; ?>"><?php echo htmlspecialchars($exchange['category']); ?></a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($exchange['name']); ?></li>
            </ol>
        </nav>

        <!-- Exchange Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3><?php echo htmlspecialchars($exchange['name']); ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Code:</strong> <?php echo htmlspecialchars($exchange['code']); ?></p>
                        <p><strong>Category:</strong> <span class="badge bg-secondary"><?php echo htmlspecialchars($exchange['category']); ?></span></p>
                        <p><strong>Status:</strong> <span class="badge bg-<?php echo $exchange['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo $exchange['status']; ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Last Update:</strong> <?php echo $exchange['last_update'] ? date('Y-m-d H:i:s', strtotime($exchange['last_update'])) : 'Never'; ?></p>
                        <?php if ($stats): ?>
                        <p><strong>Total Pairs:</strong> <?php echo $stats['total_pairs']; ?></p>
                        <p><strong>24h Volume:</strong> $<?php echo number_format($stats['total_volume_24h'], 2); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickers Table -->
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up"></i> Latest Tickers (Last Hour)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Last Price</th>
                                <th>24h Change</th>
                                <th>24h High</th>
                                <th>24h Low</th>
                                <th>24h Volume</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickers as $ticker): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($ticker['symbol']); ?></strong></td>
                                <td>$<?php echo number_format($ticker['last_price'], 8); ?></td>
                                <td class="<?php echo $ticker['price_change_percent_24h'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $ticker['price_change_percent_24h'] >= 0 ? '+' : ''; ?><?php echo number_format($ticker['price_change_percent_24h'], 2); ?>%
                                </td>
                                <td>$<?php echo number_format($ticker['high_24h'], 8); ?></td>
                                <td>$<?php echo number_format($ticker['low_24h'], 8); ?></td>
                                <td><?php echo number_format($ticker['volume_24h'], 2); ?></td>
                                <td><?php echo date('H:i:s', strtotime($ticker['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($tickers)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No recent ticker data available.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>