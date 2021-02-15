<?php
include './connection.php';

$num = 10;
$skip = 0;

if (!isset($_GET['page']) || empty($_GET['page'])) {
    $skip = 0;
} else {
    $skip = $_GET['page'] - 1;
}

try {
    $sql = "SELECT * FROM post ORDER BY create_at DESC LIMIT :skip,:num";
    $q = $conn->prepare($sql);
    $q->execute([":skip" => $skip * $num, ':num' => $num]);
    $res = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>

<link rel="stylesheet" href="./css/style.css">

<section>
    <div class="container">
        <h1 class="display-4" style="margin-top: 65px;">Posts</h1>
        <div class="card-deck row">
            <?php
            foreach ($res as $post) {
                if ($post["state"]) {
                    if (!isset($post['img']) || empty($post['img']) || !file_exists("./upload/" . $post["img"])) {
                        $post['img'] = "placeholder.png";
                    }

                    $sql = "SELECT name FROM users WHERE id=:id";
                    $q = $conn->prepare($sql);
                    $q->execute([":id" => $post["user_id"]]);
                    $author = $q->fetch(PDO::FETCH_ASSOC)["name"];
            ?>
                    <div class="card col-md-4 mt-2 hover">
                        <img class="card-img-top" style="border-radius: 10px;" src="./upload/<?php echo $post['img']; ?>" alt="<?php echo $post['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title display-6"><?php echo $post['title']; ?></h5>
                            <p class="card-text"><?php echo substr($post['body'], 0, 150); ?>...</p>
                            <a href="./post.php?post_id=<?php echo $post["id"] ?>" class="btn btn-primary">Read more</a>
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
            }
            ?>
        </div>
    </div>
</section>