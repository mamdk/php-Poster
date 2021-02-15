<?php
session_start();
include './connection.php';
$post_id = $_GET["post_id"];


try {
    $sql = "DELETE FROM post WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $post_id]);

    header("location: user.php?id=".$_SESSION["user_id"]);
} catch (PDOException $e) {
    echo $e->getMessage();
}
