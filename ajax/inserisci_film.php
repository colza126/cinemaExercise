<?php
// FILEPATH: /d:/xampp/htdocs/cinema/ajax/inserisci_film.php

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinema";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Recupera i dati inviati tramite AJAX
$titolo = $_POST['titolo'];
$genere = $_POST['genere'];
$anno = $_POST['anno'];
$bio = $_POST['bio'];
$foto = $_POST['foto'];




// Prepara la query di inserimento con prepared statement
$sql = "INSERT INTO film (nome, anno_produzione, genere, bio, percorso_locandina,ID) VALUES (?, ?, ?, ?, ?,NULL)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisss", $titolo, $anno,$genere, $bio, $foto);

// Esegui la query di inserimento
if ($stmt->execute()) {
    echo "Film inserito con successo";
} else {
    echo "Errore durante l'inserimento del film: " . $stmt->error;
}

// Chiudi la connessione al database
$stmt->close();
$conn->close();