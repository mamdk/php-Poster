<?php

include '../connection.php';

if (!isset($_GET["user_id"]) || empty($_GET["user_id"])) {
    header("location: ./index.php");
}

if (!isset($_GET["toPost"]) || empty($_GET["toPost"])) {
    header("location: ./index.php");
}

$user_id = $_GET["user_id"];
$state = $_GET["toPost"];


try {
    $sql = "UPDATE users SET toPost=:state WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $user_id, ":state" => $state]);

    header("location: ./users.php");
} catch (PDOException $e) {
    echo $e->getMessage();
}

