<?php 
    include_once('config.php');

    $id = $_GET['id'];

    $sql = "DELETE FROM rooms WHERE id = :id";

    $prep = $conn->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();

    header('Location: listRooms.php');
?>