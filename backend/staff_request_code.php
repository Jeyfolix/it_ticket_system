<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

// ✅ Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../vendor/autoload.php';

try {
    // ✅ Step 1: Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $staffno = trim($input['staffno'] ?? '');

    if (empty($staffno)) {
        echo json_encode(['success' => false, 'message' => 'Staff number is required.']);
        exit;
    }

    // ✅ Step 2: Get staff details from DB
    $stmt = $pdo->prepare("SELECT email, fullname FROM staff WHERE staffno = ?");
    $stmt->execute([$staffno]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        echo json_encode(['success' => false, 'message' => 'Staff not found in the database.']);
        exit;
    }

    $staffEmail = $staff['email'];
    $staffName = $staff['fullname'];

    // ✅ Step 3: Generate reset code + expiry
    $code = rand(100000, 999999);
    $expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // ✅ Step 4: Save code & expiry in DB
    $update = $pdo->prepare("UPDATE staff SET reset_code = ?, reset_expires = ? WHERE staffno = ?");
    $update->execute([$code, $expires, $staffno]);

    // ✅ Step 5: Configure PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // ⚠️ Replace these two lines with your actual credentials
    $mail->Username = 'sikutwafolix22@gmail.com';  // your Gmail
    $mail->Password = 'alceeromjuirqmeh';      // your Gmail app password

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // ✅ Step 6: Email details
    $mail->setFrom('youraddress@gmail.com', 'IT Ticket System');
    $mail->addAddress($staffEmail, $staffName);
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Verification Code';
    $mail->Body = "
        <p>Hello <strong>{$staffName}</strong>,</p>
        <p>We received a request to reset your IT Ticket System password.</p>
        <p>Your verification code is:</p>
        <h2 style='color:#007bff;'>$code</h2>
        <p>This code will expire in <b>10 minutes</b>.</p>
        <p>If you didn’t request this, please ignore this message.</p>
        <hr>
        <p style='font-size:12px;color:#888;'>This is an automated message. Do not reply.</p>
    ";

    // ✅ Step 7: Send email
    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Verification code sent to your registered email.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Email sending failed: ' . $mail->ErrorInfo]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Unexpected error occurred.']);
}
