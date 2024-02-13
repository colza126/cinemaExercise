<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cinema";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the token value from the GET request
    $token = $_GET['token'];
    
    // Prepare the query to check if the token exists in the token_history table
    $sql = "SELECT * FROM utente WHERE `token_conferma` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token exists in at least one row
        echo "Account attivato con successo";
        // Update the "utilizzato" field to true for the token
        $updateSql = "UPDATE utente SET account_confermato = 1 WHERE `token_conferma` = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $token);
        $updateStmt->execute();
    } else {
        // Token does not exist in any row
        echo "Token does not exist";
    }

    $conn->close();