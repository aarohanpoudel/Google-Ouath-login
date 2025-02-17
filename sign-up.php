<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enhanced Sign Up with Google OAuth</title>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      padding: 20px;
    }

    .container {
      text-align: center;
      background: white;
      padding: 2.5rem;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
      width: 100%;
      max-width: 450px;
      animation: fadeIn 0.8s ease;
      position: relative;
    }

    h2 {
      color: #1a73e8;
      margin-bottom: 1.5rem;
      font-size: 2rem;
    }

    .notice {
      color: #666;
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    #login-btn, #set-username-btn {
      width: 100%;
      padding: 12px 24px;
      font-size: 1.1rem;
      border-radius: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      transition: all 0.3s ease;
    }

    #login-btn {
      background-color: white;
      color: #5f6368;
      border: 2px solid #dadce0;
      margin-top: 20px;
    }

    #login-btn:hover {
      background-color: #f8f9fa;
      border-color: #d2e3fc;
    }

    #set-username-btn {
      background: transparent;
      color: #4285f4;
      border: 2px solid #4285f4;
      font-weight: 600;
      margin-top: 20px;
      animation: pulse 2s infinite;
    }

    .error-popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      z-index: 1000;
      display: none;
    }

    .error-popup button {
      margin-top: 15px;
      padding: 8px 16px;
      background: #1a73e8;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-img {
      width: 24px;
      height: 24px;
      object-fit: contain;
    }

    .phone-group {
      display: flex;
      gap: 10px;
    }

    #country-select {
      width: 40%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    #phone {
      width: 60%;
    }

    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(66, 133, 244, 0.4); }
      70% { box-shadow: 0 0 0 10px rgba(66, 133, 244, 0); }
      100% { box-shadow: 0 0 0 0 rgba(66, 133, 244, 0); }
    }

    .form-group {
      display: none;
      margin-top: 25px;
      animation: slideIn 0.5s ease forwards;
    }

    .input-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .input-group label {
      display: block;
      margin-bottom: 8px;
      color: #5f6368;
      font-size: 0.9rem;
    }

    .input-field {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    .input-field:focus {
      border-color: #4285f4;
      outline: none;
    }

    .error-message {
      color: #d93025;
      font-size: 0.85rem;
      margin-top: 5px;
      display: none;
    }

    .welcome {
      font-size: 1.5rem;
      color: #1a73e8;
      margin-bottom: 1rem;
    }

    #g_id_onload {
      position: fixed !important;
      top: 50% !important;
      left: 50% !important;
      transform: translate(-50%, -50%) !important;
      z-index: 1000 !important;
    }

    #proceed-btn {
      width: 100%;
      padding: 12px;
      background-color: #34a853;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      margin-top: 20px;
      display: none;
      transition: background-color 0.3s ease;
    }

    #proceed-btn:hover {
      background-color: #2d9144;
    }

    .success-message {
      display: none;
      text-align: center;
      padding: 20px;
      background: #e8f5e9;
      border-radius: 10px;
      margin: 20px 0;
    }

    /* New styles for updated buttons */
    #confirm-btn {
      width: 100%;
      padding: 15px 30px;
      font-size: 1.2rem;
      background: transparent;
      color: #4285f4;
      border: 3px solid #4285f4;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 30px;
    }

    #confirm-btn:hover {
      background: rgba(66, 133, 244, 0.1);
    }

    #goto-login {
      width: 100%;
      padding: 18px 36px;
      font-size: 1.3rem;
      background: transparent;
      color: #4285f4;
      border: 3px solid #4285f4;
      border-radius: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 25px;
    }

    #goto-login:hover {
      background: rgba(66, 133, 244, 0.1);
      transform: scale(1.02);
    }

    @media (max-width: 480px) {
      .container {
        padding: 1.5rem;
      }

      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Sign Up with Google</h2>
  <p class="notice">Please sign in with Google to continue. For the best experience, we recommend using Chrome or a modern browser.</p>
  
  <div class="error-popup" id="error-popup">
    <h3>Account Already Exists</h3>
    <p>You already have an account. Please go to the login page.</p>
    <button onclick="window.location.href='https:/index.php'">Go to Login</button>
  </div>

  <button id="login-btn">
    <img class="btn-img" src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png" alt="Google Logo">
    Sign in with Google
  </button>
  
  <button id="set-username-btn" style="display: none;">
    <img class="btn-img" src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png" alt="Google Logo">
    Complete Your Profile
  </button>
  
  <div class="form-group" id="profile-form">
    <div class="input-group">
      <label for="firstName">First Name</label>
      <input type="text" class="input-field" id="firstName">
    </div>

    <div class="input-group">
      <label for="lastName">Last Name</label>
      <input type="text" class="input-field" id="lastName">
    </div>

    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" class="input-field" id="username" placeholder="Choose a username (3-20 characters)" minlength="3" maxlength="20" required>
      <div class="error-message" id="username-error">Username must be between 3 and 20 characters.</div>
    </div>

    <div class="input-group">
      <label for="birthday">Birthday</label>
      <input type="date" class="input-field" id="birthday" required>
      <div class="error-message" id="birthday-error">Please enter a valid date.</div>
    </div>

    <div class="input-group">
      <label for="phone">Phone Number</label>
      <div class="phone-group">
        <select class="input-field" id="country-select">
          <!-- Countries will be populated by JavaScript -->
        </select>
        <input type="tel" class="input-field" id="phone" placeholder="Enter number">
      </div>
      <div class="error-message" id="phone-error">Please enter a valid phone number.</div>
    </div>

    <div class="input-group">
      <label for="bio">Short Bio (minimum 10 words)</label>
      <textarea class="input-field" id="bio" rows="3" placeholder="Tell us a bit about yourself (minimum 10 words)" maxlength="500"></textarea>
      <div class="error-message" id="bio-error">Please enter at least 10 words in your bio.</div>
    </div>

    <button id="confirm-btn" class="proceed-btn">Confirm Profile</button>
  </div>

  <div class="success-message" id="success-message">
    <h3>ðŸŽ‰ Congratulations!</h3>
    <p>Your account has been created successfully!</p>
    <button id="goto-login" class="proceed-btn">
      Go to Login Page
    </button>
  </div>

  <div class="form-group" id="welcome-message"></div>
</div>

<script>
const countries = [
  { name: "Afghanistan", dial: "+93" },
  { name: "Albania", dial: "+355" },
  { name: "Algeria", dial: "+213" },
  { name: "Andorra", dial: "+376" },
  { name: "Angola", dial: "+244" },
  { name: "Argentina", dial: "+54" },
  { name: "Armenia", dial: "+374" },
  { name: "Australia", dial: "+61" },
  { name: "Austria", dial: "+43" },
  { name: "Azerbaijan", dial: "+994" },
  { name: "Bahrain", dial: "+973" },
  { name: "Bangladesh", dial: "+880" },
  { name: "Belarus", dial: "+375" },
  { name: "Belgium", dial: "+32" },
  { name: "Belize", dial: "+501" },
  { name: "Benin", dial: "+229" },
  { name: "Bhutan", dial: "+975" },
  { name: "Bolivia", dial: "+591" },
  { name: "Brazil", dial: "+55" },
  { name: "Bulgaria", dial: "+359" },
  { name: "Cambodia", dial: "+855" },
  { name: "Cameroon", dial: "+237" },
  { name: "Canada", dial: "+1" },
  { name: "Chile", dial: "+56" },
  { name: "China", dial: "+86" },
  { name: "Colombia", dial: "+57" },
  { name: "Costa Rica", dial: "+506" },
  { name: "Croatia", dial: "+385" },
  { name: "Cuba", dial: "+53" },
  { name: "Cyprus", dial: "+357" },
  { name: "Czech Republic", dial: "+420" },
  { name: "Denmark", dial: "+45" },
  { name: "Ecuador", dial: "+593" },
  { name: "Egypt", dial: "+20" },
  { name: "Estonia", dial: "+372" },
  { name: "Ethiopia", dial: "+251" },
  { name: "Finland", dial: "+358" },
  { name: "France", dial: "+33" },
  { name: "Georgia", dial: "+995" },
  { name: "Germany", dial: "+49" },
  { name: "Ghana", dial: "+233" },
  { name: "Greece", dial: "+30" },
  { name: "Greenland", dial: "+299" },
  { name: "Hungary", dial: "+36" },
  { name: "Iceland", dial: "+354" },
  { name: "India", dial: "+91" },
  { name: "Indonesia", dial: "+62" },
  { name: "Iran", dial: "+98" },
  { name: "Iraq", dial: "+964" },
  { name: "Ireland", dial: "+353" },
  { name: "Israel", dial: "+972" },
  { name: "Italy", dial: "+39" },
  { name: "Jamaica", dial: "+1" },
  { name: "Japan", dial: "+81" },
  { name: "Jordan", dial: "+962" },
  { name: "Kazakhstan", dial: "+7" },
  { name: "Kenya", dial: "+254" },
  { name: "Kuwait", dial: "+965" },
  { name: "Kyrgyzstan", dial: "+996" },
  { name: "Latvia", dial: "+371" },
  { name: "Lebanon", dial: "+961" },
  { name: "Libya", dial: "+218" },
  { name: "Liechtenstein", dial: "+423" },
  { name: "Lithuania", dial: "+370" },
  { name: "Luxembourg", dial: "+352" },
  { name: "Madagascar", dial: "+261" },
  { name: "Malaysia", dial: "+60" },
  { name: "Maldives", dial: "+960" },
  { name: "Malta", dial: "+356" },
  { name: "Mexico", dial: "+52" },
  { name: "Monaco", dial: "+377" },
  { name: "Mongolia", dial: "+976" },
  { name: "Morocco", dial: "+212" },
  { name: "Myanmar", dial: "+95" },
  { name: "Nepal", dial: "+977" },
  { name: "Netherlands", dial: "+31" },
  { name: "New Zealand", dial: "+64" },
  { name: "Nigeria", dial: "+234" },
  { name: "North Korea", dial: "+850" },
  { name: "Norway", dial: "+47" },
  { name: "Oman", dial: "+968" },
  { name: "Pakistan", dial: "+92" },
  { name: "Palestine", dial: "+970" },
  { name: "Panama", dial: "+507" },
  { name: "Paraguay", dial: "+595" },
  { name: "Peru", dial: "+51" },
  { name: "Philippines", dial: "+63" },
  { name: "Poland", dial: "+48" },
  { name: "Portugal", dial: "+351" },
  { name: "Qatar", dial: "+974" },
  { name: "Romania", dial: "+40" },
  { name: "Russia", dial: "+7" },
  { name: "Saudi Arabia", dial: "+966" },
  { name: "Senegal", dial: "+221" },
  { name: "Serbia", dial: "+381" },
  { name: "Singapore", dial: "+65" },
  { name: "Slovakia", dial: "+421" },
  { name: "Slovenia", dial: "+386" },
  { name: "Somalia", dial: "+252" },
  { name: "South Africa", dial: "+27" },
  { name: "South Korea", dial: "+82" },
  { name: "Spain", dial: "+34" },
  { name: "Sri Lanka", dial: "+94" },
  { name: "Sudan", dial: "+249" },
  { name: "Sweden", dial: "+46" },
  { name: "Switzerland", dial: "+41" },
  { name: "Syria", dial: "+963" },
  { name: "Taiwan", dial: "+886" },
  { name: "Thailand", dial: "+66" },
  { name: "Turkey", dial: "+90" },
  { name: "Ukraine", dial: "+380" },
  { name: "United Arab Emirates", dial: "+971" },
  { name: "United Kingdom", dial: "+44" },
  { name: "United States", dial: "+1" },
  { name: "Uruguay", dial: "+598" },
  { name: "Uzbekistan", dial: "+998" },
  { name: "Vatican City", dial: "+379" },
  { name: "Venezuela", dial: "+58" },
  { name: "Vietnam", dial: "+84" },
  { name: "Yemen", dial: "+967" },
  { name: "Zimbabwe", dial: "+263" }
];

// Populate country select
const countrySelect = document.getElementById('country-select');
countries.forEach(country => {
  const option = document.createElement('option');
  option.value = country.dial;
  option.textContent = `${country.name} (${country.dial})`;
  countrySelect.appendChild(option);
});

let userData = {
  username: '',
  email: '',
  firstName: '',
  lastName: '',
  birthday: '',
  phone: '',
  bio: ''
};

document.getElementById('login-btn').onclick = function() {
  google.accounts.id.initialize({
    client_id: 'your scret clinet id',
    callback: handleCredentialResponse
  });
  google.accounts.id.prompt();
};

async function handleCredentialResponse(response) {
  const idToken = response.credential;
  const payload = JSON.parse(atob(idToken.split('.')[1]));
  
  // Check if user already exists
  const checkUser = await fetch('verify_user.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email: payload.email })
  });
  
  const result = await checkUser.json();
  
  if (result.exists) {
    document.getElementById('error-popup').style.display = 'block';
    return;
  }

  // Split full name into first and last name
  const nameParts = payload.name.split(' ');
  userData.firstName = nameParts[0];
  userData.lastName = nameParts[nameParts.length - 1];
  userData.email = payload.email;

  // Populate the name fields
  document.getElementById('firstName').value = userData.firstName;
  document.getElementById('lastName').value = userData.lastName;

  document.getElementById('set-username-btn').style.display = 'block';
  document.getElementById('login-btn').style.display = 'none';
}

