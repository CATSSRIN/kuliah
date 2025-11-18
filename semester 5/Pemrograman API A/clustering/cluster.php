<?php
require 'vendor/autoload.php';
use Phpml\Clustering\KMeans;

function runClustering($csvPath)
{
    $rows = array_map('str_getcsv', file($csvPath));
    $header = array_shift($rows);

    $data = [];
    foreach ($rows as $row) {
        if (count($row) < 21) continue;

        $data[] = [
            (int)$row[0],                                 // Age
            $row[1] == 'Male' ? 1 : 0,                    // Gender
            (float)$row[2],                               // Weight
            (float)$row[3],                               // Height
            (float)$row[4],                               // BMI
            $row[5] == 'Yes' ? 1 : 0,                     // Smoking
            (float)$row[6],                               // Alcohol Intake
            (int)$row[7],                                 // Physical Activity
            $row[8] == 'Healthy' ? 1 : 0,                 // Diet
            (int)$row[9],                                 // Stress Level
            $row[10] == 'Yes' ? 1 : 0,                    // Hypertension
            $row[11] == 'Yes' ? 1 : 0,                    // Diabetes
            $row[12] == 'Yes' ? 1 : 0,                    // Hyperlipidemia
            $row[13] == 'Yes' ? 1 : 0,                    // Family History
            $row[14] == 'Yes' ? 1 : 0,                    // Previous Heart Attack
            (float)$row[15],                              // Systolic BP
            (float)$row[16],                              // Diastolic BP
            (float)$row[17],                              // Heart Rate
            (float)$row[18],                              // Blood Sugar Fasting
            (float)$row[19],                              // Total Cholesterol
            $row[20] == 'Yes' ? 1 : 0                     // Heart Disease
        ];
    }

    $kmeans = new KMeans(4);
    return $kmeans->cluster($data);
}

function analyzeCluster($clusterIndex, $stats)
{
    $age        = $stats['Age']['mean'];
    $bmi        = $stats['BMI']['mean'];
    $smoking    = $stats['Smoking']['mean'];
    $alcohol    = $stats['Alcohol_Intake']['mean'];
    $activity   = $stats['Physical_Activity']['mean'];
    $stress     = $stats['Stress_Level']['mean'];
    $hypertension = $stats['Hypertension']['mean'];
    $diabetes     = $stats['Diabetes']['mean'];
    $cholesterol  = $stats['Cholesterol_Total']['mean'];
    $systolic     = $stats['Systolic_BP']['mean'];
    $diastolic    = $stats['Diastolic_BP']['mean'];

    $riskScore = 0;

    // === Faktor Risiko Berdasarkan Literatur Kesehatan ===
    if ($age > 45) $riskScore += 2;
    if ($bmi > 27) $riskScore += 2;
    if ($smoking > 0.3) $riskScore += 2;
    if ($alcohol > 0.4) $riskScore += 1;
    if ($activity < 0.4) $riskScore += 1;
    if ($stress > 0.5) $riskScore += 1;
    if ($hypertension > 0.3) $riskScore += 2;
    if ($diabetes > 0.2) $riskScore += 2;
    if ($cholesterol > 200) $riskScore += 2;
    if ($systolic > 135 || $diastolic > 85) $riskScore += 2;

    // === Penentuan Risiko ===
    if ($riskScore <= 3)      $riskLevel = "Sangat Rendah";
    else if ($riskScore <= 6) $riskLevel = "Rendah";
    else if ($riskScore <= 9) $riskLevel = "Sedang";
    else if ($riskScore <= 12) $riskLevel = "Tinggi";
    else                      $riskLevel = "Sangat Tinggi";

    // === Buat Analisis Teks ===
    return [
        "cluster"  => $clusterIndex,
        "risk"     => $riskLevel,
        "details"  => [
            "Rata-rata usia: " . round($age, 1),
            "Rata-rata BMI: " . round($bmi, 1) . " (" . bmiCategory($bmi) . ")",
            "Kebiasaan merokok: " . percentage($smoking),
            "Konsumsi alkohol: " . percentage($alcohol),
            "Aktivitas fisik: " . percentage($activity),
            "Tingkat stres: " . percentage($stress),
            "Hipertensi: " . percentage($hypertension),
            "Diabetes: " . percentage($diabetes),
            "Kolesterol total: " . round($cholesterol, 1),
            "Tekanan darah: " . round($systolic) . "/" . round($diastolic),
        ]
    ];
}

function percentage($val) {
    return round($val * 100, 1) . "%";
}

function bmiCategory($bmi) {
    if ($bmi < 18.5) return "Underweight";
    if ($bmi < 25) return "Normal";
    if ($bmi < 30) return "Overweight";
    return "Obesity";
}
echo "\n=== ANALISIS OTOMATIS PER CLUSTER ===\n\n";

foreach ($clusterStats as $i => $stats) {
    $analysis = analyzeCluster($i, $stats);

    echo "Cluster {$analysis['cluster']} — Risiko: {$analysis['risk']}\n";
    foreach ($analysis['details'] as $line) {
        echo " - $line\n";
    }
    echo "\n";
}
