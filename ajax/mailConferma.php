<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.office365.com';                   // Set the SMTP server to send through (Outlook)
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'tua mail outlook';      // SMTP username (il tuo indirizzo email Outlook)
        $mail->Password   = 'tua password';                         // SMTP password (la tua password Outlook)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to for STARTTLS

        // Recipients
        $Indirizzoemail = $_POST["mail"];
        $mail->setFrom('ancora tua mail outlook', 'Confermatore');
        $mail->addAddress($Indirizzoemail, '');     // Add a recipient
        
        $cont = $_POST["contenuto"];
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Registrazione avvenuta con successo';
        $mail->Body = $cont;
        $mail->AltBody = $cont;
        //$mail->AltBody = 'Clicca questo link per confermare la tua registrazione: http://localhost/cinema/paginaConferma.php?token='.$token;

        $mail->send();
        
    } catch (Exception $e) {
        
    }