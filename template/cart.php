<?php 

session_start();
require("connect-db.php"); 
require("cart-db.php");
// $_SESSION['cc'] = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['updateBtn'])) {
        $userId = getUserId($_SESSION['email']);
        $productId = $_POST['product_ID'];
        $quantity = $_POST['quantity'];
        
        updateCartItemQuantity($userId, $productId, $quantity);
        //header("Location: cart.php");
        //exit();
    }

    if (!empty($_POST['deleteBtn'])) {
        $userId = getUserId($_SESSION['email']);
        echo "cart.php line 25: ", $userId;
        $productId = $_POST['product_ID'];
        deleteCartItem($productId, $userId);
        //header("Location: cart.php");
        //exit();
    }
    if (!empty($_POST['checkoutBtn'])) {
        $userId = getUserId($_SESSION['email']);
        $cartItems = getCartItems($userId);
        $cc = $_SESSION['cc'];
        echo "line 35 cart.php: ", $cartItems[1];
        if (checkoutCart($userId, $cartItems, $cc)) {
            header("Location: home.php");
            exit();
        } else {
            echo "Failed to checkout. Please try again later.";
        }
        $_SESSION['cc'] = "";
    }
    if (!empty($_POST['applyCouponBtn'])) {
        // global $cc;
        $userId = getUserId($_SESSION['email']);
        $couponCode = $_POST['couponCode'];
        if(couponexists($couponCode) == 1){
            // echo "BLAH BLAH";
            if(usedcoupon($couponCode, $userId) == 1){
                echo "You've already used this coupon. Try another one.";
                $cc = "";   
            }
            else{
                $_SESSION['cc'] = $couponCode;
                // applyCouponToOrder($cc, $userId);
                echo "Coupon Applied";
            }
        }
        else{
            echo "Invalid Coupon";
        }
    }

    
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Bereket, Carlos, Emily, Natalie">
  <meta name="description" content="Your Cart">
  <meta name="keywords" content="Cavalier Candles">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>Cavalier Candles</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  

  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candle Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .filter-option {
            margin-top: 20px;
            margin-left: 20px;
        }

        .product-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .product {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            box-sizing: border-box;
            width: calc(60% - 20px);
        }
        
        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .remove-from-cart-button {
        border: 1px solid black;
        background-color: #FFB6C1;
        cursor: pointer;
        margin-left: 100px;
        background-color: #FFB6C1; 
        margin-left: 10px;
        }

        .remove-from-cart-button:hover{
            background-color: grey
        }

        .update-button {
        border: 1px solid black;
        background-color: #FFB6C1;
        cursor: pointer;
        margin-left: 100px;
        background-color: #FFB6C1; 
        margin-left: 10px;
        }

        .update-button:hover{
            background-color: grey
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            background-color: #FFB6C1;
            border: none;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .checkout-btn {
            width: 300px;
            height: 30px;
            background-color: #FFB6C1;
            border: none;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

    </style>
</head>

<body>  
    <?php include("header.php"); ?>

    
    <div class="product-container">
        <?php 
        $cartItems = getCartItems(getUserId($_SESSION['email'])); 
        foreach ($cartItems as $item) {
            ?>
            <div class="product">
                <h2><?php echo $item['Name']; ?></h2>
                <p>Price: $<?php echo $item['Price']; ?></p>
                <p>Size: <?php echo $item['size']; ?></p>
                <p>Quantity: <?php echo $item['quantity']; ?></p>
                <form method="post">
                    <input type="hidden" name="product_ID" value="<?php echo $item['product_ID']; ?>">
                    <input type="text" name="quantity" value="<?php echo $item['quantity']; ?>" readonly class="quantity-input">
                    <button type="button" onclick="increaseQuantity(this)" class="quantity-btn">+</button>
                    <button type="button" onclick="decreaseQuantity(this)" class="quantity-btn">-</button>
                    <input type="submit" name="updateBtn" value="Update Quantity" class="update-button">
                    <input type="submit" name="deleteBtn" value="Remove Item" class="remove-from-cart-button">
                </form>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="coupon-container">
    <form method="post">
        <label for="couponCode">Coupon Code:</label>
        <input type="text" id="couponCode" name="couponCode">
        <input type="submit" name="applyCouponBtn" value="Apply Coupon">
    </form>
</div>
    <div class="check-out-container">
    <form method="post" style="margin-top: 20px;">
        <input type="submit" name="checkoutBtn" value="Check Out" class="checkout-btn" style="background-color: #FFB6C1;">
    </form>
    </div>

    <script>
        function increaseQuantity(btn) {
            var input = btn.parentNode.querySelector('input[name="quantity"]');
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity(btn) {
            var input = btn.parentNode.querySelector('input[name="quantity"]');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>

    </body>

</html>
