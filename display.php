<?php
// Path to the file where user data is stored
$dataFile = 'user_data.txt';

// Handle API request for user data
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['api'])) {
    // Retrieve the stored data
    $users = file_exists($dataFile) ? unserialize(file_get_contents($dataFile)) : [];
    
    // Output user data as JSON and exit
    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}

// The rest of the display.php logic for the form and table display
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        // Get the user index to delete
        $userIndex = intval($_POST['user_index']);
        
        // Retrieve existing data, if any
        $existingData = file_exists($dataFile) ? unserialize(file_get_contents($dataFile)) : [];

        // Remove the user from the data
        if (isset($existingData[$userIndex])) {
            unset($existingData[$userIndex]);
            $existingData = array_values($existingData);
            file_put_contents($dataFile, serialize($existingData));
            echo "<p style='color: red; text-align: center;'>User account deleted successfully.</p>";
        }
    }

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($username) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $existingData = file_exists($dataFile) ? unserialize(file_get_contents($dataFile)) : [];
        $emailExists = false;

        foreach ($existingData as $user) {
            if (strcasecmp($user['email'], $email) === 0) {
                $emailExists = true;
                break;
            }
        }

        if ($emailExists) {
            echo "<p style='color: red; text-align: center;'>An account with this Gmail ID already exists.</p>";
        } else {
            $userData = [
                'username' => htmlspecialchars($username),
                'email' => htmlspecialchars($email)
            ];
            $existingData[] = $userData;
            file_put_contents($dataFile, serialize($existingData));
            echo "<p style='color: green; text-align: center;'>Account successfully created! Welcome, $username.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Please provide a valid username and email address.</p>";
    }
}

$users = file_exists($dataFile) ? unserialize(file_get_contents($dataFile)) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registered Users</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: #f0f2f5;
      margin: 0;
      padding: 20px;
    }
    table {
      width: 80%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
      padding: 8px;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #ddd;
    }
    p {
      margin: 10px 0;
    }
  </style>
</head>
<body>
<h2>Registered Users</h2>

<?php if (!empty($users)): ?>
  <table>
    <tr>
      <th>#</th>
      <th>Username</th>
      <th>Email</th>
      <th>Action</th>
    </tr>
    <?php foreach ($users as $index => $user): ?>
      <tr>
        <td><?php echo $index + 1; ?></td>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="user_index" value="<?php echo $index; ?>">
            <button type="submit" name="delete_user" style="color: red; background: none; border: none; cursor: pointer;">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p>No users found.</p>
<?php endif; ?>

</body>
</html>
