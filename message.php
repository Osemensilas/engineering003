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
    'from' => 'Enermill <contact@enermillpower.com>',
    'to' => ['osemensilas@gmail.com'],
    'subject' => 'New Message from Enermill',
    'html' => '
                <div style="padding:20px;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#333;border-radius:10px;">
                    <h2 style="margin-bottom:10px;color:#0B3D91;">New Client Message</h2>
                    <p style="margin:0 0 15px;">You have received a new message from your website.</p>

                    <div style="background:#ffffff;padding:15px;border-radius:8px;border:1px solid #e5e5e5;">
                        <p style="margin:8px 0;"><strong>Name:</strong> ' . htmlspecialchars($fullname) . '</p>
                        <p style="margin:8px 0;"><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                        <p style="margin:8px 0;"><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>
                        <p style="margin:8px 0;"><strong>Location:</strong> ' . htmlspecialchars($location) . '</p>
                        <p style="margin:8px 0;"><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>
                    </div>

                    <p style="margin-top:20px;font-size:13px;color:#666;">
                        Sent from Enermill Power Website
                    </p>
                </div>
            ',
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
