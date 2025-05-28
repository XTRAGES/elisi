<?php
include_once("config.php");

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $firstlastname = $_POST['firstlastname'];
    $email = $_POST['email'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $adults = $_POST['adults'];
    $kids = $_POST['kids'];
    $rooms = $_POST['rooms'];
    $specialrequests = $_POST['specialrequests'];

    $sql = "UPDATE bookings SET 
                firstlastname = :firstlastname, 
                email = :email, 
                checkin = :checkin, 
                checkout = :checkout, 
                adults = :adults, 
                kids = :kids, 
                rooms = :rooms, 
                specialrequests = :specialrequests
            WHERE id = :id";

    $updateBooking = $conn->prepare($sql);
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

    header("Location: dashboard.php");
    exit();
}
?>