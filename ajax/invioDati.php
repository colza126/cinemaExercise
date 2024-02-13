<?php
    // imposta l'intestazione per indicare che il contenuto è in formato JSON
    header('Content-Type: application/json');

    // parametri di connessione al database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "italia";

    // crea una nuova connessione al database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // verifica la connessione al database
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // verifica se il modulo è stato inviato
    $codice_regione = $_POST['codice_regione'];
    $sigla_provincia = $_POST['sigla_provincia'];
    $denominazione_comune = $_POST['denominazione_comune'];
    $usr = $_POST['username'];

    // verifica se l'utente esiste già nella tabella
    $sql_check = "SELECT * FROM `utente` WHERE `user` = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $usr);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // l'utente esiste, esegui l'UPDATE
        $sql = "UPDATE `utente` SET codice_regione = ?, sigla_provincia = ?, denominazione_comune = ? WHERE user = ?;";
    } else {
        // l'utente non esiste, esegui l'INSERT
        $sql = "INSERT INTO `utente` (codice_regione, sigla_provincia, denominazione_comune, user) VALUES (?, ?, ?, ?);";
    }

    // esegui l'operazione di UPDATE o INSERT
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $codice_regione, $sigla_provincia, $denominazione_comune, $usr);

    if ($stmt->execute()) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'not success';
    }

    echo json_encode($response);