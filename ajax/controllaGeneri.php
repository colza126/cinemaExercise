<?php
    header('Content-Type: application/json');
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cinema";


    $conn = new mysqli($servername, $username, $password, $dbname);

    
    // verifica la connessione al database
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM elenco_generi";

    $stmt = $conn->prepare($sql);

    // verifica la preparazione della query
    if ($stmt === false) {
        die("Preparation failed: " . $conn->error);
    }

    // esegue la query
    $stmt->execute();

    // ottieni il risultato della query
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $response["generi"] = array();
        while($row = $result->fetch_assoc()){
            $response['status'] = 'true';
            
            $response["generi"][] = $row["genere"];
        }
    }
    else{
        $response['status'] = 'fail';
    }
    echo json_encode($response);