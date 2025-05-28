<?php

include_once("config.php");

if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $booking_id = $_GET['delete_id'];

    $sql = "DELETE FROM bookings WHERE id = :id";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':id', $booking_id, PDO::PARAM_INT);

    $stmt->execute();
}

header("Location: viewBookings.php");
exit();

?>