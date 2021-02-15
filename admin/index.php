<?php
session_start();
include '../connection.php';
$num = 3;

try {
    $sql = "SELECT * FROM post ORDER BY create_at DESC LIMIT :num";
    $q = $conn->prepare($sql);
    $q->execute([":num" => $num]);
    $posts = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

try {
    $sql = "SELECT * FROM users ORDER BY join_at DESC LIMIT :num";
    $q = $conn->prepare($sql);
    $q->execute([":num" => $num]);
    $users = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

try {
    $sql = "SELECT * FROM admin ORDER BY join_at";
    $q = $conn->prepare($sql);
    $q->execute();
    $admins = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Admin</title>
</head>

<body>
    <?php include_once './header.php' ?>

    <section>
        <div class="container row">
            <h1 class="display-4" style="margin-top: 65px;">Admin Page</h1>

            <div class="row">
                <div class="col-12">
                    <ul class="list-group">
                        <h2 class="display-5">Recent Posts</h2>
                        <?php
                        foreach ($posts as $post) {
                            $timePost = strtotime($post["create_at"]);
                            $diff = time() - $timePost;
                            $days = floor($diff / (24 * 60 * 60));
                            $hours = floor(($diff / (60 * 60)) - ($days * (24)));;
                        ?>
                            <li class="list-group-item  flex-column align-items-start ">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?php echo $post["title"]; ?></h5>
                                    <small><?php echo $days; ?> days / <?php echo $hours; ?> hours ago</small>
                                </div>
                                <p class="mb-1"><?php echo substr($post["body"], 0, 100); ?></p>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>

                <div class="col-12 mt-4">
                    <ul class="list-group">
                        <h2 class="display-5">Recent Users</h2>
                        <?php
                        foreach ($users as $user) {
                            $timeuser = strtotime($user["join_at"]);
                            $diff = time() - $timeuser;
                            $days = floor($diff / (24 * 60 * 60));
                            $hours = floor(($diff / (60 * 60)) - ($days * (24)));
                        ?>
                            <li class="list-group-item  flex-column align-items-start ">
                                <div class="w-100 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1"><?php echo $user["name"]; ?></h5>
                                    <small><?php echo $days; ?> days / <?php echo $hours; ?> hours ago</small>
                                </div>
                                <p class="mt-1"><?php echo $user["email"] ?></p>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>

                <div class="col-12 mt-4">
                    <ul class="list-group">
                        <h2 class="display-5">Admins</h2>
                        <?php
                        foreach ($admins as $admin) {
                            $timeadmin = strtotime($admin["join_at"]);
                            $diff = time() - $timeadmin;
                            $days = floor($diff / (24 * 60 * 60));
                            $hours = floor(($diff / (60 * 60)) - ($days * (24)));
                        ?>
                            <li class="list-group-item  flex-column align-items-start ">
                                <div class="w-100 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1"><?php echo $admin["email"]; ?></h5>
                                    <small><?php echo $days; ?> days / <?php echo $hours; ?> hours ago</small>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>

                <div class="col-12 mt-4"></div>
            </div>
        </div>
    </section>
</body>

</html>