<?php

include '../connection.php';

$page = 0;
if (isset($_GET['page']) || !empty($_GET['page'])) {
    $page = $_GET['page'] - 1;
}

try {
    $sql = "SELECT * FROM post";
    $q = $conn->prepare($sql);
    $q->execute();
    $res = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}


$numPosts = count($res);

$numPart = isset($num) || !empty($num) ? $num : 10;

$numPages = ceil($numPosts / $numPart);

$maxLen = count(str_split($numPages . ""));
?>

<link rel="stylesheet" href="../css/style.css">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

<footer>
    <div class="container fixed-bottom d-flex justify-content-center align-item-center" style="bottom: 50px; height: 30px;">
        <input id="page" type="hidden" value="<?php echo $page; ?>">
        <div class="count d-flex justify-content-center align-item-center bg-dark" style="position: relative;border-radius: 10px;">
            <div style="overflow: hidden;">
                <?php
                for ($i = 0; $i < $numPages; $i++) {
                ?>
                    <a class="links text-light" style="position: absolute;text-decoration: none;" href="./posts.php?page=<?php echo ($i + 1) ?>"><?php echo sprintf("%0" . $maxLen . "d", $i + 1) ?></a>
                <?php
                }
                ?>
            </div>
            <a href="./posts.php?page=<?php echo ($page) ? $page : $page + 1; ?>" class="h-100 btn btn-link d-flex justify-content-center align-items-center text-light bg-dark" style="border-radius: 10px;position: absolute;left:0; top:0;text-decoration: none;" id="less">&lt;</a>

            <a href="./posts.php?page=<?php echo ($page < ($numPages - 1)) ? $page + 2 : $numPages; ?>" class="h-100 btn btn-link d-flex justify-content-center align-items-center text-light bg-dark" style="border-radius: 
            10px;position: absolute;right:0; top:0;text-decoration: none;" id="add">&gt;</a>

            <!-- last page & first page -->

            <a href="./posts.php?page=<?php echo 1; ?>" class="h-100 btn btn-link d-flex justify-content-center align-items-center text-light bg-dark" style="border-radius: 10px;position: absolute;right:100%; top:0;text-decoration: none;"><i class="fa fa-angle-double-left"></i></a>

            <a href="./posts.php?page=<?php echo $numPages; ?>" class="h-100 btn btn-link d-flex justify-content-center align-items-center text-light bg-dark" style="border-radius: 10px;position: absolute;left:100%; top:0;text-decoration: none;"><i class="fa fa-angle-double-right"></i></a>
            ?>
        </div>
    </div>
</footer>

<script src="../js/counter.js"></script>