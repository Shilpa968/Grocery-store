<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset Confirmation</title>
  <style>
    body {
      font-family: 'Poppons', sans-serif;
      background: #eee;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .box {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
      text-align: center;
      width: 400px;
    }
    .box:hover{
        box-shadow: 1px 1px 10px 4px green;
    }
    h2 {
      color: black;
    }
    p {
      margin-top: 15px;
      font-size: 16px;
      color: #555;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: green;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
    }
    a:hover {
        color: black;
      background: rgb(20, 177, 20);
    }
  </style>
</head>
<body>
  <div class="box">
    <h2><img src="image/favicon.PNG">  Confirmation Link Sent</h2>
    <p>A confirmation link has been sent to your registered email.</p>
    <p>Please check your inbox and follow the link to change your password.</p>
    <a href="index.php">Back to Home</a>
  </div>
</body>
</html>