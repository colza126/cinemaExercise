<?php

// imposta l'intestazione per indicare che il contenuto è in formato JSON
header('Content-Type: application/json');

// parametri di connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinema";

// crea una nuova connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// verifica la connessione al database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// query per verificare l'autenticazione dell'utente
$sql = "DELETE FROM film WHERE `ID` = ?";
// associa i parametri alla query

$stmt = $conn->prepare($sql);

// verifica la preparazione della query
if ($stmt === false) {
    die("Preparation failed: " . $conn->error);
}

// esegue la query
$stmt->bind_param("i", $_POST['id_film']);
$stmt->execute();

echo json_encode(array(
    'status' => 'success'
));