<?php
session_start();  // Start the session

header('Content-Type: application/json');

// Get JSON data from the request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Path to the user data file
$dataFile = 'user_data.txt';

// Check if email exists in stored data
$userExists = false;
$userName = '';  // To store the user's name if found
if (file_exists($dataFile)) {
    $users = unserialize(file_get_contents($dataFile));
    foreach ($users as $user) {
        if (strcasecmp($user['email'], $data['email']) === 0) {
            $userExists = true;
            $userName = $user['name'];  // Get the name of the found user
            break;
        }
    }
}

// If the user exists, set the asession variables
if ($userExists) {
    $_SESSION['user_email'] = $data['email'];
    $_SESSION['user_name'] = $userName;
}

// Send response
echo json_encode([
    'exists' => $userExists,
    'message' => $userExists ? 'User found' : 'User not found'
]);
?>
