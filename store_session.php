<?php
// Start the session
session_start();

// Get the data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Store data in the session
$_SESSION['google_id'] = $data['google_id'];
$_SESSION['username'] = $data['username'];
$_SESSION['gmail'] = $data['gmail'];

// Return success response
echo json_encode(['success' => true]);
?>
