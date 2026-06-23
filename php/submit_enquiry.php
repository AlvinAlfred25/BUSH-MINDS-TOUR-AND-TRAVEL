<?php
// ============================================================
//  BUSH MINDS — CONTACT FORM HANDLER
//  Receives form data via POST, saves to database
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// ── Collect and sanitize form data ──
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$first_name  = clean($_POST['fname'] ?? '');
$last_name   = clean($_POST['lname'] ?? '');
$email       = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone       = clean($_POST['phone'] ?? '');
$destination = clean($_POST['destination'] ?? '');
$travelers   = intval($_POST['travelers'] ?? 1);
$travel_date = $_POST['travel-date'] ?? '';
$message     = clean($_POST['message'] ?? '');

// ── Validate required fields ──
if (empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Handle empty travel date
$travel_date_val = !empty($travel_date) ? $travel_date : null;

// ── Save to database ──
$conn = getConnection();

$stmt = $conn->prepare(
    "INSERT INTO enquiries 
     (first_name, last_name, email, phone, destination, travelers, travel_date, message)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    'sssssiiss',
    $first_name,
    $last_name,
    $email,
    $phone,
    $destination,
    $travelers,
    $travel_date_val,
    $message
);

// Fix: bind_param for nullable date
$stmt->close();

// Redo with correct types (date is string or null)
$stmt = $conn->prepare(
    "INSERT INTO enquiries 
     (first_name, last_name, email, phone, destination, travelers, travel_date, message)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
    'sssssiss',
    $first_name,
    $last_name,
    $email,
    $phone,
    $destination,
    $travelers,
    $travel_date_val,
    $message
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! We received your enquiry and will contact you within 24 hours.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Something went wrong. Please try again or contact us via WhatsApp.'
    ]);
}

$stmt->close();
$conn->close();
?>
