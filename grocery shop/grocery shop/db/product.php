<?php
session_start();
include 'config.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin'])) {
    header("Location: indexx.php");
    exit();
}

// Fetch all products
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Products</h2>
      <a href="add_product.php" class="btn btn-primary">+ Add Product</a>
    </div>

    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price (₹)</th>
          <th>Image</th>
          <th>Rating</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td>₹<?= number_format($row['price'], 2); ?></td>
            <td><img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>" width="70" height="70"></td>
            <td><?= htmlspecialchars($row['rating']); ?></td>
            <td>
              <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center">No products found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back</a>
  </div>
</body>
</html>
