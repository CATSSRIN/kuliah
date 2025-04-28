<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head> 

<body> 
<h1>Welcome to the Car Info Page</h1>
<p>Below is the information about the car:</p>
<?php
$brand = "volvo";
$color = "red";

function getCarInfo($brand, $color) {
    return "The car is a $color $brand.";
}
echo getCarInfo($brand, $color);
?>

</body>
</html>