<?php
// FILEPATH: /D:/xampp/htdocs/cinema/ajax/registration.php

header('Content-Type: application/json');

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinema";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati dal form
    $mail = $_POST["mail"];
    $password = md5($_POST["password"]); // Encrypt password using MD5
    $token = bin2hex(random_bytes(16));
    // Esegui la query per inserire i dati nel database
    $sql = "INSERT INTO utente (mail, password,token_conferma) VALUES ('$mail', '$password','$token')";

    if ($conn->query($sql) === TRUE) {
        $response['status'] = 'success';
        $response['token'] = $token;
    } else {
        $response['status'] = 'fail';
    }
}
// stampa la risposta JSON
echo json_encode($response);

$conn->close();
