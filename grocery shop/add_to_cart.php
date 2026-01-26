<?php
session_start();

if(isset($_POST['add_to_cart'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
    }
}

// ✅ reduce quantity
if(isset($_POST['remove_from_cart'])){
    $id = $_POST['id'];

    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['quantity']--;

        // if qty becomes 0 → remove item fully
        if($_SESSION['cart'][$id]['quantity'] <= 0){
            unset($_SESSION['cart'][$id]);
        }
    }
}

// ✅ delete item completely
if(isset($_POST['delete_from_cart'])){
    $id = $_POST['id'];
    if(isset($_SESSION['cart'][$id])){
        unset($_SESSION['cart'][$id]);
    }
}

header("Location: index.php");
exit;
?>
