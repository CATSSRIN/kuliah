<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Purple Dark Mode Theme */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #1a1a2e; /* Dark purple-blue */
            color: #e0e0e0; /* Light grey text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            padding: 40px;
            background-color: #16213e; /* Slightly lighter dark blue */
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        h1 {
            color: #c792ea; /* Lavender color for the heading */
            margin-bottom: 30px;
        }

        .button-group {
            display: flex;
            flex-direction: column; /* Stack buttons vertically on small screens */
            gap: 15px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            color: #ffffff;
            background-color: #5a3a8a; /* Rich purple */
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #6a4a9a; /* Lighter purple on hover */
            border-color: #c792ea; /* Lavender border on hover */
            transform: translateY(-3px); /* Slight lift effect */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Responsive design for wider screens */
        @media (min-width: 600px) {
            .button-group {
                flex-direction: row; /* Buttons side-by-side on larger screens */
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Navigation</h1>
        <div class="button-group">
            <!-- The 'a' tag is used for simple redirection. -->
            <a href="./1/" class="btn">E-manga</a>
            <a href="./2/" class="btn">Tickers</a>
            <a href="./3/" class="btn">Trade</a>
        </div>
    </div>

</body>
</html>
