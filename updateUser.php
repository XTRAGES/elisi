<?php
    include_once("config.php");

    if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $emri = $_POST['emri'];
        $username = $_POST['username'];
        $email = $_POST['email'];

        $sql = "UPDATE users SET id = :id, emri = :emri, username = :username, email = :email
        WHERE id = :id";

        $updateUser = $conn->prepare($sql);
        $updateUser->bindParam(":id", $id);
        $updateUser->bindParam(":emri", $emri);
        $updateUser->bindParam(":username", $username);
        $updateUser->bindParam(":email", $email);
        $updateUser->execute();
        
        header("Location: dashboard.php");
    }

?>