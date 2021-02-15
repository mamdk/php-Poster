<?php
session_start();
include './connection.php';
include './helper/help.php';
$err = [];

if (isset($_POST['submit']) || !empty($_POST['submit'])) {
    $pass = $_POST['password'];
    $email = $_POST['email'];

    try {
        $sql = "SELECT * FROM users WHERE email=:email";
        $q = $conn->prepare($sql);
        $q->execute([":email" => $email]);
        $res = $q->fetch(PDO::FETCH_ASSOC);

        if ($res && isset($res) && !empty($res)) {
            $h_pass = sha1($pass . $res["salt"]);

            if ($h_pass != $res["password"]) {
                array_push($err, "password is not true");
            }

            if (empty($err)) {
                $_SESSION["user_id"] = $res["id"];
                header("location: index.php");
            }
        } else {
            array_push($err, "user with this email not found!!");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
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
    <title>sign in</title>
</head>

<body>
    <?php include './header.php'; ?>

    <div class="container">
        <h1 class="display-4" style="margin-top: 65px;">sign in</h1>

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
        <form method="POST" action="signin.php" class="mt-5">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <input type="submit" name="submit" class="btn btn-primary mt-3" value="Sign In"/>
        </form>
    </div>


</body>

</html>