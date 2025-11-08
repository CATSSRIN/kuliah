<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
$category = $_GET['cat'] ?? '';

if (empty($category)) {
    header('Location: index.php');
    exit;
}

// Get exchanges in this category
$exchanges = $db->fetchAll("
    SELECT e.*,
           (SELECT COUNT(*) FROM trading_pairs WHERE exchange_id = e.id) as pairs_count,
           (SELECT COUNT(*) FROM tickers WHERE exchange_id = e.id AND DATE(created_at) = CURDATE()) as today_updates
    FROM exchanges e
    WHERE e.category = :category
    ORDER BY e.name
", ['category' => $category]);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(ucfirst($category)); ?> Exchanges - Crypto Aggregator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
                <li class="breadcrumb-item active"><?php echo htmlspecialchars(ucfirst($category)); ?></li>
            </ol>
        </nav>

        <h2 class="mb-4"><?php echo htmlspecialchars(ucfirst($category)); ?> Exchanges</h2>

        <div class="row">
            <?php foreach ($exchanges as $exchange): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-<?php echo $exchange['status'] === 'active' ? 'success' : 'warning'; ?> text-white">
                        <h5 class="mb-0"><?php echo htmlspecialchars($exchange['name']); ?></h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Code:</strong> <?php echo htmlspecialchars($exchange['code']); ?></p>
                        <p><strong>Trading Pairs:</strong> <?php echo $exchange['pairs_count']; ?></p>
                        <p><strong>Today's Updates:</strong> <?php echo $exchange['today_updates']; ?></p>
                        <p><strong>Status:</strong> <span class="badge bg-<?php echo $exchange['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo $exchange['status']; ?></span></p>
                        <p><strong>Last Update:</strong><br><?php echo $exchange['last_update'] ? date('Y-m-d H:i:s', strtotime($exchange['last_update'])) : 'Never'; ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="exchange_detail.php?code=<?php echo $exchange['code']; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($exchanges)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No exchanges found in this category.
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>