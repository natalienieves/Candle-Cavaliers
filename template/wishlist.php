<?php
session_start();
require("connect-db.php"); 
require("wishlist-db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
//   echo "line 7: in if statement wishlist.php";
    if (!empty($_POST['addBtn'])) {
        $userId = getUserId($_SESSION['email']);
        $productId = $_POST['product_ID'];
        InsertToCart($productId, $userId);
        //header("Location: cart.php");
        //exit();
    }

    if (!empty($_POST['deleteBtn'])) {
        $userId = getUserId($_SESSION['email']);
        $productId = $_POST['product_ID'];
        deleteWishlistItem($productId, $userId);
        //header("Location: cart.php");
        //exit();
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
  <meta name="description" content="Your Wishlist">
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

        .remove-from-wishlist-button {
        border: 1px solid black;
        background-color: #FFB6C1;
        cursor: pointer;
        margin-left: 100px;
        background-color: #FFB6C1; 
        margin-left: 10px;
        }

        .remove-from-wishlist-button:hover{
            background-color: grey
        }

        .add-button {
        border: 1px solid black;
        background-color: #FFB6C1;
        cursor: pointer;
        margin-left: 100px;
        background-color: #FFB6C1; 
        margin-left: 10px;
        }

        .add-button:hover{
            background-color: grey
        }

    </style>
</head>

<body>  
    <?php include("header.php"); ?>

    <div class="product-container">
        <?php 
        $WishlistItems = getWishlistItems(getUserId($_SESSION['email'])); 
        foreach ($WishlistItems as $item) {
            ?>
            <div class="product">
                <h2><?php echo $item['Name']; ?></h2>
                <p>Price: $<?php echo $item['Price']; ?></p>
                <p>Size: <?php echo $item['size']; ?></p>
                <form action="wishlist.php" method="post">
                    <input type="hidden" name="product_ID" value="<?php echo $item['product_ID']; ?>">
                    <input type="submit" name="addBtn" id="addBtn" value="Add to Cart" class="add-button">
                </form>
                <form action="wishlist.php" method="post">
                    <input type="hidden" name="product_ID" value="<?php echo $item['product_ID']; ?>">
                    <input type="submit" name="deleteBtn" value="Remove Item" class="remove-from-wishlist-button">
                </form>
            </div>
            <?php
        }
        ?>
    </div>

</body>

</html>
