<?php
session_start();

if (!isset($_SESSION['clustering_result'])) {
    die("Tidak ada hasil clustering.");
}

$clusters = $_SESSION['clustering_result'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Clustering</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <h2>Hasil Clustering K-Means</h2>

    <?php 
    $clusterIndex = 1;
    $clusterSizes = [];

    foreach ($clusters as $cluster) : 
        $clusterSizes[] = count($cluster);
    ?>
        <div class="card">
            <h3>Cluster <?= $clusterIndex ?> (<?= count($cluster) ?> data)</h3>
            <pre><?= print_r(array_slice($cluster, 0, 5), true) ?></pre>
        </div>
    <?php 
        $clusterIndex++;
    endforeach;
    ?>

    <h3>Visualisasi Jumlah Anggota Cluster</h3>
    <canvas id="clusterChart" width="400" height="200"></canvas>
</div>

<script>
    const ctx = document.getElementById('clusterChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Cluster 1', 'Cluster 2', 'Cluster 3', 'Cluster 4'],
            datasets: [{
                label: 'Jumlah Data',
                data: <?= json_encode($clusterSizes) ?>,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

</body>
</html>
