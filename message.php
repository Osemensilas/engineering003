<?php 

header("Access-Control-Allow-Origin: https://www.enermillpower.com");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

require __DIR__ . '/vendor/autoload.php';

$enermillApi = "re_ZFP1s3yA_4RkXqGX6mVAq7jRDUQMjJsYx";

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

$resend = Resend::client($enermillApi);

try {
    $resend->emails->send([
    'from' => 'Enermill <contact@enermill.com>',
    'to' => ['osemensilas@gmail.com'],
    'subject' => 'New Message from Enermill',
    'html' => '<strong>it works!</strong>',
    ]);

    echo json_encode([
        'status' => 'successful',
        'message' => "Message sent successfully"
    ]);
} catch (\Throwable $th) {
    echo json_encode([
        'status' => 'error',
        'message' => $th->getMessage()
    ]);
}
