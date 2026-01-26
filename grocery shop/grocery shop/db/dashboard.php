<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: indexx.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Grocery Admin</a>
      <div class="d-flex">
        <span class="navbar-text text-white me-3">Welcome, <?php echo $_SESSION['admin']; ?></span>
        <a href="indexx.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="row text-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Products</h5>
            <p class="card-text">Manage grocery products</p>
            <a href="product.php" class="btn btn-primary">Go</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Orders</h5>
            <p class="card-text">View customer orders</p>
            <a href="orders.php" class="btn btn-success">Go</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Customers</h5>
            <p class="card-text">Manage customer data</p>
            <a href="customer.php" class="btn btn-warning">Go</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>