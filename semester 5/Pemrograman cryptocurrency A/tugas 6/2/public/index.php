<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

// Get statistics
$totalExchanges = $db->fetchOne("SELECT COUNT(*) as count FROM exchanges WHERE status = 'active'")['count'];
$totalPairs = $db->fetchOne("SELECT COUNT(*) as count FROM trading_pairs WHERE is_active = 1")['count'];
$totalTickers = $db->fetchOne("SELECT COUNT(*) as count FROM tickers WHERE DATE(created_at) = CURDATE()")['count'];

// Get categories
$categories = $db->fetchAll("SELECT DISTINCT category FROM exchanges WHERE category IS NOT NULL ORDER BY category");

// Get recent updates
$recentUpdates = $db->fetchAll("
    SELECT e.name, e.code, e.category, e.last_update, e.status,
           (SELECT COUNT(*) FROM trading_pairs WHERE exchange_id = e.id) as pairs_count
    FROM exchanges e
    ORDER BY e.last_update DESC
    LIMIT 10
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Exchange Aggregator Dashboard</title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="exchanges.php">Exchanges</a></li>
                    <li class="nav-item"><a class="nav-link" href="tickers.php">Tickers</a></li>
                    <li class="nav-item"><a class="nav-link" href="analytics.php">Analytics</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-building"></i> Active Exchanges</h5>
                        <h2><?php echo $totalExchanges; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-arrow-left-right"></i> Trading Pairs</h5>
                        <h2><?php echo $totalPairs; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-graph-up"></i> Today's Updates</h5>
                        <h2><?php echo $totalTickers; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-tags"></i> Exchange Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($categories as $cat): ?>
                            <?php 
                                $count = $db->fetchOne(
                                    "SELECT COUNT(*) as count FROM exchanges WHERE category = :cat AND status = 'active'",
                                    ['cat' => $cat['category']]
                                )['count'];
                            ?>
                            <div class="col-md-3 mb-3">
                                <a href="category.php?cat=<?php echo urlencode($cat['category']); ?>" class="text-decoration-none">
                                    <div class="card category-card">
                                        <div class="card-body text-center">
                                            <h3><?php echo $count; ?></h3>
                                            <p class="text-capitalize"><?php echo htmlspecialchars($cat['category']); ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Updates -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-clock-history"></i> Recent Updates</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Exchange</th>
                                        <th>Category</th>
                                        <th>Pairs</th>
                                        <th>Status</th>
                                        <th>Last Update</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentUpdates as $exchange): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($exchange['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($exchange['code']); ?></small>
                                        </td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($exchange['category']); ?></span></td>
                                        <td><?php echo $exchange['pairs_count']; ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'active' => 'success',
                                                'maintenance' => 'warning',
                                                'inactive' => 'danger'
                                            ];
                                            $class = $statusClass[$exchange['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo $class; ?>"><?php echo $exchange['status']; ?></span>
                                        </td>
                                        <td><?php echo $exchange['last_update'] ? date('Y-m-d H:i:s', strtotime($exchange['last_update'])) : 'Never'; ?></td>
                                        <td>
                                            <a href="exchange_detail.php?code=<?php echo $exchange['code']; ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 py-3 bg-dark text-white text-center">
        <p>&copy; 2025 Crypto Exchange Aggregator. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>