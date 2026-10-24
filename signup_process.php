<?php
// delete_user.php
// Place this file in the same folder where your manage_users.php calls it from,
// or adjust the fetch() URL in manage_users.php to point here.

session_start();

// Make path robust relative to this file
require_once __DIR__ . '/../conn.php'; // adjust if conn.php is elsewhere

header('Content-Type: application/json; charset=utf-8');

// Optional: only allow admins (keep for production). If debugging temporarily disable this check.
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(403);
    error_log('delete_user: admin session missing. Session keys: ' . json_encode(array_keys($_SESSION)));
    echo json_encode(['success' => false, 'message' => 'Forbidden - admin only']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed, POST required']);
    exit;
}

// Accept either application/x-www-form-urlencoded / form-data or JSON body
$id = 0;
if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
} else {
    // support JSON body
    $raw = trim(file_get_contents('php://input'));
    if ($raw !== '') {
        $json = json_decode($raw, true);
        if (isset($json['id'])) {
            $id = (int) $json['id'];
        }
    }
}

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid user id']);
    exit;
}

// Prepared delete
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
if (!$stmt) {
    error_log('delete_user: prepare failed: ' . $conn->error);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (prepare)']);
    exit;
}

$stmt->bind_param('i', $id);
$ok = $stmt->execute();

if ($ok === false) {
    error_log('delete_user: execute failed: ' . $stmt->error);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error (execute)']);
    $stmt->close();
    exit;
}

$affected = $stmt->affected_rows;
$stmt->close();

if ($affected > 0) {
    echo json_encode(['success' => true, 'message' => 'Deleted', 'id' => $id]);
} else {
    // nothing deleted — maybe user doesn't exist or a FK prevented deletion
    error_log("delete_user: nothing deleted for id={$id} (affected_rows={$affected})");
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found or already deleted']);
}
?>