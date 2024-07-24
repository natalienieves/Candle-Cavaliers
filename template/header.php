<?php
session_start(); 

$email = $_SESSION['email'];
$emailParts = explode('@', $email);
$user = $emailParts[0];
$hostname = 'portal.cs.virginia.edu';

if ($db) {
    if ($emailParts[1] == "virginia.edu") {
        $userMySQLUsername = $user;

        try {
            $sql = "GRANT INSERT ON cfr5spw.Product TO '$userMySQLUsername'@'%'";
            $stmt = $db->prepare($sql);
            $stmt->execute();

            $addProductButton = "<li class='nav-item'><a class='nav-link' href='#' data-bs-toggle='modal' data-bs-target='#addProductModal'>Add Product</a></li>";
        } catch (PDOException $e) {
        }
    } 
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email'])) {  
    $productName = $_POST['productName'];
    $productPrice = number_format($_POST['productPrice'], 2, '.', '');
    $productSize = $_POST['productSize'];
    $productStock = $_POST['productStock'];

    $query = "INSERT INTO Product (Product.size, Price, Product.Name, Stock, pic) VALUES (:productSize, :productPrice, :productName, :productStock, null)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':productName', $productName);
    $stmt->bindValue(':productPrice', $productPrice);
    $stmt->bindValue(':productSize', $productSize);
    $stmt->bindValue(':productStock', $productStock);
    $stmt->execute();
    
    // if ($stmt->execute()) {
    //     header("Location: home.php");
    // } 
}
?>

<header>  
    <nav class="navbar navbar-expand-md bannercolor">
        <div class="container-fluid">            
            <a class="navbar-brand" href="home.php">Cavalier Candles</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav ms-auto">
                    <?php if (!isset($_SESSION['email'])) { ?>              
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Log in/ Sign up</a>
                        </li>              
                    <?php  } else { ?>      
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php"> Profile </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">Cart</a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="wishlist.php">Wishlist</a>
                        </li> 
                        <?php echo isset($addProductButton) ? $addProductButton : ''; ?>
                        <li class="nav-item">                  
                            <a class="nav-link" href="signout.php">Sign out</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>    

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" method="post">
                    <div class="mb-3">
                        <label for="productName">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice">Price</label>
                        <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                    </div>
                    <div class="mb-3">
                        <label for="productSize">Size</label>
                        <select class="form-select" id="productSize" name="productSize" required>
                            <option value="8oz">8oz</option>
                            <option value="12oz">12oz</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="productStock">Stock</label>
                        <input type="number" class="form-control" id="productStock" name="productStock" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Add</button>
                </form>
                <div id="addProductMessage"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .bannercolor {
        background-color: #FFB6C1; 
    }
    .navbar-brand,
    .navbar-nav .nav-link {
        color: #fff; 
        font-weight: bold; 
    }
</style>
