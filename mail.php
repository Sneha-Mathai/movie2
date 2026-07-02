<?php


// Include the PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Use the PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                        // Set mailer to use SMTP
    $mail->Host       = 'smtp-relay.brevo.com';                 // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                               // Enable SMTP authentication
    $mail->Username   = '7ae9ec001@smtp-brevo.com';           // SMTP username
    $mail->Password   = 'jtZWYVgO7mv29Qn3';                    // SMTP password
    $mail->SMTPSecure = 'tsl';                              // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                // TCP port to connect to

    // Recipients
    $mail->setFrom('snehamathai17@gmail.com', 'Mailer');
    $mail->addAddress('chackoz2024@gmail.com', 'Sneha'); // Add a recipient

    // Content
    $mail->isHTML(true);                                    // Set email format to HTML
    $mail->Subject = 'Haii';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
