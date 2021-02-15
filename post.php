<?php
include './connection.php';


if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    header("location: index.php");
}

$post_id = $_GET['post_id'];

try {
    $sql = "SELECT * FROM post WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $post_id]);
    $post = $q->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (!isset($post['img']) || empty($post['img']) || !file_exists("./upload/" . $post["img"])) {
    $post['img'] = "placeholder.png";
}

try {
    $sql = "SELECT name FROM users WHERE id=:user_id";
    $q = $conn->prepare($sql);
    $q->execute([":user_id" => $post["user_id"]]);
    $author = $q->fetch(PDO::FETCH_ASSOC)["name"];
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
    <link rel="stylesheet" href="./css/style.css">
    <title>Post</title>
</head>

<body>
    <?php include './header.php'; ?>
    <section>
        <div class="container">
            <h1 class="display-4" style="margin-top: 65px;">Post</h1>
            <div class="card col-md-12 mt-2">
                <img class="card-img-top" style="border-radius: 10px;" src="./upload/<?php echo $post['img']; ?>" alt="<?php echo $post['title']; ?>">
                <div class="card-body">
                    <h5 class="card-title display-6"><?php echo $post['title']; ?></h5>
                    <h4 class="card-text h4"><?php echo $post['body']; ?></h4>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        create by <a href="./user.php?id=<?php echo $post["user_id"]; ?>" class="btn btn-link p-0 text-danger"><?php echo $author; ?></a>
                        at <?php echo $post["create_at"]; ?>
                    </small>
                </div>
            </div>
        </div>
    </section>
</body>

</html>