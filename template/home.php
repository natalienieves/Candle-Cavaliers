<?php 

require("connect-db.php");
require("home-db.php");

$products = getAvailableProducts($db);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Bereket, Carlos, Emily, Natalie">
  <meta name="description" content="Website that sells candles">
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

        .product-details {
            display: flex;
            align-items: center;
        }

        .size-input,
        .quantity-input {
            margin-right: 20px;
        }

        .size-input select{
            
            width: 50px;
            height: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            flex-wrap: wrap
    
        }

        .quantity-input input[type="number"] {
            
            width: 50px;
            height: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            flex-wrap: wrap
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
            width: calc(33.33% - 20px)
        }
        
        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .add-to-cart-button {
        border:2px solid black;
        height: 40px;
        background-color: #FFB6C1;
        cursor: pointer;
        margin-left: 100px;
        margin-top: 30px;
        }

        .add-to-cart-button:hover{
            background-color: grey
        }

        .add-to-wishlist-button {
        border:2px solid black;
        height: 40px;
        background-color: #FFB6C1;
        cursor: pointer;
        margin-left: 100px;
        margin-top: 30px;
        }

        .add-to-wishlist-button:hover{
            background-color: grey
        }
        .review-button {
        border:2px solid black;
        height: 40px;
        background-color: #FFB6C1;
        cursor: pointer;
        margin-left: 100px;
        margin-top: 30px;
        }

        .review-button:hover{
            background-color: grey
        }
        .quantity-input {
            display: flex;
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

    </style>
</head>

<body>  
    
<?php include("header.php"); ?>


    <div class="filter-option">
        <label for="sizeFilter">Filter:</label>
        <select id="filter" name="filter" onchange="filterProducts(this.value)">
            <option value="no_filter">None</option>
            <option value="lowest_to_highest">Lowest to Highest Price</option>
            <option value="highest_to_lowest">Highest to Lowest Price</option>
            <option value="most_reviewed">Most Reviewed</option>
        </select>
    </div>
    
    <div class="product-container">
    <?php foreach ($products as $product): ?>
        <?php $uniqueId = htmlspecialchars($product['Name']) . rand(); ?>
        <div class="product">
            <h2> <h2 style="color: #FFB6C1;"><?php echo htmlspecialchars($product['Name']); ?></h2>
            <p>Price: $<span id="price-<?php echo $uniqueId; ?>"><?php echo htmlspecialchars($product['Price']); ?></span></p>
            <label for="size-<?php echo $uniqueId; ?>">Size:</label>
            <select id="size-<?php echo $uniqueId; ?>" name="size" onchange="updatedPriceFetch('<?php echo $uniqueId; ?>', this.value, '<?php echo addslashes(htmlspecialchars($product['Name'])); ?>')">
                <option value="8oz">8oz</option>
                <option value="12oz">12oz</option>
            </select>
       </div>
    <?php endforeach; ?>
</div>

<script>
function updatedPriceFetch(uniqueId, selectedSize, productName) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('price-' + uniqueId).textContent = this.responseText;
        }
    };
    xhttp.open("GET", "home-db.php?size=" + selectedSize + "&Name=" + encodeURIComponent(productName), true);
    xhttp.send();
}

displayProducts(<?php echo json_encode($products); ?>);

function filterProducts(filterOption) {
    const products = <?php echo json_encode($products); ?>;
    let filteredProducts = products.slice();
    switch(filterOption) {
        case 'lowest_to_highest':
            filteredProducts.sort((a, b) => a.Price - b.Price);
            break;
        case 'highest_to_lowest':
            filteredProducts.sort((a, b) => b.Price - a.Price);
            break;
        default:
            break;
    }

    displayProducts(filteredProducts);
}

