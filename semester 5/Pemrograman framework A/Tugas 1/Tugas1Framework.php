<?php
$request_uri = $_SERVER['REQUEST_URI'];
$page = 'home'; //default page

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

?>

<!DOCTYPE html>
<html>
<head>  
    <title>Coba Routing</title>
</head>
<body>
    <nav>
        <a href="?page=home"> 
        <button>Home</button></a> | 
        <a href="?page=about">
        <button>About</button></a> |
        <a href="?page=profile"> 
        <button>Profile</button></a> |
        <a href="?page=contact">
        <button>Contact</button></a> |
    </nav>

<?php
if ($page == 'home') {
    echo "<h2>Selamat Datang di home</h2>";
    echo "<body> selamat datang di landing page / home </body><br>";
} elseif ($page == 'about') {
    echo "<h2>About Us</h2>";
    echo "<body> ini adalah halaman about </body><br>";
} elseif ($page == 'profile') {
    echo "<h2>Nama || NPM || no Wa</h2>";
    echo "<body> | Caezarlov Nugraha || 23081010182 || 08170040805 | </body><br>";
} elseif ($page == 'contact') {
    echo "<h2>Contact Us</h2>";
    echo "<body> Hubungi saya di Lappy@waifu.club </body><br>";
} else {
    echo "<h2>404 Page Not Found</h2>";
}
?>

</body>
</html>