<?php
$username = "root";
$host = "localhost";
$pass = "";
$dbname = "web_post";
$option = [
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $pass, $option);
} catch (PDOException $e) {
    echo $e->getMessage();
}
