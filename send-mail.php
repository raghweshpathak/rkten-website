<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method Not Allowed");
}

$name    = htmlspecialchars($_POST['name'] ?? '');
$email   = htmlspecialchars($_POST['email'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    exit("All fields are required");
}

$mail = new PHPMailer(true);

try {
    // SMTP config (AWS SES)
    $mail->isSMTP();
    $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'AKIATHGYVN4FOJQIB446';
    $mail->Password   = 'BDWKscUVwWGw4yVG4NaihEuedIyFtZpX3KI1Mu50q+cI';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Headers
    $mail->setFrom('info@rkten.com', 'RKTen Website');
    $mail->addReplyTo($email, $name);
    $mail->addAddress('shivanipathak0210@gmail.com');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission';
    $mail->Body    = "
        <h3>New Message from Website</h3>
        <p><b>Name:</b> {$name}</p>
        <p><b>Email:</b> {$email}</p>
        <p><b>Message:</b><br>{$message}</p>
    ";

    $mail->send();

    header("Location: thank-you.html");
    exit;

} catch (Exception $e) {
    error_log($mail->ErrorInfo);
    exit("Mail could not be sent");
}
