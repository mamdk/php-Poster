<?php
session_start();
include './connection.php';
$user_id = $_SESSION["user_id"];
$err = [];

try {
    $sql = "SELECT * FROM users WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $user_id]);
    $res = $q->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
    header("location: index.php");
}

if (isset($_POST["submit"]) && !empty($_POST["submit"])) {
    $nameUser = getValue("name", $res);
    $email = getValue("email", $res);
    $about = getValue("about", $res);
    $pass = getValue("password", $res);
    $insta = getValue("instagram", $res);
    $twitter = getValue("twitter", $res);
    $facebook = getValue("facebook", $res);
    $git = getValue("github", $res);
    $proImg = $res["img"];
    $bgImg = $res["img_bg"];

    if (isset($_FILES["profile"]["tmp_name"]) && !empty($_FILES["profile"]["tmp_name"])) {
        $size = $_FILES["profile"]["size"];
        $name = explode(".", $_FILES["profile"]["name"])[0];
        $tmp = $_FILES["profile"]["tmp_name"];
        $type = $_FILES["profile"]["type"];
        $sufixx = explode("/", $type)[1];

        if (!in_array("image", explode("/", $type))) {
            array_push($err, "profile should just photo and img");
        }
        if (!in_array($sufixx, ["png", "jpg", "jpeg"])) {
            array_push($err, "profile should just 'png' , 'jpg' , 'jpeg'");
        }
        if ($size > 1000000) {
            array_push($err, "profile is too large");
        }

        if (empty($err)) {
            $nameFileP = $name . round(microtime(true)) . "." . $sufixx;
            if (move_uploaded_file($tmp, "./upload/profile/" . $nameFileP)) {
                $proImg = $nameFileP;
            } else {
                array_push($err, "profile not upload");
            }
        }
    }

    if (isset($_FILES["avatar"]["tmp_name"]) && !empty($_FILES["avatar"]["tmp_name"])) {
        $size = $_FILES["avatar"]["size"];
        $name = explode(".", $_FILES["avatar"]["name"])[0];
        $tmp = $_FILES["avatar"]["tmp_name"];
        $type = $_FILES["avatar"]["type"];
        $sufixx = explode("/", $type)[1];

        if (!in_array("image", explode("/", $type))) {
            array_push($err, "avatar should just photo and img");
        }
        if (!in_array($sufixx, ["png", "jpg", "jpeg"])) {
            array_push($err, "avatar should just 'png' , 'jpg' , 'jpeg'");
        }
        if ($size > 1000000) {
            array_push($err, "avatar is too large");
        }

        if (empty($err)) {
            $nameFileB = $name . round(microtime(true)) . "." . $sufixx;
            if (move_uploaded_file($tmp, "./upload/bg/" . $nameFileB)) {
                $bgImg = $nameFileB;
            } else {
                array_push($err, "avatar not upload");
            }
        }
    }

    if (!isset($nameUser) || empty($nameUser) || strlen($nameUser) < 1 || strlen($nameUser) > 50) {
        array_push($err, "name most be between 1 and 50 charcters");
    }

    if ($email != $res["email"]) {
        try {
            $sql = "SELECT name FROM users WHERE email=:email";
            $q = $conn->prepare($sql);
            $q->execute([":email" => $email]);
            $existEmail = $q->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        if (isset($existEmail) || !empty($existEmail) || $existEmail) {
            array_push($err, "this email is already exsists");
            $email = $res["email"];
        }
    }

    if (!isset($pass) || empty($pass) || strlen($pass) < 8) {
        array_push($err, "password most be bigger of 8 charcters");
    }

    if (!isset($err) || empty($err)) {
        try {
            $sql = "UPDATE users SET name=:name , email=:email , password=:pass , about=:about , instagram=:insta , facebook=:facebook , twitter=:twitter , github=:git , img=:img , img_bg=:bgImg WHERE id=:id";

            $q = $conn->prepare($sql);

            $q->execute([
                ":name" => $nameUser, ":email" => $email, ":pass" => $pass, ":about" => $about, ":insta" => $insta, ":facebook" => $facebook, ":twitter" => $twitter, ":git" => $git,
                ":img" => $proImg, ":bgImg" => $bgImg, ":id" => $user_id
            ]);

            header("location: ./user.php");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}


function getValue($name, $res)
{

    if ($name === "about") {
        return $_POST[$name];
    }

    if (isset($_POST[$name]) && !empty($_POST[$name])) {
        if($name === "password") return sha1($_POST[$name].$res["salt"]);
        return $_POST[$name];
    }

    return $res[$name];
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
        <h1 class="display-4" style="margin-top: 65px;">Edit profile</h1>

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
        <form action="editProfile.php" method="post" enctype="multipart/form-data">
            <div class="form-group m-1">
                <label for="name">Name</label>
                <input type="text" value="<?php if (isset($res)) echo $res["name"]; ?>" name="name" class="form-control" id="name" placeholder="Name">
            </div>
            <div class="form-group m-1">
                <label for="email">Email</label>
                <input type="email" value="<?php if (isset($res)) echo $res["email"]; ?>" name="email" class="form-control" id="email" placeholder="Email">
            </div>
            <div class="form-group m-1">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="New Password">
            </div>
            <div class="form-group m-1">
                <label for="about">About</label>
                <input type="text" value="<?php if (isset($res)) echo $res["about"]; ?>" name="about" class="form-control" id="about" placeholder="About">
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-group jusitfy m-1">
                    <label for="instagram">Instagram</label>
                    <input type="text" value="<?php if (isset($res)) echo $res["instagram"]; ?>" name="instagram" class="form-control" id="instagram" placeholder="Instagram">
                </div>
                <div class="form-group m-1">
                    <label for="facebook">Facebook</label>
                    <input type="text" value="<?php if (isset($res)) echo $res["facebook"]; ?>" name="facebook" class="form-control" id="facebook" placeholder="Facebook">
                </div>
                <div class="form-group m-1">
                    <label for="twitter">Twitter</label>
                    <input type="text" value="<?php if (isset($res)) echo $res["twitter"]; ?>" name="twitter" class="form-control" id="twitter" placeholder="Twitter">
                </div>
                <div class="form-group m-1">
                    <label for="github">Github</label>
                    <input type="text" value="<?php if (isset($res)) echo $res["github"]; ?>" name="github" class="form-control" id="github" placeholder="Github">
                </div>
            </div>


            <div class="row">
                <div class="form-group col-md-6">
                    <label for="profile">Profile photo</label>
                    <input type="file" name="profile" class="form-control" id="profile">
                </div>
                <div class="form-group col-md-6">
                    <label for="avatar">Avatar photo</label>
                    <input type="file" name="avatar" class="form-control" id="avatar">
                </div>
            </div>

            <input type="submit" name="submit" class="btn btn-primary mt-3" value="Edit"/>
        </form>
    </div>
</body>

</html>