<?php
    include_once("config.php");

    if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $job = $_POST['job'];
        $image = $_POST['image'];

        $sql = "UPDATE staff SET name = :name, job = :job, image = :image
        WHERE id = :id";

        $insertStaff = $conn->prepare($sql);
        $insertStaff->bindParam(":id", $id);
        $insertStaff->bindParam(":name", $name);
        $insertStaff->bindParam(":job", $job);
        $insertStaff->bindParam(":image", $image);
        $insertStaff->execute();
        
        header("Location: listStaff.php");
    }

?>