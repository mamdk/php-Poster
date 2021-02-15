<?php

include '../connection.php';

if (!isset($_GET["post_id"]) || empty($_GET["post_id"])) {
    header("location: ./index.php");
}

if (!isset($_GET["toState"]) || empty($_GET["toState"])) {
    header("location: ./index.php");
}

$post_id = $_GET["post_id"];
$state = $_GET["toState"];


try {
    $sql = "UPDATE post SET state=:state WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $post_id, ":state" => $state]);

    header("location: ./posts.php");
} catch (PDOException $e) {
    echo $e->getMessage();
}
