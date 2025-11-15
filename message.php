<?php

header("Access-Control-Allow-Origin: https://www.enermillpower.com");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$fullname = $data['fullname'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$location = $data['location'] ?? '';
$message = $data['message'] ?? '';

if (empty($fullname) || empty($email) || empty($phone) || empty($location) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required'
    ]);
    exit();
}

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'cloud.webhostingbliss.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@enermillpower.com';
    $mail->Password = '@bWEEvN[LV@X[Wx7';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('contact@enermillpower.com', 'Enermill Power Limited');
    $mail->addAddress('osemensilas@gmail.com');
    $mail->Subject = 'Client From Enermill Power';
    $mail->Body = 
        "Name: $fullname\n" .
        "Phone: $phone\n" .
        "Email: $email\n" .
        "Location: $location\n" .
        "Message: $message";

    $mail->send();

    echo json_encode([
        'status' => 'success',
        'message' => 'Message sent successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => "Email failed: {$mail->ErrorInfo}"
    ]);
}
