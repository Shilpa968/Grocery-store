<?php
session_start();
include "db.php"; // your database connection

// Handle login form submission
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php"); // reload page or redirect to homepage
            exit;
        } else {
            $login_error = "Incorrect password!";
        }
    } else {
        $login_error = "Email not registered!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freshmart</title>
    <!--linking css-->
    <link rel="stylesheet" type="text/css" href="style.css">
    <!--font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--Slider code using swipperjs.com-->
   <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
</head>
<body>
    <!--Header section-->
    <header class="header">
        <a href="#" class="logo"><i class="fa fa-shopping-basket" aria-hidden="true"></i>FreshMart</a>
        <nav class="navbar">
            <a href="#home" >Home</a>
            <a href="#features" >Features</a>
            <a href="#products" >Products</a>
            <a href="#blogs" >Blogs</a>
        </nav>
        <div class="icons">
            <div class="fa fa-bars" id="menu-btn"></div>
            <div class="fa fa-search" id="search-btn"></div>
            <div class="fa fa-shopping-cart" id="cart-btn">
             <?php
             $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
             if($cart_count > 0){
                 echo "<span style='background:red;color:white;border-radius:50%;padding:2px 6px;font-size:12px;position:absolute;margin-left:-10px;'>$cart_count</span>";
            }
         ?>
        </div>

            <div class="fa fa-user" id="login-btn"></div>
        </div>
        <form class="search-form" action="" method="GET">
            <input type="search" id="search-box" name="my_search" placeholder="Search your products....">
            <label for ="search-box" class="fa fa-search"></label>
        </form>
       <?php if(isset($_SESSION['user_id'])): ?>
    <div class="profile-box">
        <h3>Welcome, <?php echo $_SESSION['user_name']; ?> 👋</h3>
        <a href="view_cart.php" class="btn">My Cart</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
    <?php else: ?>
    <form method="post" class="login-form">
        <h3>Login Now</h3>
        <input type="email" name="email" placeholder="Your email" class="box" required>
        <input type="password" name="password" placeholder="Your Password" class="box" required>

        <p>Forget Your Password? <a href="forgetpassword.php"> Click Here</a> </p>
        <p>Don't Have An Account? <a href="register.php"> Create Now</a> </p>

        <input type="submit" name="login" value="Login Now" class="btn">
    </form>
    <?php endif; ?>

    <div class="shopping-cart" id="shopping-cart">
    <?php
    $total = 0;
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $item){
            $sub = $item['price'] * $item['quantity'];
            $total += $sub;
            echo "<div class='cart-item'>
                <h4>{$item['name']}</h4>
                <p>₹{$item['price']} x {$item['quantity']} = ₹$sub</p>
                </div>";
        }
    } else {
        echo "<p>Your cart is empty</p>";
    }
    ?>
    <div class="total">Total: ₹<?php echo $total; ?></div>
    <a href="view_cart.php" class="btn">Checkout</a>
    </div>


    </header>

    <!--banner section-->
    <section class="home" id="home">
        <div class="content">
            <h3>Fresh And <span>Organic</span> Products For You</h3>
            <p>Bringing you the freshest groceries, delivered with care, right when you need them. </p>
            <a href="#products" class="btn">Shop Now</a>
        </div>
    </section>

    <!--Our Feature Section-->
    <section class="features" id="features">
        <h1 class="heading"> Our <span>Features</span> </h1>

        <div class="box-container">
            <div class="box">
                <img src="fruit mix.jpg" alt="organic food">
                <h3>Fresh And Organic</h3>
                <p>Fresh Organic veggies and fruits at your doorstep with all the love.</p>
                
            </div>

            <div class="box">
                <img src="free delivery.jpg" alt="organic food">
                <h3>Free delivery</h3>
                <p>Fresh Organic veggies and fruits at your doorstep with all the love.</p>
                
            </div>

            <div class="box">
                <img src="easy payment.jpg" alt="organic food">
                <h3>Easy Payments</h3>
                <p>Fresh Organic veggies and fruits at your doorstep with all the love.</p>
                
            </div>
        </div>

    </section>

    <!--Our Product Section-->
    <section class="products" id="products">
        <h1 class="heading">Our <span>Products</span></h1>

        <div class="swiper product-slider">
            <div class="swiper-wrapper">
                <?php
                    include "db.php"; // make sure db.php is included
                    $search = isset($_GET['my_search']) ? $_GET['my_search'] : "";
                    $sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
                    $result = $conn->query($sql);

