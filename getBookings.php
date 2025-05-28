<?php
include_once("config.php");

$sql = "SELECT id, firstlastname, email, checkin, checkout, adults, kids, rooms, specialrequests FROM bookings";
$stmt = $conn->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$events = [];

foreach ($bookings as $booking) {
    $events[] = [
        'title' => $booking['firstlastname'], // Event title (guest name)
        'start' => $booking['checkin'], // Start date
        'end' => $booking['checkout'], // End date
        'email' => $booking['email'],
        'adults' => $booking['adults'],
        'kids' => $booking['kids'],
        'rooms' => $booking['rooms'],
        'specialrequests' => $booking['specialrequests'],
        'id' => $booking['id']
    ];
}

echo json_encode($events);
?>