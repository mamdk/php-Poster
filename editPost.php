<?php
session_start();
include './connection.php';
$user_id = $_SESSION["user_id"];
$post_id = $_GET["post_id"];
$err = [];

try {
    $sql = "SELECT * FROM post WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $post_id]);
    $post = $q->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (isset($_POST["submit"]) && !empty($_POST["submit"])) {
    $title = $_POST["title"];
    $body = $_POST["body"];
    $img = $post["img"];

    if (isset($_FILES["file"]) && !empty($_FILES['file']) && !empty($_FILES["file"]["tmp_name"])) {
        $size = $_FILES["file"]["size"];
        $name = explode(".", $_FILES["file"]["name"])[0];
        $tmp = $_FILES["file"]["tmp_name"];
        $type = $_FILES["file"]["type"];
        $sufixx = explode("/", $type)[1];

        if (!in_array("image", explode("/", $type))) {
            array_push($err, "file should just photo and img");
        }
        if (!in_array($sufixx, ["png", "jpg", "jpeg"])) {
            array_push($err, "file should just 'png' , 'jpg' , 'jpeg'");
        }
        if ($size > 500000) {
            array_push($err, "file is too large");
        }

        if (!isset($err) || empty($err)) {
            $nameFile = $name . round(microtime(true)) . "." . $sufixx;
            if (move_uploaded_file($tmp, "./upload/" . $nameFile)) {
                $img = $nameFile;
            } else {
                array_push($err, "file not upload");
            }
        }
    }

    if (!isset($title) || empty($title) || strlen($title) < 3 || strlen($title) > 50) {
        array_push($err, "title most be between 3 and 50 charcters");
    }

    if (!isset($body) || empty($body) || strlen($body) < 5) {
        array_push($err, "body of post most be between 5 and 250 charcters");
    }

    if (!isset($err) || empty($err)) {
        try {
            $sql = "UPDATE post SET title=:title, body=:body, img=:img, create_at=:date, state=0 WHERE id=:post_id";
            $q = $conn->prepare($sql);
            $q->execute([":post_id" => $post_id, ":title" => htmlspecialchars($title), ":body" => htmlspecialchars($body), ":img" => $img, ":date" => date('Y-m-d H:i:s')]);

            header("location: ./index.php");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>Edit Post</title>
</head>

<body>
    <?php include_once './header.php' ?>

    <div class="container">
        <h1 class="display-4" style="margin-top: 65px;">Edit post (<?php echo $post["title"] ?>)</h1>

        <?php
        if (isset($err) || !empty($err)) {
            foreach ($err as $e) {
        ?>
                <div class="alert alert-danger mt-2" role="alert">
                    <?php echo $e; ?>
                </div>
        <?php
            }
        }
        ?>
        <form action="editPost.php?post_id=<?php echo $post_id;?>" method="post" enctype="multipart/form-data">
            <div class="form-group m-1">
                <label for="title">title</label>
                <input type="text" value="<?php if (isset($post["title"])) echo $post["title"]; ?>" name="title" class="form-control" id="title" placeholder="title">
            </div>
            <div class="form-group m-1">
                <label for="body">body</label>
                <textarea rows="10" type="text" name="body" class="form-control" id="body" placeholder="body of post..."><?php if (isset($post["body"])) echo $post["body"]; ?></textarea>
            </div>

            <div class="form-group m-1">
                <label for="photo">choose Photo</label>
                <input type="file" name="file" class="form-control" id="file">
            </div>

            <input type="submit" name="submit" class="btn btn-primary mt-3" value="Edit" />
        </form>
    </div>
</body>

</html>