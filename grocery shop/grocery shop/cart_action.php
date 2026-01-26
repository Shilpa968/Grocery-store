<?php
session_start();

// Increase quantity
if(isset($_POST['increase'])){
    $id = $_POST['id'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['quantity']++;
    }
}

// Decrease quantity
if(isset($_POST['decrease'])){
    $id = $_POST['id'];
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['quantity']--;
        if($_SESSION['cart'][$id]['quantity'] <= 0){
            unset($_SESSION['cart'][$id]);
        }
    }
}

// Delete product
if(isset($_POST['delete'])){
    $id = $_POST['id'];
    if(isset($_SESSION['cart'][$id])){
        unset($_SESSION['cart'][$id]);
    }
}

// Redirect back to cart
header("Location: view_cart.php");
exit;
?>
