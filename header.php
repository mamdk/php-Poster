<?php
if (!isset($_SESSION)) {
    session_start();
}
include './connection.php';
$isAuth = true;

if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
    $isAuth = false;
}
?>


<link rel="stylesheet" href="./css/style.css">

<header>
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul id="ul-nav" class="navbar-nav">
                    <li class="nav-item " data-name="index">
                        <a style="white-space: nowrap;" class="nav-link position-relative active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <?php
                    if ($isAuth) {
                        $sql = "SELECT name FROM users WHERE id=:id";
                        $q = $conn->prepare($sql);
                        $q->execute([":id" => $_SESSION["user_id"]]);
                        $nameUser = $q->fetch(PDO::FETCH_ASSOC)["name"];
                    ?>
                        <li style="white-space: nowrap;" class="nav-item" data-name="user">
                            <a class="nav-link position-relative" href="./user.php?id=<?php echo $_SESSION["user_id"] ?>"><?php echo $nameUser; ?></a>
                        </li>
                        <li style="white-space: nowrap;" class="nav-item" data-name="signout">
                            <a class="nav-link position-relative" href="./signout.php">sign out</a>
                        </li>
                        <li style="white-space: nowrap;" class="nav-item" data-name="createPost">
                            <a class="nav-link position-relative" href="./createPost.php">create post</a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li style="white-space: nowrap;"  class="nav-item" data-name="signup">
                            <a class="nav-link position-relative" href="./signup.php">sign up</a>
                        </li>
                        <li style="white-space: nowrap;" class="nav-item" data-name="signin">
                            <a class="nav-link position-relative" href="./signin.php">sign in</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script src="./js/header.js"></script>
