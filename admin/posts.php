<?php
include '../connection.php';

$page = 0;
$num = 10;

if (isset($_GET["page"]) && !empty($_GET["page"])) {
    $page = $_GET["page"] - 1;
}

try {
    $sql = "SELECT * FROM post ORDER BY state LIMIT :skip,:num";
    $q = $conn->prepare($sql);
    $q->execute([":skip" => $page * $num, ":num" => $num]);
    $posts = $q->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Posts</title>
</head>

<body>
    <?php include './header.php'; ?>


    <section>
        <div class="container">
            <h1 class="display-4" style="margin-top: 65px;">Posts</h1>
            <div class="card-deck row">
                <?php
                foreach ($posts as $post) {
                    if (!isset($post['img']) || empty($post['img']) || !file_exists("../upload/" . $post["img"])) {
                        $post['img'] = "placeholder.png";
                    }

                    $sql = "SELECT name FROM users WHERE id=:id";
                    $q = $conn->prepare($sql);
                    $q->execute([":id" => $post["user_id"]]);
                    $author = $q->fetch(PDO::FETCH_ASSOC)["name"];
                ?>
                    <div class="card col-md-4 mt-2 hover">
                        <img class="card-img-top" style="border-radius: 10px;" src="../upload/<?php echo $post['img']; ?>" alt="<?php echo $post['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title display-6"><?php echo $post['title']; ?></h5>
                            <p class="card-text"><?php echo substr($post['body'], 0, 150); ?>...</p>
                            <a href="./post.php?post_id=<?php echo $post["id"] ?>" class="btn btn-primary">Read more</a>
                        </div>
                        <div class="card-footer text-center">
                            <?php
                            if ($post["state"]) {
                            ?>
                                <span class="display-5 text-light bg-success">Enable</span>
                            <?php
                            } else {
                            ?>
                                <span class="display-5 text-light bg-warning">Unenable</span>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                create by <a href="./user.php?id=<?php echo $post["user_id"]; ?>" class="btn btn-link p-0 text-danger"><?php echo $author; ?></a>
                                at <?php echo $post["create_at"]; ?>
                            </small>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>

    <?php include './counterPost.php'; ?>
</body>

</html>