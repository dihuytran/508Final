<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "cmsc508.com";
$username = "24SP_dominguezjs";      // Replace with your 24SP_user
$password = "V01057069";    // Replace with your V#
$database = "24SP_dominguezjs_hr";  // Replace with your 24SP_user_hr database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>