<?php
    include_once("config.php");

    if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $bed = $_POST['bed'];
        $bath = $_POST['bath'];
        $wifi = $_POST['wifi'];

        $sql = "UPDATE rooms SET name = :name, description = :description,
        price = :price, bed = :bed, bath = :bath, wifi = :wifi
        WHERE id = :id";

        $insertRoom = $conn->prepare($sql);
        $insertRoom->bindParam(":id", $id);
        $insertRoom->bindParam(":name", $name);
        $insertRoom->bindParam(":description", $description);
        $insertRoom->bindParam(":price", $price);
        $insertRoom->bindParam(":bed", $bed);
        $insertRoom->bindParam(":bath", $bath);
        $insertRoom->bindParam(":wifi", $wifi);
        $insertRoom->execute();
        
        header("Location: listRooms.php");
    }

?>