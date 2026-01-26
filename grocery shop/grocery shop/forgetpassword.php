<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <style>
    body {
      font-family: 'Poppons', sans-serif;
      background: #eee;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: #fff;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
      width: 300px;
    }
    .container:hover{
        box-shadow: 1px 1px 10px 4px green;
    }
    h2 {
      text-align: center;
      color: black;
    }
    
    input[type="email"] {
      width: 92%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      background: #eee;
    }
    button {
      
      width: 100%;
      padding: 10px;
      background: green;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
        background: rgb(22, 154, 22);
    }
    button a{
        color: white;
        text-decoration: none;
    }
    p{
      text-align: center;
      margin-top: 15px;
      text-decoration: none;
    }
    p a{
        text-decoration: none;
        color: green;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2> <img src="image/favicon.PNG"> Forgot Password</h2>
    <form action="password_reset.php" method="post">
      <label for="email">Enter your registered email:</label>
      <input type="email" name="email" id="email" placeholder="example@email.com" required>
      <button type="submit"><a href="passwprd_reset.php">Send Reset Link</a></button>
    </form>
    <p><a href="index.php">Back to Login</a></p>
  </div>
</body>
</html>