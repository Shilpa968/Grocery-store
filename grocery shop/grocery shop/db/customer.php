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

// SQL query with search filter
$query = "SELECT DISTINCT customer_name, customer_phone, customer_address 
          FROM orders 
          WHERE customer_name IS NOT NULL";

if($search != '') {
    $query .= " AND (customer_name LIKE '%$search%' OR customer_phone LIKE '%$search%')";
}

$query .= " ORDER BY id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #e8f5e9; /* soft green background */
      font-family: 'Poppins', sans-serif;
    }

    h2 {
      color: #2e7d32;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }

    .search-form input {
      border-radius: 10px;
      border: 2px solid #43a047; /* green border */
      padding: 10px 15px;
    }

    .search-form button {
      border-radius: 10px;
      border: none;
      font-weight: bold;
      padding: 10px 20px;
      transition: all 0.3s ease;
    }

    .search-form button.btn-primary {
      background-color: #43a047;
      border-color: #43a047;
      color: white;
    }

    .search-form button.btn-primary:hover {
      background-color: #66bb6a;
      transform: scale(1.05);
    }

    .search-form .btn-secondary {
      background-color: #a5d6a7; /* refresh button color */
      color: #2e7d32;
    }

    .search-form .btn-secondary:hover {
      background-color: #81c784;
      color: white;
    }

    .table-responsive {
        overflow-x: auto; /* horizontal scroll on small screens */
    }

    table {
      border: 2px solid #43a047;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 100%;
      background: #ffffff;
      table-layout: fixed; /* allows column widths to be fixed */
      min-width: 800px; /* ensures table doesn’t shrink too much */
    }

    /* Column widths */
    th:nth-child(1), td:nth-child(1) { width: 10%; }  /* Serial No */
    th:nth-child(2), td:nth-child(2) { width: 20%; }  /* Name */
    th:nth-child(3), td:nth-child(3) { width: 20%; }  /* Phone */
    th:nth-child(4), td:nth-child(4) { width: 50%; }  /* Address */

    th {
      background-color: #2e7d32 !important;
      color: #ffffff !important; /* white text */
      font-weight: 600;
      text-align: center;
      padding: 15px;
    }

    td {
      text-align: center;
      vertical-align: middle;
      padding: 15px;
      word-wrap: break-word;
      border-bottom: 1px solid #43a047; /* green border between rows */
    }

    /* Zebra striping: alternating row colors */
    tbody tr:nth-child(odd) {
      background-color: #a5d6a7; /* refresh button green */
    }

    tbody tr:nth-child(even) {
      background-color: #ffffff; /* white */
    }

    tr:hover {
      background-color: #c8e6c9; /* slightly darker green on hover */
      transform: scale(1.01);
      transition: all 0.2s ease-in-out;
    }

    .btn-back {
      background: #2e7d32;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      padding: 12px 25px;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-back:hover {
      background: #66bb6a;
      color: white;
      text-decoration: none;
    }

    .container {
      max-width: 1200px; /* wider layout */
      margin-top: 50px;
      padding: 20px;
    }

    .search-form {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    @media(max-width: 768px){
        table {
            font-size: 14px;
        }
        .search-form {
            flex-direction: column;
        }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Customer List</h2>

  <!-- Search Form with Search + Refresh -->
  <form method="get" class="search-form">
      <input type="text" name="search" class="form-control" placeholder="Search by name or phone" 
             value="<?= htmlspecialchars($search); ?>">
      <button type="submit" class="btn btn-primary">Search</button>
      <a href="customer.php" class="btn btn-secondary">Refresh</a>
  </form>

  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Serial No</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Address</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><?= htmlspecialchars($row['customer_name'] ?? 'Unknown'); ?></td>
              <td><?= htmlspecialchars($row['customer_phone'] ?? 'No ph. number'); ?></td>
              <td><?= htmlspecialchars($row['customer_address'] ?? 'No Address'); ?></td>
            </tr>
        <?php 
          endwhile;
        } else {
          echo "<tr><td colspan='4' class='text-center text-muted'>No customers found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <a href="dashboard.php" class="btn-back mt-3">Back</a>
</div>
</body>
</html>
