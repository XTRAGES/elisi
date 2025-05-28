<?php
    include_once("config.php");

    if (empty($_SESSION['username']) || $_SESSION['is_admin'] != "true") {
        header("Location: login.html");
        exit();
    }

    $id = $_GET['id'];

    $sql = "SELECT * FROM bookings WHERE id = :id";
    $selectBooking = $conn->prepare($sql);
    $selectBooking->bindParam(":id", $id);
    $selectBooking->execute();
    $booking_data = $selectBooking->fetch();

    if (isset($_POST['submit'])) {
        $firstlastname = $_POST['firstlastname'];
        $email = $_POST['email'];
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $adults = $_POST['adults'];
        $kids = $_POST['kids'];
        $rooms = $_POST['rooms'];
        $specialrequests = $_POST['specialrequests'];

        // Update the booking in the database
        $updateSql = "UPDATE bookings SET firstlastname = :firstlastname, email = :email, checkin = :checkin, checkout = :checkout, adults = :adults, kids = :kids, rooms = :rooms, specialrequests = :specialrequests WHERE id = :id";
        $updateBooking = $conn->prepare($updateSql);
        $updateBooking->bindParam(":firstlastname", $firstlastname);
        $updateBooking->bindParam(":email", $email);
        $updateBooking->bindParam(":checkin", $checkin);
        $updateBooking->bindParam(":checkout", $checkout);
        $updateBooking->bindParam(":adults", $adults);
        $updateBooking->bindParam(":kids", $kids);
        $updateBooking->bindParam(":rooms", $rooms);
        $updateBooking->bindParam(":specialrequests", $specialrequests);
        $updateBooking->bindParam(":id", $id);
        $updateBooking->execute();

        // Redirect to the viewBookings page after updating
        header("Location: viewBookings.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <nav class="navbar bg-dark navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Welcome, <?php echo $_SESSION['username']; ?></a>
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa-solid fa-right-from-bracket ms-2"></i></a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php include_once("sidebar.php"); ?>

            <div class="col-10">
                <div class="container mt-5">
                    <h2>Edit Booking</h2>
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?php echo $booking_data['id']; ?>">

                        <input type="text" name="firstlastname" class="form-control mb-3"
                        value="<?php echo $booking_data['firstlastname']; ?>" placeholder="Full Name">

                        <input type="email" name="email" class="form-control mb-3"
                        value="<?php echo $booking_data['email']; ?>" placeholder="Email">

                        <!-- Prevent past date selection for check-in -->
                        <input type="datetime-local" name="checkin" class="form-control mb-3"
                        value="<?php echo date('Y-m-d\TH:i', strtotime($booking_data['checkin'])); ?>"
                        min="<?php echo date('Y-m-d\TH:i'); ?>">

                        <!-- Prevent past date selection for checkout -->
                        <input type="datetime-local" name="checkout" class="form-control mb-3"
                        value="<?php echo date('Y-m-d\TH:i', strtotime($booking_data['checkout'])); ?>"
                        min="<?php echo date('Y-m-d\TH:i'); ?>">

                        <input type="number" name="adults" class="form-control mb-3"
                        value="<?php echo $booking_data['adults']; ?>" placeholder="Adults">

                        <input type="number" name="kids" class="form-control mb-3"
                        value="<?php echo $booking_data['kids']; ?>" placeholder="Kids">

                        <input type="number" name="rooms" class="form-control mb-3"
                        value="<?php echo $booking_data['rooms']; ?>" placeholder="Rooms">

                        <textarea name="specialrequests" class="form-control mb-3" placeholder="Special Requests"><?php echo $booking_data['specialrequests']; ?></textarea>

                        <button type="submit" name="submit" class="btn btn-success">Update Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>