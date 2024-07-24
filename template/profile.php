<?php
session_start();
require("connect-db.php");
require("profile-db.php");

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); 
    exit();
}

$currentUserInfo = getUserInfoByEmail($_SESSION['email']);
$user_ID = $currentUserInfo['user_ID']; 
$orderHistory = ViewOrderHistory($user_ID);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password']; 
  $address = $_POST['address'];
  $age = $_POST['age'];

  // Update user information
  updateUserInfo($user_ID, $name, $email, $password, $address, $age);

  
    // Delete User Account
  if (!empty($_POST['deleteBtn'])) {
    echo "user ID: " . $user_ID;
    deleteUserAccount($user_ID);
    session_destroy();
    header('Location: home.php');
  }
  
}
//Get user info  from the database
$userInfo = getUserInfoByEmail($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile - Cavalier Candles</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px;
            padding: 0px;
            background-color: #f4f4f4;
        }
  </style>
</head>

<body>  
    <?php include("header.php"); ?>

    <div class="container">
        <div class="profile-box">
          <h2>User Profile</h2>
          <form action="profile.php" method="post">
            <table class="table">
                <tr>
                  <td>Name</td>
                  <td><input type="text" name="name" value="<?php echo htmlspecialchars($userInfo['name']); ?>"></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><input type="email" name="email" value="<?php echo htmlspecialchars($userInfo['email']); ?>"></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td>
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($userInfo['password']); ?>">
                    <button type="button" onclick="togglePasswordVisibility()">Show</button>
                  </td>
                </tr>
                <tr>
                  <td>Address</td>
                  <td><input type="text" name="address" value="<?php echo htmlspecialchars($userInfo['address']); ?>"></td>
                </tr>
                <tr>
                  <td>Age</td>
                  <td><input type="number" name="age" value="<?php echo htmlspecialchars($userInfo['age']); ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit">Save Changes</button>
                    </td>
                </tr>
            </table>
          </form>
            <form method="post">
                <button type="submit" name="deleteBtn" value="Delete Account" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
            </form>
              
        </div>
    </div>


    <?php if (!empty($orderHistory)): ?>
    <div class="container">
        <div class="order-history">
            <h2>Order History</h2>
            <table class="table">
                <thead>
                    <tr>
                        
                        <th>Order ID</th>
                        <th>Order Price</th>
                        <th>Order Size</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderHistory as $order): ?>
                    <tr>
                        <td><?php echo $order['order_ID']; ?></td>
                        <td><?php echo $order['discounted_price']; ?></td>
                        <td><?php echo $order['order_size']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>


    <script>
      function togglePasswordVisibility() {
        var x = document.getElementById("password");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
      }
   
    </script>
</body>
</html>
