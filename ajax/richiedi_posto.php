<?php
// imposta l'intestazione per indicare che il contenuto è in formato JSON
header('Content-Type: application/json');

// inizia la sessione se non è già in corso
if (!isset($_SESSION)) {
    session_start();
}

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

// array per la risposta JSON
$response = array();
$campo = "";

// se non è impostato il parametro 'regione' nella richiesta POST
if (!isset($_POST['regione'])) {
    // esegue la query per ottenere tutte le regioni
    $sql = "SELECT * FROM `gi_regioni`";
    $campo = "denominazione_regione";
    $stmt = $conn->prepare($sql);

    $stmt->execute();

    // ottiene il risultato della query
    $result = $stmt->get_result();

    // verifica se ci sono righe nel risultato
    if ($result->num_rows > 0) {
        $response['status'] = 'success';
        $response['data'] = array();

        // costruisce l'array di dati con le informazioni delle regioni
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = array(
                'nome' => $row['denominazione_regione'],
                'id' => $row['codice_regione']
            );
        }
    } else {
        $response['status'] = 'fail';
    }
} else {
    // se è impostato il parametro 'regione' nella richiesta POST
    if (!isset($_POST['provincia'])) {
        // esegue la query per ottenere le province di una specifica regione
        $sql = "SELECT * FROM `gi_province` WHERE `codice_regione` = ?";
        $campo = "denominazione_provincia";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_POST['regione']);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['status'] = 'success';
            $response['data'] = array();

            // costruisce l'array di dati con le informazioni delle province
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = array(
                    'nome' => $row['denominazione_provincia'],
                    'id' => $row['sigla_provincia']
                );
            }
        } else {
            $response['status'] = 'fail';
        }
    } else {
        // se è impostato il parametro 'provincia' nella richiesta POST
        if (!isset($_POST['comune'])) {
            // esegue la query per ottenere i comuni di una specifica provincia
            $sql = "SELECT * FROM `gi_comuni` WHERE `sigla_provincia` = ?";
            $campo = "denominazione_ita";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_POST['provincia']);

            // esegue la query
            $stmt->execute();

            // ottiene il risultato della query
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['status'] = 'success';
                $response['data'] = array();

                // costruisce l'array di dati con le informazioni dei comuni
                while ($row = $result->fetch_assoc()) {
                    $response['data'][] = array(
                        'nome' => $row['denominazione_ita'],
                        'id' => $row['denominazione_ita']
                    );
                }
            } else {
                $response['status'] = 'fail';
            }
        }
    }
}

// stampa la risposta JSON
echo json_encode($response);