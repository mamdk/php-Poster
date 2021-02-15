<?php

session_start();
include '../connection.php';
$err = [];

if (isset($_POST["submit"]) && !empty($_POST["submit"])) {
    if (strtolower($_SESSION["captcha"]) == strtolower($_POST["captcha"])) {
        $email = $_POST["email"];
        $pass = $_POST["password"];

        try {
            $sql = "SELECT * FROM admin WHERE email=:email";
            $q = $conn->prepare($sql);
            $q->execute([":email" => $email]);

            $user = $q->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        if (!isset($user) || empty($user)) {
            array_push($err, "Email is not Existe!");
        } else {
            $password = sha1($pass . $user["salt"]);

            if ($password != $user["password"]) {
                array_push($err, "password is not true!");
            }
        }


        if (empty($err)) {
            $_SESSION["admin_id"] = $user["id"];

            header("location: ./index.php");
        }
    } else {
        array_push($err, "captcha in wrong!!!!!!!");
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
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Sign in Admin</title>
</head>

<body>
    <div class="container">
        <h1 class="display-5">Sign in Admin</h1>
        <?php
        if (isset($err) || !empty($err)) {
            foreach ($err as $e) {
        ?>
                <div class="alert alert-danger mt-2" role="alert">
                    <?php echo $e ?>
                </div>
        <?php
            }
        }
        ?>
        <form action="./signin.php" method="post">

            <div class="form-group mt-3">
                <label for="email">Email</label>
                <input class="form-control" type="email" name="email" id="email">
            </div>

            <div class="form-group mt-3">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" id="password">
            </div>

            <div class="d-flex flex-column justify-content-between align-items-start mt-3">
                <div>
                    <img style="border-radius: 2px;" id="imgCaptcha" src="../captcha/captcha.php" alt="captcha">
                    <span id="btnCaptcha" class="btn btn-light"><i class="fa fa-exchange"></i></span>
                </div>
                <input class="mt-2" type="text" name="captcha">
            </div>

            <input class="btn btn-primary mt-3" type="submit" name="submit" value="Sign in">
        </form>
    </div>
</body>

</html>

<script src="../js/reCaptcha.js"></script>