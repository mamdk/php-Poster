<?php
include '../connection.php';


try {
    $sql = "SELECT * FROM users";
    $q = $conn->prepare($sql);
    $q->execute();
    $users = $q->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Users</title>
</head>

<body>
    <?php include './header.php'; ?>

    <section>
        <div class="container">
            <h1 class="display-4" style="margin-top: 65px;">Users</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">ToPost</th>
                        <th scope="col">Change toPost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($users as $user) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $user["id"]; ?></th>
                            <td><?php echo $user["name"]; ?></td>
                            <td><?php echo $user["email"]; ?></td>
                            <td><span class="bg-<?php echo boolval($user["toPost"]) ? "success": "danger"; ?> text-light p-1" style="border-radius: 5px;"><?php echo boolval($user["toPost"]) ? "True": "False"; ?></span></td>
                            <?php
                            if (!$user["toPost"]) {
                            ?>
                                <td>to <a class="btn btn-link text-success" href="./toPost.php?user_id=<?php echo $user["id"]; ?>&toPost=1">True</a></td>
                            <?php
                            } else {
                            ?>
                                <td>to <a class="btn btn-link text-danger" href="./toPost.php?user_id=<?php echo $user["id"]; ?>&toPost=0">False</a></td>
                            <?php
                            }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

</body>

</html>