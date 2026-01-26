<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: indexx.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid order ID.");
}

$order_id = intval($_GET['id']);

if (isset($_POST['delete_order'])) {
    $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
    $conn->query("DELETE FROM orders WHERE id = $order_id");
    header("Location: orders.php?msg=OrderDeleted");
    exit();
}

$order_sql = "SELECT * FROM orders WHERE id = $order_id";
$order_result = $conn->query($order_sql);
$order = $order_result->fetch_assoc();

if (!$order) {
    die("Order not found!");
}

$items_sql = "
    SELECT oi.*, p.name AS product_name, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $order_id
";
$items_result = $conn->query($items_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this order? This action cannot be undone!");
}
</script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 text-success">Order Details</h2>

  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5>Customer Information</h5>
      <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']); ?></p>
      <p><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']); ?></p>
      <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']); ?></p>
      <p><strong>Date:</strong> <?= date("d-m-Y H:i", strtotime($order['created_at'])); ?></p>
      <p><strong>Total:</strong> ₹<?= number_format($order['total'], 2); ?></p>
    </div>
  </div>

  <?php if ($items_result && $items_result->num_rows > 0): ?>
  <h5>Items in this Order</h5>
  <table class="table table-bordered shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Product</th>
        <th>Image</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php while($item = $items_result->fetch_assoc()): ?>
        <tr>
          <td><?= $item['id']; ?></td>
          <td><?= htmlspecialchars($item['product_name']); ?></td>
          <td>
            <?php if(!empty($item['image'])): ?>
              <img src="../images/<?= htmlspecialchars($item['image']); ?>" 
                   alt="<?= htmlspecialchars($item['product_name']); ?>" 
                   width="50" style="cursor:pointer;" 
                   data-bs-toggle="modal" 
                   data-bs-target="#imageModal<?= $item['id']; ?>">

              <!-- Unique Modal -->
              <div class="modal fade" id="imageModal<?= $item['id']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"><?= htmlspecialchars($item['product_name']); ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                      <img src="../images/<?= htmlspecialchars($item['image']); ?>" class="img-fluid">
                    </div>
                  </div>
                </div>
              </div>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($item['quantity']); ?></td>
          <td>₹<?= number_format($item['price'], 2); ?></td>
          <td>₹<?= number_format($item['quantity'] * $item['price'], 2); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="text-muted">No items found for this order.</p>
  <?php endif; ?>

  <div class="d-flex justify-content-between mt-4">
    <a href="orders.php" class="btn btn-secondary">⬅ Back to Orders</a>
    <form method="post" onsubmit="return confirmDelete()">
      <button type="submit" name="delete_order" class="btn btn-danger">🗑 Delete Order</button>
    </form>
  </div>
</div>
</body>
</html>
