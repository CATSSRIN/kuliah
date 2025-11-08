<?php
/**
 * Display Data Script
 *
 * Shows the ticker data with pagination and search functionality.
 *
 * @package VindaxDataApp
 */

// Include the database configuration
require 'database_config.php';

// Pagination settings
$itemsPerPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Search filter settings
$searchSymbol = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Build the query dynamically based on search input
$sql = "SELECT * FROM tickers WHERE symbol LIKE :search LIMIT :offset, :itemsPerPage";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $searchSymbol . '%', PDO::PARAM_STR);
$stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
$stmt->bindValue(':itemsPerPage', (int) $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();

$tickers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total rows for pagination
$totalRows = $pdo->query("SELECT COUNT(*) FROM tickers WHERE symbol LIKE '%$searchSymbol%'")->fetchColumn();
$totalPages = ceil($totalRows / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vindax Ticker Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        form {
            margin-bottom: 20px;
        }
        .pagination {
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Vindax Ticker Data</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Search by Symbol" value="<?= htmlspecialchars($searchSymbol) ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Symbol</th>
                <th>Price Change (%)</th>
                <th>Last Price</th>
                <th>Volume</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickers as $ticker): ?>
                <tr>
                    <td><?= htmlspecialchars($ticker['symbol']) ?></td>
                    <td><?= htmlspecialchars(number_format($ticker['priceChangePercentage'], 2)) ?>%</td>
                    <td>$<?= htmlspecialchars(number_format($ticker['lastPrice'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format($ticker['volume'], 2)) ?></td>
                    <td><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($ticker['timestamp']))) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($searchSymbol) ?>" <?= $i === $page ? 'style="background:#0056b3;"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>