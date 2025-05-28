<?php
// Database credentials
$servername = "localhost"; // Replace with your database server name
$username = "your_db_username"; // Replace with your database username
$password = "your_db_password"; // Replace with your database password
$dbname = "your_db_name"; // Replace with your database name
$tablename = "users"; // Replace with your desired table name

try {
    // Create a PDO connection to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO $tablename (name, birthday, job) VALUES (:name, :birthday, :job)");

    // Bind the parameters to the statement
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':birthday', $birthday);
    $stmt->bindParam(':job', $job);

    // Get the data from the POST request
    $name = $_POST['name'];
    $birthday = $_POST['birthday'];
    $job = $_POST['job'];

    // Execute the prepared statement
    $stmt->execute();

    // Optionally, you can redirect the user back to the form with a success message
    header("Location: index.html?success=1");
    exit();

    // Or, if you prefer to display the success message directly from PHP (without redirecting):
    // echo '<div style="margin-top: 20px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; text-align: center;">Data submitted successfully!</div>';
    // echo '<p><a href="index.html">Go back to the form</a></p>';

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null; // Close the database connection
?>