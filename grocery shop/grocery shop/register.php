<?php
session_start();
include "db.php"; // include database connection

// Handle form submission
if(isset($_POST['register'])){
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone']; // optional, if you want to store
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Simple validation
    if($password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if($check->num_rows > 0){
            $error = "Email already registered!";
        } else {
            // Insert into users table
            $conn->query("INSERT INTO users (name,email,password) VALUES ('$name','$email','$hash')");
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            header("Location: index.php"); // redirect after registration
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - FreshMart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background: #eee; font-family: 'Poppons', sans-serif; }
    .register-container { max-width: 500px; margin: 80px auto; padding: 30px; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; }
    .register-container:hover{ box-shadow: 1px 1px 10px 4px green; }
    .register-container h2 { text-align: center; margin-bottom: 30px; color: black; }
    .form-control { margin-bottom: 15px; background: #eee; }
    .btn-success { width: 100%; }
    .register-footer { text-align: center; margin-top: 20px; }
    .register-footer a { text-decoration: none; color: green; }
    .error-msg { color: red; text-align: center; margin-bottom: 15px; }
  </style>
</head>
<body>
  <div class="register-container">
    <h2><img src="image/favicon.PNG" style="height:30px;"> Create an Account</h2>

    <!-- Display error if any -->
    <?php if(isset($error)) echo '<div class="error-msg">'.$error.'</div>'; ?>

    <form method="post" action="">
      <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="fullname" name="fullname" required placeholder="Enter your name"/>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="abc123@email.com"/>
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone" required placeholder="0123456789"/>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required placeholder="Abc@123"/>
      </div>

      <div class="mb-3">
        <label for="confirm-password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm-password" name="confirm_password" required placeholder="Abc@123"/>
      </div>

      <button type="submit" class="btn btn-success" name="register">Register</button>
    </form>

    <div class="register-footer mt-3">
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </div>
</body>
</html>
