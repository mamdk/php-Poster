<?php
session_start();
include './connection.php';
$user_id = $_SESSION["user_id"];
$err = [];

try {
    $sql = "SELECT * FROM users WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $user_id]);
    $user = $q->fetchAll(PDO::FETCH_ASSOC);

    if (!$user["toPost"]) {
        array_push($err, "you can't create new post!!");
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (isset($_POST["submit"]) && !empty($_POST["submit"])) {
    $title = $_POST["title"];
    $body = $_POST["body"];
    $img = "";


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

        if (empty($err)) {
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

    if (!isset($err) && empty($err)) {
        try {
            $sql = "INSERT INTO post (user_id,title,body,img) VALUES (:user_id,:title,:body,:img)";
            $q = $conn->prepare($sql);
            $q->execute([":user_id" => $user_id, ":title" => htmlspecialchars($title), ":body" => htmlspecialchars($body), ":img" => $img]);

            header("location: index.php");
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
    <title>Create Post</title>
</head>

<body>
    <?php include_once './header.php' ?>

    <div class="container">
        <h1 class="display-4" style="margin-top: 65px;">create new post</h1>

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

        <form action="createPost.php" method="post" enctype="multipart/form-data">
            <div class="form-group m-1">
                <label for="title">title</label>
                <input type="text" value="<?php if (isset($title)) echo $title; ?>" name="title" class="form-control" id="title" placeholder="title">
            </div>
            <div class="form-group m-1">
                <label for="body">body</label>
                <textarea type="text" name="body" value="<?php if (isset($body)) echo $body; ?>" class="form-control" id="body" placeholder="body of post..."></textarea>
            </div>

            <div class="form-group m-1">
                <label for="photo">choose Photo</label>
                <input type="file" name="file" class="form-control" id="file">
            </div>

            <input type="submit" name="submit" class="btn btn-primary mt-3" value="Create" />
        </form>

    </div>
</body>

</html>