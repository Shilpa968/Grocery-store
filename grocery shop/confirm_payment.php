<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    echo "You must <a href='index.php'>login</a> to place an order.";
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user_id = $_SESSION['user_id'];

    // ✅ Ensure session values exist
        $customer_name = $_POST['customer_name'] ?? $_SESSION['customer_name'] ?? 'Unknown Customer';
        $customer_phone = $_POST['customer_phone'] ?? $_SESSION['customer_phone'] ?? 'No Phone Provided';
        $customer_address = $_POST['customer_address'] ?? $_SESSION['customer_address'] ?? 'No Address Provided';


    // ✅ Recalculate total as backup if missing
    if(!isset($_SESSION['grand_total']) || $_SESSION['grand_total'] <= 0){
        $total_amount = 0;
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $item){
                $total_amount += $item['price'] * $item['quantity'];
            }
        }
        $_SESSION['grand_total'] = $total_amount;
    } else {
        $total_amount = $_SESSION['grand_total'];
    }

    $payment_method = $_POST['payment_method'] ?? 'Not Selected';

    // ✅ Insert into orders table
    $sql = "INSERT INTO orders (user_id, customer_name,customer_phone,customer_address, total, payment_method) 
            VALUES ('$user_id', '$customer_name','$customer_phone','$customer_address', '$total_amount', '$payment_method')";
    
    if($conn->query($sql)){
        $order_id = $conn->insert_id;

        // ✅ Insert each cart item
        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $pid => $item){
                $pid = (int)$pid;
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];
                $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES ($order_id, $pid, $quantity, $price)");
            }
        }

        // ✅ Clear cart after successful order
        $_SESSION['cart'] = [];
        $_SESSION['grand_total'] = 0;

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Payment Success</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
          <style>
            body {
                background: linear-gradient(135deg, #6dd5ed, #2193b0);
                font-family: 'Poppins', sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .success-box {
                background: white;
                padding: 40px;
                border-radius: 20px;
                text-align: center;
                box-shadow: 0 8px 30px rgba(0,0,0,0.3);
                animation: fadeIn 1.2s ease-in-out;
                max-width: 500px;
                width: 90%;
            }

            .checkmark {
              font-size: 60px;
              color: green;
              animation: pop 0.6s ease forwards;
            }
            h2 {
              color: green;
              font-weight: bold;
              margin-top: 20px;
            }
            p {
              font-size: 18px;
              color: #333;
            }
            .btn-shop {
              margin-top: 20px;
              padding: 12px 25px;
              font-size: 18px;
              border-radius: 10px;
              background: linear-gradient(90deg, #2193b0, #6dd5ed);
              color: white;
              text-decoration: none;
              transition: all 0.3s ease-in-out;
            }
            .btn-shop:hover {
              transform: scale(1.05);
              background: linear-gradient(90deg, #6dd5ed, #2193b0);
            }
            @keyframes fadeIn {
              from {opacity: 0; transform: translateY(30px);}
              to {opacity: 1; transform: translateY(0);}
            }
            @keyframes pop {
              0% {transform: scale(0.5); opacity: 0;}
              100% {transform: scale(1); opacity: 1;}
            }
          </style>
        </head>
        <body>
          <div class="success-box">
            <div class="checkmark">✅</div>
            <h2>Payment Successful!</h2>
            <p>Your order has been placed successfully with <b><?php echo ucfirst($payment_method); ?></b>.</p>
            <p><b>Total:</b> ₹<?php echo number_format($total_amount, 2); ?></p>
            <a href="index.php" class="btn-shop">🛒 Back to Shop</a>
          </div>
        </body>
        </html>
        <?php
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
