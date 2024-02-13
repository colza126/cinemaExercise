<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    $response['admin'] = true;
} else {
    $response['admin'] = false;
}

echo json_encode($response);