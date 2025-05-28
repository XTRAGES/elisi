<?php
    include_once("config.php");

    if (isset($_POST['submit'])) {
        $firstlastname = $_POST['firstlastname'];
        $email = $_POST['email'];
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $adults = $_POST['adults'];
        $kids = $_POST['kids'];
        $rooms = $_POST['rooms'];
        $specialrequests = $_POST['specialrequests'];

        $sql = "INSERT INTO bookings 
        (firstlastname, email, checkin, checkout, adults, kids, rooms, specialrequests) 
        VALUES 
        (:firstlastname, :email, :checkin, :checkout, :adults, :kids, :rooms, :specialrequests)";

        $insertBooking = $conn->prepare($sql);
        $insertBooking->bindParam(":firstlastname", $firstlastname);
        $insertBooking->bindParam(":email", $email);
        $insertBooking->bindParam(":checkin", $checkin);
        $insertBooking->bindParam(":checkout", $checkout);
        $insertBooking->bindParam(":adults", $adults);
        $insertBooking->bindParam(":kids", $kids);
        $insertBooking->bindParam(":rooms", $rooms);
        $insertBooking->bindParam(":specialrequests", $specialrequests);
        $insertBooking->execute();

        header("Location: viewBookings.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <nav class="navbar bg-dark navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Welcome, <?php echo $_SESSION['username']; ?></a>
            <a href="logout.php" class="btn btn-danger">
                Logout <i class="fa-solid fa-right-from-bracket ms-2"></i>
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php include_once("sidebar.php"); ?>

            <div class="col-10">
                <div class="container mt-5">
                    <h2 class="mt-5">Add Booking</h2>
                    <form action="" method="POST">
                        <input type="text" name="firstlastname" class="form-control mb-3" placeholder="Full Name">
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email">
                        <input type="datetime-local" name="checkin" class="form-control mb-3" placeholder="Check-in" min="<?php echo date('Y-m-d\TH:i'); ?>">
                        <input type="datetime-local" name="checkout" class="form-control mb-3" placeholder="Check-out" min="<?php echo date('Y-m-d\TH:i'); ?>">
                        <input type="number" name="adults" class="form-control mb-3" placeholder="Adults">
                        <input type="number" name="kids" class="form-control mb-3" placeholder="Kids">
                        <input type="number" name="rooms" class="form-control mb-3" placeholder="Rooms">
                        <textarea name="specialrequests" class="form-control mb-3" placeholder="Special Requests"></textarea>
                        <button type="submit" name="submit" class="btn btn-success">Add Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>