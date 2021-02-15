<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["admin_id"]) || empty($_SESSION["admin_id"])) {
    header("location: ./signin.php");
}

?>


<link rel="stylesheet" href="../css/style.css">

<header>
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul id="ul-nav" class="navbar-nav">
                    <li class="nav-item " data-name="index">
                        <a style="white-space: nowrap;" class="nav-link position-relative active" aria-current="page" href="./index.php">Admin</a>
                    </li>
                    <li class="nav-item " data-name="posts">
                        <a style="white-space: nowrap;" class="nav-link position-relative" href="./posts.php">Posts</a>
                    </li>
                    <li class="nav-item " data-name="users">
                        <a style="white-space: nowrap;" class="nav-link position-relative" href="./users.php">Users</a>
                    </li>
                    <li class="nav-item " data-name="admin">
                        <a style="white-space: nowrap;" class="nav-link position-relative" href="./admin.php">New Admin</a>
                    </li>
                    <li class="nav-item " data-name="signout">
                        <a style="white-space: nowrap;" class="nav-link position-relative" href="./signout.php">Sign out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script src="../js/header.js"></script>