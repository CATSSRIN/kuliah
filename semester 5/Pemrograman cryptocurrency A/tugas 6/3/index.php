<?php
/**
 * Index Page: Landing Page for Navigation
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vindax Data App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        a {
            display: inline-block;
            margin: 10px;
            padding: 10px 15px;
            text-decoration: none;
            background: #007BFF;
            color: white;
            border-radius: 4px;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Vindax Data App</h1>
    <nav>
        <a href="fetch_data.php">Fetch Data</a>
        <a href="display_data.php">View Data</a>
    </nav>
</body>
</html>