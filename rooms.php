<?php
    include_once("config.php");

    if(empty($_SESSION['username']) || $_SESSION['is_admin'] != "true"){
        header("Location: login.html");
    }

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $bed = $_POST['bed'];
        $bath = $_POST['bath'];
        $wifi = $_POST['wifi'];
        $image = $_POST['image'];

        $sql = "INSERT INTO rooms
        (name, description, price, bed, bath, wifi, image)
        VALUES (:name, :description, :price, :bed, :bath, :wifi, :image)";

        $insertRoom = $conn->prepare($sql);
        $insertRoom->bindParam(":name", $name);
        $insertRoom->bindParam(":description", $description);
        $insertRoom->bindParam(":price", $price);
        $insertRoom->bindParam(":bed", $bed);
        $insertRoom->bindParam(":bath", $bath);
        $insertRoom->bindParam(":wifi", $wifi);
        $insertRoom->bindParam(":image", $image);
        $insertRoom->execute();

        header("Location: listRooms.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
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
                            <h2 class="mt-5">Add Room</h2>
                            <form action="" method="POST">
                                <input type="text" name="name" class="form-control mb-3" placeholder="Name">
                                <input type="text" name="description" class="form-control mb-3" placeholder="Description">
                                <input type="text" name="price" class="form-control mb-3" placeholder="Price">
                                <input type="text" name="bed" class="form-control mb-3" placeholder="Bed">
                                <input type="text" name="bath" class="form-control mb-3" placeholder="Bath">
                                <input type="text" name="wifi" class="form-control mb-3" placeholder="Wifi">
                                <input type="file" name="image" class="form-control mb-3">
                                <button type="submit" name="submit" class="btn btn-success">Add Room</button>
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