<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: indexx.php");
    exit();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];

    // Update image if new one uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $image = $product['image'];
    }

    $sql = "UPDATE products SET name='$name', price='$price', image='$image', rating='$rating' WHERE id=$id";
    if ($conn->query($sql)) {
        header("Location: product.php");
        exit();
    } else {
        echo "<script>alert('Error updating product.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h2>Edit Product</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Price (₹)</label>
        <input type="number" name="price" value="<?= htmlspecialchars($product['price']); ?>" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Rating</label>
        <input type="number" name="rating" value="<?= htmlspecialchars($product['rating']); ?>" step="0.1" min="0" max="5" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="uploads/<?= htmlspecialchars($product['image']); ?>" width="100" height="100"><br><br>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Update Product</button>
      <a href="product.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
