<?php
session_start();
require_once 'connection.php';

// Redirect to the dashboard if already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our Gym Management System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html,
