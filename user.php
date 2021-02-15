<?php

session_start();
include './connection.php';
$auth = false;

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("location: index.php");
}

$id = $_GET["id"];

if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $id) {
    $auth = true;
}

try {
    $sql = "SELECT * FROM users WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $id]);
    $res = $q->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (!isset($res["img_bg"]) || empty($res["img_bg"]) || !file_exists("./upload/bg/" . $res["img"])) {
    $res["img_bg"] = "bg.jpg";
}
if (!isset($res["img"]) || empty($res["img"]) || !file_exists("./upload/profile/" . $res["img"])) {
    $res["img"] = "img.png";
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <title>User Profile</title>
</head>

<body>
    <?php include './header.php' ?>
    <div class="container d-flex flex-column">
        <h1 class="display-4" style="margin-top: 65px;">Profile</h1>
        <section class="profile mt-2">
            <div class="bg-up">
                <img src="./upload/bg/<?php echo $res["img_bg"] ?>" alt="<?php echo $res["name"]; ?>">
            </div>
            <div class="content">

                <div class="avatar">
                    <img alt="<?php echo $res["name"]; ?>" src="./upload/profile/<?php echo $res["img"] ?>">
                </div>
                <div class="info">
                    <h2><?php echo $res["name"]; ?></h2>
                    <h4><?php echo $res["email"]; ?></h4>
                    <p><?php echo $res["about"]; ?></p>
                </div>
                <div class="bottom">
                    <?php
                    if (isset($res["instagram"]) && !empty($res["instagram"])) {
                    ?>
                        <a target="_blank" class="btn btn-danger btn-sm" href="https://www.instagram.com/<?php echo $res["instagram"] ?>">
                            <i class="fa fa-instagram"></i>
                        </a>
                    <?php
                    }
                    ?>

                    <?php
                    if (isset($res["facebook"]) && !empty($res["facebook"])) {
                    ?>
                        <a target="_blank" class="btn btn-primary btn-facebook btn-sm" href="https://www.facebook.com/<?php echo $res["facebook"] ?>">
                            <i class="fa fa-facebook"></i>
                        </a>
                    <?php
                    }
                    ?>

                    <?php
                    if (isset($res["twitter"]) && !empty($res["twitter"])) {
                    ?>
                        <a target="_blank" class="btn btn-info text-light btn-twitter btn-sm" href="https://www.twitter.com/<?php echo $res["twitter"] ?>">
                            <i class="fa fa-twitter"></i>
                        </a>
                    <?php
                    }
                    ?>

                    <?php
                    if (isset($res["github"]) && !empty($res["github"])) {
                    ?>
                        <a target="_blank" class="btn btn-dark btn-sm" href="https://www.github.com/<?php echo $res["github"] ?>">
                            <i class="fa fa-github"></i>
                        </a>
                    <?php
                    }
                    ?>
                </div>
                <?php
                if ($auth) {
                ?>
                    <div style="width: 100%;" class="flex-row d-flex justify-content-around align-items-center mt-4">
                        <a class="btn btn-success col-3" href="editProfile.php?id=<?php echo $res["id"] ?>">Edit</a>
                        <a class="btn btn-danger col-3" href="deleteProfile.php?id=<?php echo $res["id"] ?>">delete</a>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
        <section class="posts mt-2" >
            <h1 class="display-5" ><?php echo $res["name"]; ?>'s Posts</h1>
            <div class="col-12">
                <?php
                $posts = [];
                try {
                    $sql = "SELECT * FROM post WHERE user_id=:id ORDER BY create_at DESC";
                    $q = $conn->prepare($sql);
                    $q->execute([":id" => $res["id"]]);
                    $posts = $q->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

                foreach ($posts as $post) {
                    if (!isset($post["img"]) || empty($post["img"]) || !file_exists("./upload/" . $post["img"])) {
                        $post["img"] = "placeholder.png";
                    }
                ?>
                    <div class="card m-2">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img src="./upload/<?php echo $post["img"]; ?>" class="card-img" alt="<?php echo $post["title"]; ?>">
                            </div>
                            <div class="card-body col-md-8">
                                <h5 class="card-title display-5"><?php echo $post["title"]; ?>
                                <small>(Enable:<?php echo (boolval($post["state"])) ? "true" : "false"; ?>)</small>
                                </h5>
                                <p class="card-text"><?php echo substr($post["body"], 0, 200); ?>....</p>
                                <small class="card-footer"><?php echo $post["create_at"] ?></small>
                                <?php
                                if ($auth) {
                                ?>
                                    <a class="btn btn-primary" href="./editPost.php?post_id=<?php echo $post["id"] ?>">Edit post</a>
                                    <a class="btn btn-warning" href="./deletePost.php?post_id=<?php echo $post["id"] ?>">Delete post</a>
                                <?php
                                } else {
                                ?>
                                    <a class="btn btn-info" href="./post.php?post_id=<?php echo $post["id"] ?>">Read more</a>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
    </div>
</body>

</html>