<?php
    include_once("config.php");

    if(isset($_POST['submit'])){
        $emri = $_POST['emri'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        $sql = "INSERT INTO users
        (emri, username, email, password, confirm_password)
        VALUES (:emri, :username, :email, :password, :confirm_password)";

        $insertUser = $conn->prepare($sql);
        $insertUser->bindParam(":emri", $emri);
        $insertUser->bindParam(":username", $username);
        $insertUser->bindParam(":email", $email);
        $insertUser->bindParam(":password", $password);
        $insertUser->bindParam(":confirm_password", $confirm_password);
        $insertUser->execute();

        header("Location: listUsers.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <nav class="navbar bg-dark navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Welcome, <?php echo $_SESSION['username']; ?></a>
            <a href="logout.php" class="btn btn-danger justify-content-end d-flex">
                Logout  <i class="fa-solid fa-right-from-bracket ms-2 d-flex align-items-center"></i>
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php include_once("sidebar.php"); ?>

            <div class="col-10">
                <div class="container mt-5">
                    <div class="row">
                        <div class="col">
                            <h2 class="mt-5">Add User</h2>
                            <form action="" method="POST">
                                <input type="text" name="emri" class="form-control mb-3" placeholder="Name">
                                <input type="text" name="username" class="form-control mb-3" placeholder="Username">
                                <input type="text" name="email" class="form-control mb-3" placeholder="Email">
                                <input type="text" name="password" class="form-control mb-3" placeholder="Password">
                                <input type="text" name="confirm_password" class="form-control mb-3" placeholder="Confirm Password">
                                <button type="submit" name="submit" class="btn btn-success">Add User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>