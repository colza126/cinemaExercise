<?php
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

    // ottieni la mail passata tramite POST
    $email = $_POST['email'];

    // genera un nuovo token casuale
    $token = bin2hex(random_bytes(16));

    // aggiorna il token nel database per la mail specificata
    $sql = "UPDATE users SET token = '$token' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
        echo "Token aggiornato con successo";
    } else {
        echo "Errore durante l'aggiornamento del token: " . $conn->error;
    }

    $conn->close();
    