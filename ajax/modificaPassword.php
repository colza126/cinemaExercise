<?php
// FILEPATH: /d:/xampp/htdocs/cinema/ajax/modificaPassword.php

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinema";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the token from POST request
$token = $_POST['token'];

// Find the tuple with the given token
$sql = "SELECT * FROM utente WHERE token_conferma = '$token'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Update the password field with the provided password (encrypted using MD5)
    $newPassword = md5($_POST['new_password']);
    $sql = "UPDATE utente SET password = '$newPassword' WHERE token_conferma = '$token'";
    if ($conn->query($sql) === TRUE) {
        echo "Password updated successfully";
    } else {
        echo "Error updating password: " . $conn->error;
    }
} else {
    echo "No tuple found with the given token";
}

$conn->close();