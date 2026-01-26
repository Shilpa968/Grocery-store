<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart - FreshMart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
     /* Your existing CSS */
     body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .cart-container {
      max-width: 600px;
      margin: 50px auto;
      background: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 20px 30px rgba(80, 125, 31, 0.8);
      animation: fadeIn 1s ease-in-out;
    }

    h2 {
      text-align: center;
      color: green;
      margin-bottom: 20px;
    }
    table {
      border: 3px solid black;
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      animation: slideUp 1s ease-in-out;
    }
     th, td {
      border: 3px solid green;
      padding: 10px;
      text-align: center;
    }

    th {
      font-size: 20px;
    }

    tr:hover {
      background-color: #80eb80ff;
      transform: scale(1.02);
      transition: all 0.3s ease-in-out;
    }

    .grand-total {
      font-weight: bold;
      background: #e9f5e9;
    }

    .form-section {
     text-align: center;
      color: green;
      margin: 15px 0;
      font-weight: bold;
      animation: fadeIn 2s ease-in-out;
    }

    .btn-success {
      width: 100%;
      padding: 12px;
      background: linear-gradient(90deg, green, #ffeb3b);
      color: white;
      font-size: 18px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .btn-success:hover {
      background: linear-gradient(90deg, #ffeb3b, green);
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }

    @keyframes slideUp {
      from {transform: translateY(20px); opacity: 0;}
      to {transform: translateY(0); opacity: 1;}
    }
  </style>
</head>
<body>
  <div class="cart-container">
    <h2 style="font-weight: bold;">Your Cart</h2>

    <table class="table table-bordered">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Action</th>
        </tr>

        <?php
        $grand = 0;

        if(!empty($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $id=>$item){
                $sub = $item['price'] * $item['quantity'];
                $grand += $sub;
                echo "<tr>
                        <td>{$item['name']}</td>
                        <td>₹{$item['price']}</td>
                        <td>
                            <!-- Quantity form -->
                            <form method='post' action='cart_action.php' style='display:flex; justify-content:center; align-items:center; gap:5px;'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' name='decrease' class='btn btn-outline-secondary btn-sm'>-</button>
                                <span>{$item['quantity']}</span>
                                <button type='submit' name='increase' class='btn btn-outline-secondary btn-sm'>+</button>
                            </form>
                        </td>
                        <td>₹$sub</td>
                        <td>
                            <!-- Delete form -->
                            <form method='post' action='cart_action.php'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' name='delete' class='btn btn-danger btn-sm'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
        }
        ?>
        <tr class="grand-total">
          <td colspan="3">Grand Total</td>
          <td>₹<?php echo $grand;?></td>
          <td></td>
        </tr>
    </table>
        <?php


// Assume this file handles the form where the user enters their address & confirms the order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $customer_address = $_POST['customer_address'];
    $grand_total = calculateCartTotal($_SESSION['cart']); // however you calculate total

    // ✅ Set session variables before redirecting
    $_SESSION['customer_name'] = $customer_name;
    $_SESSION['customer_phone'] = $customer_phone;
    $_SESSION['customer_address'] = $customer_address;
    

    $_SESSION['grand_total'] = $grand_total;

    // Redirect to payment confirmation page
    header("Location: confirm_payment.php");
    exit;
}
?>

    <!-- Separate Place Order Form -->
    <div class="form-section">
    <form method="post" action="payment.php">
        <h4 style="color:green; font-weight: bold;">Customer Details</h4>
        <div class="mb-3">
            <input type="text" name="customer_name" class="form-control" placeholder="Enter your name" required>
        </div>
        <div class="mb-3">
            <input type="text" name="customer_phone" class="form-control" placeholder="Enter your phone number" required>
        </div>
        <div class="mb-3">
            <textarea name="customer_address" class="form-control" placeholder="Enter your address" required></textarea>
        </div>
        <button type="submit" name="proceed_payment" class="btn btn-success">Proceed to Payment</button>
    </form>
  </div>
</div>
</body>
</html>
