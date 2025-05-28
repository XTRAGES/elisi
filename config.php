<?php
    session_start();
    
    $host = 'localhost';
    $db = "elisi";
    $user = 'root';
    $password = '';

    try{
        $conn = new PDO("mysql:host=$host; dbname=$db", 
        $user, $password);
    } catch(Exception $e){
        echo $e->getMessage();
    }
?>