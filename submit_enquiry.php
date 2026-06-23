<?php
// ── ENQUIRY SUBMISSION HANDLER ──
// Bush Minds Tours & Travel
// Receives POST from contact.html and saves to MySQL

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

require_once 'db.php';

// ── Sanitize & collect inputs ──
function clean($val) {
    return htmlspecialchars(strip_tags(trim($val)));
}

$fname        = clean($_POST['fname']        ?? '');
$lname        = clean($_POST['lname']        ?? '');
$email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone        = clean($_POST['phone']        ?? '');
$destination  = clean($_POST['destination']  ?? '');
$travelers    = intval($_POST['travelers']   ?? 0);
$travel_date  = clean($_POST['travel-date']  ?? '');
$message      = clean($_POST['message']      ?? '');

// ── Basic validation ──
if (empty($fname) || empty($lname) || empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

// ── Insert into database ──
try {
    $sql = "INSERT INTO enquiries 
            (fname, lname, email, phone, destination, travelers, travel_date, message, submitted_at)
            VALUES 
            (:fname, :lname, :email, :phone, :destination, :travelers, :travel_date, :message, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':fname'       => $fname,
        ':lname'       => $lname,
        ':email'       => $email,
        ':phone'       => $phone,
        ':destination' => $destination,
        ':travelers'   => $travelers,
        ':travel_date' => $travel_date ?: null,
        ':message'     => $message,
    ]);

    echo json_encode([
        'status'  => 'success',
        'message' => 'Enquiry received! We will contact you within 24 hours.'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Could not save your enquiry. Please try again.'
    ]);
}
?>
