<?php
    include_once("config.php");

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(empty($username) || empty($password)){
            echo "All fields are required!";
        }
        else{
            $sql = "SELECT * FROM users WHERE username = :username";
            
            $selectUser = $conn->prepare($sql);
            $selectUser->bindParam(":username", $username);
            $selectUser->execute();

            $data = $selectUser->fetch();
            if($data == false){
                echo "User does not exist!";
            }
            else{
                if(password_verify($password, $data['password'])){
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['emri'] = $data['emri'];
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['is_admin'] = $data['is_admin'];

                    header("Location: dashboard.php");
                }
            }
        }
    }
?>