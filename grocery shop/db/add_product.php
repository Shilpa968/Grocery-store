<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: indexx.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];

    $image = $_FILES['image']['name'];
    // ✅ Fixed: Save to "images" folder outside the "db" folder
    $target_dir = "../images/";
    $target_file = $target_dir . basename($image);

    // Create the folder if it doesn’t exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO products (name, price, image, rating) VALUES ('$name', '$price', '$image', '$rating')";
        if ($conn->query($sql)) {
            header("Location: product.php");
            exit();
        } else {
            echo "<script>alert('Error adding product.');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image. Please check the folder path and permissions.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h2>Add New Product</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Price (₹)</label>
        <input type="number" name="price" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Rating</label>
        <input type="number" name="rating" step="0.1" min="0" max="5" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-success">Add Product</button>
      <a href="product.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
