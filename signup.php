<?php
session_start();
include './connection.php';
include './helper/help.php';
$err = [];

if (isset($_POST['submit']) || !empty($_POST['submit'])) {

    $salt = getSalt();
    $pass = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    try {
        $sql = "SELECT * FROM users WHERE email=:email";
        $q = $conn->prepare($sql);
        $q->execute([":email" => $email]);
        $res = $q->fetch(PDO::FETCH_ASSOC);

        if (!isset($pass) || empty($pass) || strlen($pass) < 8) {
            array_push($err, "password most be bigger of 8 charcters");
        }

        if (isset($res) && !empty($res)) {
            array_push($err, "Email is token!!!");
        }

        if (empty($err)) {
            $sql = "INSERT INTO users (name,email,password,salt) VALUES (:name,:email,:password,:salt)";
            $q = $conn->prepare($sql);
            $q->execute([":name" => $name, ":email" => $email, ":password" => sha1($pass . $salt), ":salt" => $salt]);

            $sql = "SELECT id FROM users WHERE email='$email'";
            $q = $conn->prepare($sql);
            $q->execute();
            $id = $q->fetch(PDO::FETCH_ASSOC)["id"];
            $_SESSION["user_id"] = $id;
            header("location: index.php");
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
    <title>sign up</title>
</head>

<body>
    <?php include './header.php'; ?>

    <div class="container">
        <h1 class="display-4" style="margin-top: 65px;">sign up</h1>


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
        <form method="POST" action="signup.php" class="mt-5">
            <div class="form-group">
                <label for="exampleInputname">Name</label>
                <input type="text" name="name" class="form-control" id="exampleInputname" placeholder="Name">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <input type="submit" name="submit" class="btn btn-primary mt-3" value="Sign Up" />
        </form>
    </div>


</body>

</html>