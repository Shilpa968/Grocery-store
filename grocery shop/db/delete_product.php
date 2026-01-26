<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: indexx.php");
    exit();
}

$id = $_GET['id'];

// Get image name before deleting
$result = $conn->query("SELECT image FROM products WHERE id = $id");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imagePath = "uploads/" . $row['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath); // delete image file
    }
}

// Delete product
$conn->query("DELETE FROM products WHERE id = $id");
header("Location: product.php");
exit();
?>
