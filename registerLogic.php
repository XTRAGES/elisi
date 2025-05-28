<?php
    include_once("config.php");

    if(isset($_POST['submit'])){
        $emri = $_POST['emri'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $tempPass = $_POST['password'];
        $confirmPass = $_POST['confirm_password'];
        $password = password_hash($tempPass, PASSWORD_DEFAULT);
        $is_admin_default = 0; // Set default to 0 (non-admin)

        if(empty($emri) || empty($username) || empty($email) || empty($tempPass) || empty($confirmPass)){
            header("Location: login.html?error=empty_fields&signup=true");
            exit();
        }
        else{
            if($tempPass != $confirmPass){
                header("Location: login.html?error=password_mismatch&signup=true");
                exit();
            }

            $checkSql = "SELECT id FROM users WHERE username = :username OR email = :email";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(":username", $username);
            $checkStmt->bindParam(":email", $email);
            $checkStmt->execute();

            if($checkStmt->rowCount() > 0){
                header("Location: login.html?error=user_exists&signup=true");
                exit();
            }

            $sql = "INSERT INTO users(emri, username, email, password, is_admin)
                    VALUES (:emri, :username, :email, :password, :is_admin)";

            $insertSQL = $conn->prepare($sql);

            $insertSQL->bindParam(":emri", $emri);
            $insertSQL->bindParam(":username", $username);
            $insertSQL->bindParam(":email", $email);
            $insertSQL->bindParam(":password", $password);
            $insertSQL->bindParam(":is_admin", $is_admin_default, PDO::PARAM_INT);

            if($insertSQL->execute()){
                header("Location: login.html?registration_success=true");
                exit();
            } else {
                // Handle database errors
                $errorInfo = $insertSQL->errorInfo();
                error_log("Registration Error: " . $errorInfo[2]); // Log the error
                header("Location: login.html?error=registration_failed&signup=true");
                exit();
            }
        }
    }
    ?>