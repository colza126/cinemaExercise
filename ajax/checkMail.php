<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cinema";
    header('Content-Type: application/json');

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $randomCode = bin2hex(random_bytes(2));
    

    $mail = $_POST["mail"];
    $sql = "SELECT * FROM utente WHERE `mail` = ?";
    $stmt = $conn->prepare($sql);
    
    // verifica la preparazione della query
    if ($stmt === false) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $mail);

    // Esegui la query di inserimento
    $stmt->execute();
    $result = $stmt->get_result();
    $response = array();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response["exists"] = "true"; // Corrected the key name to "exists"
        $response["token"] = $row["token_conferma"];
    } else {
        $response["exists"] = "false";
    }

    $stmt->close(); // Close the prepared statement
    $conn->close();
    echo json_encode($response);