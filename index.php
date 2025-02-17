<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    .container {
      text-align: center;
      background: white;
      padding: 3rem;
      border-radius: 24px;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
      transition: all 0.3s ease;
    }
    .container:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    h2 {
      color: #2c3e50;
      margin-bottom: 2rem;
      font-weight: 600;
    }
    #login-btn, #signup-btn {
      width: 100%;
      padding: 14px 24px;
      font-size: 1rem;
      border-radius: 12px;
      background-color: white;
      color: #4a4a4a;
      border: 2px solid #e0e0e0;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      transition: all 0.3s ease;
      font-weight: 500;
      margin-top: 1rem;
    }
    #login-btn:hover, #signup-btn:hover {
      background-color: #f9f9f9;
      border-color: #1a73e8;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .btn-img {
      width: 24px;
      height: 24px;
      object-fit: contain;
    }
    .or-divider {
      margin: 20px 0;
      font-size: 1rem;
      color: #7a7a7a;
      font-weight: 500;
    }
    .error-message, .success-message {
      padding: 12px;
      border-radius: 8px;
      margin-top: 15px;
      font-size: 0.9rem;
    }
    .error-message {
      color: #d32f2f;
      background-color: #ffebee;
      display: none;
    }
    .success-message {
      color: #2e7d32;
      background-color: #e8f5e9;
      display: none;
    }
    .continue-btn {
      display: inline-block;
      width: 100%;
      padding: 14px 24px;
      background-color: #1a73e8;
      color: white;
      text-decoration: none;
      border-radius: 12px;
      transition: background 0.3s ease;
      font-weight: 500;
      margin-top: 20px;
    }
    .continue-btn:hover {
      background-color: #165ab6;
    }
    #user-info {
      margin-top: 20px;
      color: #4a4a4a;
    }
    #dashboard-btn {
      display: none;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Sign in to Continue</h2>
  
  <button id="login-btn">
    <img class="btn-img" src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png" alt="Google Logo">
    Sign in with Google
  </button>

  <div class="or-divider">or</div>

  '<button id="signup-btn" onclick="window.location.href='/login';">
';">
    Make an Account Today
  </button>

  <div id="error-message" class="error-message"></div>
  <div id="success-message" class="success-message"></div>
  <div id="user-info"></div>

  <div id="dashboard-btn">
    <a href="https://yourwebsitelink/home.php?continue=true" class="continue-btn">Continue to Dashboard</a>
  </div>
</div>

<script>
  document.getElementById('login-btn').onclick = function() {
    google.accounts.id.initialize({
      client_id: 'keep you goolge ouath seccret key here ',
      callback: handleCredentialResponse
    });
    google.accounts.id.prompt();
  };

  function handleCredentialResponse(response) {
    const idToken = response.credential;
    const payload = JSON.parse(atob(idToken.split('.')[1]));
    
    // Check if user exists in database
    fetch('verify_user.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ 
        email: payload.email
      })
    })
    .then(response => response.json())
    .then(data => {
      const errorMessage = document.getElementById('error-message');
      const successMessage = document.getElementById('success-message');
      const userInfo = document.getElementById('user-info');
      
      if (data.exists) {
        // User exists, show success message and user info
        errorMessage.style.display = 'none';
        successMessage.style.display = 'block';
        successMessage.textContent = 'Login successful! Welcome back.';
        
        userInfo.style.display = 'block';
        userInfo.innerHTML = `
          <p><strong>Name:</strong> ${payload.name}</p>
          <p><strong>Email:</strong> ${payload.email}</p>
        `;
        
        document.getElementById('login-btn').style.display = 'none';

        // Show the Continue to Dashboard button
        const dashboardBtn = document.getElementById('dashboard-btn');
        dashboardBtn.style.display = 'block';

        // Store user data in session variables for home.php
        <?php
        $_SESSION['user_name'] = payload.name;
        $_SESSION['user_email'] = payload.email;
        ?>

        // Set a cookie to remember the last login email
        document.cookie = `last_login_email=${payload.email}; path=/; max-age=3600`;
      } else {
        // User doesn't exist, show error message
        successMessage.style.display = 'none';
        errorMessage.style.display = 'block';
        errorMessage.innerHTML = 'No account found with this email. Please create an account first.';
        userInfo.style.display = 'none';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('error-message').style.display = 'block';
      document.getElementById('error-message').textContent = 'An error occurred. Please try again later.';
    });
  }
</script>
</body>
</html>