while($row = $result->fetch_assoc()): ?>
    <div class="swiper-slide box">
        <form method="post" action="add_to_cart.php">
            <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h1><?php echo $row['name']; ?></h1>
            <div class="price">₹<?php echo $row['price']; ?>/-</div>

            <!-- Star Rating -->
            <div class="rating">
                <?php
                $rating = $row['rating']; // assuming your DB has a 'rating' column
                for($i=1; $i<=5; $i++){
                    if($i <= floor($rating)){
                        echo '<span style="color:gold;">★</span>'; // full star
                    } else {
                        echo '<span style="color:gray;">☆</span>'; // empty star
                    }
                }
                echo " ($rating)";
                ?>
            </div>

            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
            <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
            <input type="hidden" name="rating" value="<?php echo $row['rating']; ?>">
            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
        </form>
    </div>
<?php endwhile; ?>


    </section>
   <!--Blog section-->
    <section class="blogs" id="blogs">
        <h1 class="heading">Our <span>Blogs</span></h1>
        <div class="box-container">
            <div class="box">
                <img src="bit.jpg">
                <div class="content">
                    <div class="icons">
                        <a href="#"><i class="fa fa-user"></i>By Vaibhav</a>
                        <a href="#"><i class="fa fa-calendar"></i> 22nd august, 2025</a>
                    </div>
                    <h3>Fresh and Organic Vegetables and Fruits</h3>
                    <p>Fresh Organic veggies and fruits at your doorstep with all the love.Fresh Organic veggies and fruits at your doorstep with all the love.</p>
                    <a href="#" class="btn">Read More</a>
                </div>
            </div>

            <div class="box">
                <img src="mixed veg.jpg">
                <div class="content">
                    <div class="icons">
                        <a href="#"><i class="fa fa-user"></i>By Lisha</a>
                        <a href="#"><i class="fa fa-calendar"></i> 22nd august, 2025</a>
                    </div>
                    <h3>Fresh and Organic Vegetables and Fruits</h3>
                    <p>Fresh Organic veggies and fruits at your doorstep with all the love.Fresh Organic veggies and fruits at your doorstep with all the love.</p>
                    <a href="#" class="btn">Read More</a>
                </div>
            </div>

            <div class="box">
                <img src="again mix.jpg">
                <div class="content">
                    <div class="icons">
                        <a href="#"><i class="fa fa-user"></i>By Ankita</a>
                        <a href="#"><i class="fa fa-calendar"></i> 22nd august, 2025</a>
                    </div>
                    <h3>Fresh and Organic Vegetables and Fruits</h3>
                    <p>Fresh Organic veggies and fruits at your doorstep with all the love.Fresh Organic veggies and fruits at your doorstep with all the love.</p>
                    <a href="#" class="btn">Read More</a>
                </div>
            </div>

        </div>
    </section>





    <!--Footer Design-->
    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>FreshMart <i class="fa fa-shopping-basket"></i></h3>
                <p>Feel Free To Follow Us On Our Social Media Handlers All The Links Are Given Below.</p>
                <div class="share">
                    <a href="#" class="fa fa-facebook"></a>
                    <a href="#" class="fa fa-twitter"></a>
                    <a href="#" class="fa fa-instagram"></a>
                    <a href="#" class="fa fa-linkedin"></a>
                    <a href="#" class="fa fa-whatsapp"></a>
                </div>
            </div>

            <div class="box">
                <h3>Contact Info</h3>
                <a href="#" class="links"><i class="fa fa-phone"></i> +91 7294177186</a>
                <a href="#" class="links"><i class="fa fa-phone"></i> +91 7294177186</a>
                <a href="#" class="links"><i class="fa fa-envelope"></i> vhvkr2103@gmail.com</a>
                <a href="#" class="links"><i class="fa fa-map-marker"></i> Kolkata,West Bengal, India</a>
            </div>

             <div class="box">
                <h3>Quick Links</h3>
                <a href="#" class="links"><i class="fa fa-arrow-right"></i> Home</a>
                <a href="#" class="links"><i class="fa fa-arrow-right"></i> Features</a>
                <a href="#" class="links"><i class="fa fa-arrow-right"></i> Products</a>
                <a href="#" class="links"><i class="fa fa-arrow-right"></i> Categories</a>
                <a href="#" class="links"><i class="fa fa-arrow-right"></i> Reviews</a>
            </div>

            <div class="box">
                <h3>Newsletter</h3>
                <p>Subscribe For Latest Updates</p>
                <input type="email" placeholder="Your Email" class="email">
                <input type="submit" value="Subscribe" class="btn">
                <img src="patyment.jpg" class="payment-img">
            </div>

        </div>

        <div class="credit">Created by <span>team grocery</span> | All Rights Reserved</div>

    </section>




    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>