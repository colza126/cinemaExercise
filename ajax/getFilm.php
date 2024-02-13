<?php
    // imposta l'intestazione per indicare che il contenuto è in formato JSON
    header('Content-Type: application/json');
    $response = array();

    if(!isset($_SESSION)){
        session_start();
        $response['auth'] = $_SESSION['auth'];
    }

    // parametri di connessione al database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cinema";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if(isset($_POST["id_film"])){
        
    // verifica la connessione al database
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_POST["id_film"];

    // query per ottenere l'elenco dei film
    $sql = "SELECT * FROM film WHERE `ID` = ?";
    
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);
    // verifica la preparazione della query
    if ($stmt === false) {
        die("Preparation failed: " . $conn->error);
    }

    // esegue la query
    $stmt->execute();

    // ottieni il risultato della query
    $result = $stmt->get_result();

    // verifica se ci sono righe nel risultato
    if ($result->num_rows > 0) {
        // Inizializza l'array film nella risposta
        $response["film"] = array();

        // Itera attraverso le righe del risultato e aggiungi ciascuna all'array di risposta
        while ($row = $result->fetch_assoc()) {
            $response['status'] = 'true';
            $filmData = array(
                "nome" => $row["nome"],
                "anno_produzione" => $row["anno_produzione"],
                "genere" => $row["genere"],
                "bio" => $row["bio"],
                "foto" => $row["percorso_locandina"]
            );

            // Aggiungi i dati del film all'array film nella risposta
            $response["film"] = $filmData;
        }
    } else {
        $response['status'] = 'fail';
    }

    // chiudi lo statement e la connessione al database
    } else {
        
    // verifica la connessione al database
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // query per ottenere l'elenco dei film
    $sql = "SELECT * FROM film";
    $stmt = $conn->prepare($sql);

    // verifica la preparazione della query
    if ($stmt === false) {
        die("Preparation failed: " . $conn->error);
    }

    // esegue la query
    $stmt->execute();

    // ottieni il risultato della query
    $result = $stmt->get_result();

    // verifica se ci sono righe nel risultato
    if ($result->num_rows > 0) {
        // Inizializza l'array film nella risposta
        $response["film"] = array();

        // Itera attraverso le righe del risultato e aggiungi ciascuna all'array di risposta
        while ($row = $result->fetch_assoc()) {
            $filmData = array(
                "nome" => $row["nome"],
                "anno_produzione" => $row["anno_produzione"],
                "genere" => $row["genere"],
                "bio" => $row["bio"],
                "ID" => $row["ID"]
            );

            // Aggiungi i dati del film all'array film nella risposta
            $response["film"][] = $filmData;
        }
    } else {
        $response['status'] = 'fail';
    }

    // chiudi lo statement e la connessione al database
    }
    

    $stmt->close();
    $conn->close();

    // stampa la risposta JSON
    echo json_encode($response);