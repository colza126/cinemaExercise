<?php
    // Avvia la sessione
    if (!isset($_SESSION)) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login and Registration Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Login and Registration Page</h1>
        <button id="showLoginForm" class="btn btn-primary">Login</button>
        <button id="showRegistrationForm" class="btn btn-secondary">Register</button>
        <button id="showRecoveryForm" class="btn btn-info">Password dimenticata</button>

        <form id="loginForm" method="POST" style="display: none;">
            <div class="mb-3">
                <label for="loginMail" class="form-label">E-mail:</label>
                <input type="text" id="loginMail" name="mail" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="loginPassword" class="form-label">Password:</label>
                <input type="password" id="loginPassword" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
            <p id="loginErrorMessage" class="mt-2"></p>
        </form>

        <form id="recoveryPw" method="POST" style="display: none;">
            <div class="mb-3">
                <label for="recoveryMail" class="form-label">E-mail:</label>
                <input type="text" id="recoveryMail" name="mail" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Password dimenticata</button>
            <p id="recoveryErrorMessage" class="mt-2"></p>
        </form>

        <form id="registrationForm" method="POST" style="display: none;">
            <div class="mb-3">
                <label for="registrationMail" class="form-label">E-mail:</label>
                <input type="text" id="registrationMail" name="mail" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="registrationPassword" class="form-label">Password:</label>
                <input type="password" id="registrationPassword" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Register</button>
            <p id="registrationErrorMessage" class="mt-2"></p>
        </form>
    </div>

    <script src="script/index.js"></script>
</body>

</html>
