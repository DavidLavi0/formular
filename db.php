<?php
$host = "localhost";
$port = 3306;
$dbname = "moje_db";
$user = "root";
$pass = "root";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Chyba připojení: " . $e->getMessage());
}
?>