document.getElementById('set-username-btn').onclick = function() {
  document.getElementById('profile-form').style.display = 'block';
  document.getElementById('set-username-btn').style.display = 'none';
};

function isValidPhoneNumber(phone) {
  return /^\d{6,14}$/.test(phone);
}

function countWords(str) {
  return str.trim().split(/\s+/).length;
}

document.getElementById('confirm-btn').onclick = function() {
  let isValid = true;
  
  // Validate username
  userData.username = document.getElementById('username').value.trim();
  if (userData.username.length < 3 || userData.username.length > 20) {
    document.getElementById('username-error').style.display = 'block';
    isValid = false;
  } else {
    document.getElementById('username-error').style.display = 'none';
  }

  // Get updated first and last name
  userData.firstName = document.getElementById('firstName').value.trim();
  userData.lastName = document.getElementById('lastName').value.trim();

  // Validate birthday
  userData.birthday = document.getElementById('birthday').value;
  if (!userData.birthday) {
    document.getElementById('birthday-error').style.display = 'block';
    isValid = false;
  } else {
    document.getElementById('birthday-error').style.display = 'none';
  }

  // Validate phone
  const selectedCountryCode = document.getElementById('country-select').value;
  const phoneNumber = document.getElementById('phone').value.trim();
  if (!isValidPhoneNumber(phoneNumber)) {
    document.getElementById('phone-error').style.display = 'block';
    isValid = false;
  } else {
    document.getElementById('phone-error').style.display = 'none';
    userData.phone = `${selectedCountryCode}-${phoneNumber}`;
  }

  // Validate bio (10 words minimum)
  userData.bio = document.getElementById('bio').value.trim();
  if (countWords(userData.bio) < 10) {
    document.getElementById('bio-error').textContent = 'Please enter at least 10 words in your bio.';
    document.getElementById('bio-error').style.display = 'block';
    isValid = false;
  } else {
    document.getElementById('bio-error').style.display = 'none';
  }

  if (isValid) {
    // Send data to server
    const formData = new FormData();
    Object.entries(userData).forEach(([key, value]) => {
      formData.append(key, value);
    });

    fetch('display.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(() => {
      // Hide the profile form
      document.getElementById('profile-form').style.display = 'none';
      // Show success message
      document.getElementById('success-message').style.display = 'block';
    })
    .catch(error => console.error('Error:', error));
  }
};

// Add event listener for the goto-login button
document.getElementById('goto-login').onclick = function() {
  window.location.href = 'index.php';
};
</script>

</body>
</html>