function displayProducts(products) {
    const container = document.querySelector('.product-container');
    container.innerHTML = ''; 
    products.forEach(product => {
        const uniqueId = product.Name + Math.random();
        container.innerHTML += `
            <div class="product">
                <h2 style="color: #FFB6C1;">${product.Name}</h2>
                <p>Price: $<span id="price-${uniqueId}">${product.Price}</span></p>
               
                <div class ="product-details">
                    <div class ="size-input">
                        <label for="size-${uniqueId}">Size:</label>
                        <select id="size-${uniqueId}" name="size" onchange="updatedPriceFetch('${uniqueId}', this.value, '${product.Name}')">
                            <option value="8oz">8oz</option>
                            <option value="12oz">12oz</option>
                        </select>
                    </div>

                <div class="quantity-input">
                    <label for="quantity-${uniqueId}">Quantity: </label>
                        <button class="quantity-btn" onclick="decreaseQuantity('${uniqueId}')">-</button>
                        <input type="number" id="quantity-${uniqueId}" name="quantity" value="1">
                        <button class="quantity-btn" onclick="increaseQuantity('${uniqueId}')">+</button>
                    </div>
                </div>
                <button class="add-to-cart-button" onclick="addToCart('${product.product_ID}', document.getElementById('quantity-${uniqueId}').value)">Add to Cart</button>
                <button class="add-to-wishlist-button" onclick="addToWishlist('${product.product_ID}')">Add to Wishlist</button>
                <button class="review-button" onclick="showReviews('${product.product_ID}')">Reviews</button>

            <div id="modal-${product.product_ID}" class="w3-modal">
                <div class="w3-modal-content">
                    <header class="w3-container w3-pink">
                        <span onclick="document.getElementById('modal-${product.product_ID}').style.display='none'"
                        class="w3-button w3-display-topright">&times;</span>
                        <h2>Reviews for ${product.Name}</h2>
                    </header>
                    <div class="w3-container" id="reviews-container-${product.product_ID}">
                    </div>
                </div>
            </div>
        </div>
        `;
    })
}

function increaseQuantity(uniqueId) {
    const quantityInput = document.getElementById(`quantity-${uniqueId}`);
    quantityInput.value = parseInt(quantityInput.value) + 1;
}

function decreaseQuantity(uniqueId) {
    const quantityInput = document.getElementById(`quantity-${uniqueId}`);
    if (parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
    }
}


function showReviews(productID) {
    const modal = document.getElementById(`modal-${productID}`);
    modal.style.display = 'block'; 

    const reviewsContainer = document.getElementById(`reviews-container-${productID}`);
    reviewsContainer.innerHTML = `
        <form onsubmit="submitReview(event, '${productID}')">
            <textarea id="review-text-${productID}" required placeholder="Write your review here... " rows="4" style="width:100%;"></textarea>
            <button type="submit">Submit Review</button>
        </form>
        <div id="reviews-list-${productID}"></div>
    `;

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
            const reviews = JSON.parse(this.responseText);
            const reviewsList = document.getElementById(`reviews-list-${productID}`);
            reviewsList.innerHTML = '';
            reviews.forEach(review => {
                const reviewElement = document.createElement('div');
                reviewElement.innerHTML = `<p>${review.name}: ${review.usercomment} (Posted on: ${review.comment_date})</p>`;
                reviewsList.appendChild(reviewElement);
            });
        }
    };
    xhttp.open("GET", "home-db.php?product_ID=" + productID, true);
    xhttp.send();
}

function submitReview(event, productID) {
    console.log("home.php line 284: in submitReview Func!!!!");
    event.preventDefault();
    const reviewText = document.getElementById(`review-text-${productID}`).value;
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
            showReviews(productID); 
        }
    };
    xhttp.open("POST", "submit-review.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`product_ID=${productID}&usercomment=${encodeURIComponent(reviewText)}`);
}


function addToCart(product_ID, quantity){
    const email = "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>";
    const user_ID = "<?php echo getUserId(isset($_SESSION['email']) ? $_SESSION['email'] : ''); ?>";
    console.log("line 243 home.php - email: " + email);
    console.log("Product ID: " + product_ID);
    console.log("Quantity: " + quantity);
    console.log("User ID: " + user_ID);

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = this.responseText;
            if( response == "success"){
                alert("Item Added to Cart!");
            }
        };
    };
    xhttp.open("GET", "home-db.php?product_ID="+ product_ID + "&quantity=" + quantity + "&user_ID=" + user_ID, true);
    xhttp.send();
   
}

function addToWishlist(product_ID){
    const email = "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>";
    const user_ID = "<?php echo getUserId(isset($_SESSION['email']) ? $_SESSION['email'] : ''); ?>";
    const add = "True";
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = this.responseText;
            if( response == "success"){
                alert("Item Added to Wishlist!");
            }
        };
    };
    xhttp.open("GET", "home-db.php?product_ID="+ product_ID + "&user_ID=" + user_ID + "&add=" + add, true);
    xhttp.send();
}

</script>
<?php 
//include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>