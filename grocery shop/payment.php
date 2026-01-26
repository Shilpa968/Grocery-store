<?php
session_start();
include "db.php";

// ✅ Save customer info from view_cart.php form into session
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['customer_name']) && isset($_POST['customer_address'])) {
        $_SESSION['customer_name'] = $_POST['customer_name'];
        $_SESSION['customer_address'] = $_POST['customer_address'];

        // ✅ Capture and store phone number too
        if (isset($_POST['customer_phone'])) {
            $_SESSION['customer_phone'] = $_POST['customer_phone'];
        }
    }
}

// ✅ Calculate total from session cart 
$total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
$_SESSION['grand_total'] = $total;

// ✅ Retrieve from session
$customer_name = $_SESSION['customer_name'] ?? '';
$customer_address = $_SESSION['customer_address'] ?? '';
$customer_phone = $_SESSION['customer_phone'] ?? ''; // ✅ new variable
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment - FreshMart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
   .payment-container {
      max-width: 500px;
      margin: 50px auto;
      background: #e4e7e1ff;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 20px 30px rgba(80, 125, 31, 0.8);
      animation: slideUp 1s ease-in-out;
    }
    @keyframes slideUp {
      from { transform: translateY(40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    h2 {
      text-align: center;
      color: #2e7d32;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .payment-methods button {
      width: 100%;
      margin-bottom: 12px;
      padding: 12px;
      border-radius: 10px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      transition: transform 0.3s;
    }
    .payment-methods button img {
      width: 28px;
      margin-right: 10px;
    }
    .payment-methods button:hover {
      transform: scale(1.05);
    }
    .active-method {
      border: 3px solid #48771eff;
      background: #e1dab3ff !important;
    }
    .btn-pay {
      width: 100%;
      background: linear-gradient(90deg, #43cea2, #185a9d);
      color: white;
      font-weight: bold;
      font-size: 18px;
      padding: 12px;
      border-radius: 10px;
      margin-top: 15px;
      transition: transform 0.3s, background 0.3s;
    }
    .btn-pay:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #185a9d, #43cea2);
    }
  </style>

  <script>
    function showPaymentForm(method) {
      document.getElementById('card-form').style.display = 'none';
      document.getElementById('upi-form').style.display = 'none';
      document.getElementById('paypal-form').style.display = 'none';
      document.getElementById(method+'-form').style.display = 'block';

      let buttons = document.querySelectorAll('.payment-methods button');
      buttons.forEach(btn => btn.classList.remove('active-method'));
      document.getElementById(method+'-btn').classList.add('active-method');
    }
  </script>
</head>
<body>
<div class="payment-container">
  <h2>💳 Payment</h2>
  <h5>Order Summary</h5>

  <p><strong>Name:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
  <p><strong>Phone:</strong> <?php echo htmlspecialchars($customer_phone); ?></p> <!-- ✅ Display phone -->
  <p><strong>Address:</strong> <?php echo htmlspecialchars($customer_address); ?></p>
  <p><strong>Total Amount:</strong> ₹<?php echo $total; ?></p>

  <!-- Payment Method Buttons -->
  <div class="payment-methods">
    <button type="button" id="card-btn" onclick="showPaymentForm('card')" class="btn btn-light">
      <img src="https://cdn-icons-png.flaticon.com/512/633/633611.png" alt="Card"> Pay with Card
    </button>
    <button type="button" id="upi-btn" onclick="showPaymentForm('upi')" class="btn btn-light">
      <img src="https://cdn-icons-png.flaticon.com/512/5968/5968204.png" alt="UPI"> Pay with UPI
    </button>
    <button type="button" id="paypal-btn" onclick="showPaymentForm('paypal')" class="btn btn-light">
      <img src="https://cdn-icons-png.flaticon.com/512/174/174861.png" alt="PayPal"> Pay with PayPal
    </button>
  </div>

  <!-- ✅ Card Payment Form -->
  <form method="post" action="confirm_payment.php" id="card-form" style="display:none;">
    <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>">
    <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($customer_phone); ?>"> <!-- ✅ -->
    <input type="hidden" name="customer_address" value="<?php echo htmlspecialchars($customer_address); ?>">
    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
    <input type="hidden" name="payment_method" value="Card">

    <div class="mb-3">
      <input type="text" name="card_number" class="form-control" placeholder="Card Number" required>
    </div>
    <div class="mb-3">
      <input type="text" name="expiry" class="form-control" placeholder="Expiry MM/YY" required>
    </div>
    <div class="mb-3">
      <input type="text" name="cvv" class="form-control" placeholder="CVV" required>
    </div>
    <button type="submit" name="pay_now" class="btn-pay">Pay Now</button>
  </form>

  <!-- ✅ UPI Payment Form -->
  <form method="post" action="confirm_payment.php" id="upi-form" style="display:none;">
    <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>">
    <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($customer_phone); ?>"> <!-- ✅ -->
    <input type="hidden" name="customer_address" value="<?php echo htmlspecialchars($customer_address); ?>">
    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
    <input type="hidden" name="payment_method" value="UPI">

    <div class="mb-3">
      <input type="text" name="upi_id" class="form-control" placeholder="Enter UPI ID" required>
    </div>
    <button type="submit" name="pay_now" class="btn-pay">Pay Now via UPI</button>
  </form>

  <!-- ✅ PayPal Payment Form -->
  <form method="post" action="confirm_payment.php" id="paypal-form" style="display:none;">
    <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>">
    <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($customer_phone); ?>"> <!-- ✅ -->
    <input type="hidden" name="customer_address" value="<?php echo htmlspecialchars($customer_address); ?>">
    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
    <input type="hidden" name="payment_method" value="PayPal">

    <div class="mb-3">
      <input type="email" name="paypal_email" class="form-control" placeholder="PayPal Email" required>
    </div>
    <button type="submit" name="pay_now" class="btn-pay">Pay Now via PayPal</button>
  </form>
</div>

<script>
  // Default show Card method
  showPaymentForm('card');
</script>
</body>
</html>
