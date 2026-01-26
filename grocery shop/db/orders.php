<?php
session_start();
include 'config.php';

// Check admin login
if (!isset($_SESSION['admin'])) {
    header("Location: indexx.php");
    exit();
}

// Capture search term
$search = $_GET['search'] ?? '';

// SQL query with optional search filter
$query = "SELECT * FROM orders WHERE 1=1";

if($search != '') {
    $query .= " AND (customer_name LIKE '%$search%' OR customer_phone LIKE '%$search%')";
}

$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fff8e1; /* soft cream */
      font-family: 'Poppins', sans-serif;
    }

    h2 {
      color: #f57f17; /* dark golden */
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }

    .search-form input {
      border-radius: 10px;
      border: 2px solid #f9a825;
      padding: 10px 15px;
    }

    .search-form button {
      border-radius: 10px;
      border: none;
      font-weight: bold;
      padding: 10px 20px;
      transition: all 0.3s ease;
    }

    .search-form .btn-primary {
      background-color: #f9a825;
      border-color: #f9a825;
      color: #ffffff;
    }

    .search-form .btn-primary:hover {
      background-color: #f57f17;
      transform: scale(1.05);
    }

    .search-form .btn-secondary {
      background-color: #fff176;
      color: #f57f17;
    }

    .search-form .btn-secondary:hover {
      background-color: #ffee58;
      color: #f57f17;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
      border: 2px solid #f9a825; /* strong golden border */
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 100%;
      background: #ffffff;
      table-layout: fixed;
      min-width: 900px;
    }

    /* Column widths */
    th:nth-child(1), td:nth-child(1) { width: 5%; }   /* Serial No */
    th:nth-child(2), td:nth-child(2) { width: 15%; }  /* User ID */
    th:nth-child(3), td:nth-child(3) { width: 20%; }  /* Customer Name */
    th:nth-child(4), td:nth-child(4) { width: 15%; }  /* Phone */
    th:nth-child(5), td:nth-child(5) { width: 25%; }  /* Address */
    th:nth-child(6), td:nth-child(6) { width: 10%; }  /* Total */
    th:nth-child(7), td:nth-child(7) { width: 10%; }  /* Payment Method */
    th:nth-child(8), td:nth-child(8) { width: 10%; }  /* Date */

    th {
      background-color: #f9a825 !important; /* dark golden header */
      color: #ffffff !important;
      font-weight: 600;
      text-align: center;
      padding: 15px;
    }

    td {
      text-align: center;
      vertical-align: middle;
      padding: 12px;
      word-wrap: break-word;
      border-bottom: 1px solid #fbc02d;
    }

    /* Zebra striping: alternating row colors */
    tbody tr:nth-child(odd) {
      background-color: #fdd835; /* rich golden yellow */
    }

    tbody tr:nth-child(even) {
      background-color: #fff176; /* lighter yellow */
    }

    tr:hover {
      background-color: #ffb300; /* darker yellow hover */
      transform: scale(1.01);
      transition: all 0.2s ease-in-out;
    }

    .btn-back {
      background: #f9a825;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      padding: 12px 25px;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-back:hover {
      background: #f57f17;
      color: white;
      text-decoration: none;
    }

    .container {
      max-width: 1200px;
      margin-top: 50px;
      padding: 20px;
    }

    .search-form {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    @media(max-width: 768px){
        table { font-size: 14px; min-width: 700px; }
        .search-form { flex-direction: column; }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>All Orders</h2>

  <!-- Search Form with Search + Refresh -->
  <form method="get" class="search-form">
      <input type="text" name="search" class="form-control" placeholder="Search by name or phone" 
             value="<?= htmlspecialchars($search); ?>">
      <button type="submit" class="btn btn-primary">Search</button>
      <a href="orders.php" class="btn btn-secondary">Refresh</a>
  </form>

  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>User ID</th>
          <th>Customer Name</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Total</th>
          <th>Payment Method</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><?= htmlspecialchars($row['user_id']); ?></td>
              <td>
                <a href="order_details.php?id=<?= $row['id']; ?>" 
                   style="text-decoration:none; color:#f57f17; font-weight:bold;">
                  <?= htmlspecialchars($row['customer_name']); ?>
                </a>
              </td>
              <td><?= htmlspecialchars($row['customer_phone']); ?></td>
              <td><?= htmlspecialchars($row['customer_address']); ?></td>
              <td>₹<?= number_format($row['total'], 2); ?></td>
              <td><?= htmlspecialchars($row['payment_method']); ?></td>
              <td><?= date("d-m-Y H:i", strtotime($row['created_at'])); ?></td>
            </tr>
        <?php 
          endwhile;
        } else {
          echo "<tr><td colspan='8' class='text-center text-muted'>No orders found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <a href="dashboard.php" class="btn-back mt-3">Back</a>
</div>
</body>
</html>
