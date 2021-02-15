<?php
session_start();
include './connection.php';
$err = [];

if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
    header("location: signin.php");
}

try {
    $sql = "SELECT name FROM users WHERE id=:id";
    $q = $conn->prepare($sql);
    $q->execute([":id" => $_SESSION["user_id"]]);
    $user = $q->fetch(PDO::FETCH_ASSOC)["name"];
} catch (PDOException $e) {
    echo $e->getMessage();
}


if (isset($_POST["submit"]) && !empty($_POST["submit"])) {

    $let = $_POST["let"];

    if ($let != $user) {
        array_push($err, "value is wrong!!!!!");
    }

    if (!isset($err) || empty($err)) {
        try {
            $sql = "DELETE FROM users WHERE id=:id";
            $q = $conn->prepare($sql);
            $q->execute([":id" => $_SESSION["user_id"]]);
            unset($_SESSION["user_id"]);

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
    <link rel="stylesheet" href="./css/style.css">
    <title>Delete Account</title>
</head>

<body>
    <?php include './header.php'; ?>
    <div class="container">
        <h1 class="display-4 text-danger" style="margin-top: 65px;">Delete Account</h1>
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
        <div class="d-flex justify-content-center align-items-center">
            <form action="./deleteProfile.php" method="POST">
                <div class="form-group">
                    <label class="display-5 text-center" for="let">Please enter the word <h3 class="display-4">"<?php echo $user ?>"</h3 class="display-4"></label>
                    <input class="form-control" type="text" name="let" id="let">
                </div>

                <input class="btn btn-danger mt-3" type="submit" name="submit" value="Delete">
            </form>
        </div>
    </div>
</body>

</html>