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
    $mail->Host = 'cloud.webhostingbliss.com'; // Change this to your cPanel SMTP host
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@enermillpower.com'; // Your email address
    $mail->Password = '@bWEEvN[LV@X[Wx7'; // Use App Password if 2FA is enabled
    $mail->SMTPSecure = 'ssl'; // Use 'ssl' for port 465, 'tls' for port 587
    $mail->Port = 465; // 465 for SSL, 587 for TLS

    // Email Headers
    $mail->setFrom('contact@enermillpower.com', 'Enermill Power Limited'); // Sender
    $mail->addAddress('osemensilas@gmail.com'); // Recipient ndianaisang@gmail.com
    $mail->Subject = 'Client From Enermill Power';
    $mail->Body = 
        "Name: $fullname\n" .
        "Phone: $phone\n" .
        "Email: $email\n" .
        "Location: $location\n" .
        "Message: $message";

    // Send Mail
    if ($mail->send()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Message sent successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Message not sent. Check connection'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => "Email failed: {$mail->ErrorInfo}"
    ]);
}