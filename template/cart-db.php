<?php 
require 'connect-db.php';

function debug_to_console($data) {
  $output = $data;
  if (is_array($output))
      $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function getUserId($email){
    global $db;
    $query = "SELECT user_ID FROM UserInfo WHERE email = :email";
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    return $result['user_ID'];
}

function getCartItems($userId){
  global $db;
  // $query = "WITH userCart AS(SELECT * FROM CartItem WHERE user_ID = :user_ID) SELECT size, Price, Name, pic, quantity FROM userCart NATURAL JOIN Product";
  $query = "SELECT Product.product_ID, Product.size, Product.Price, Product.Name, CartItem.quantity  FROM CartItem NATURAL JOIN Product WHERE user_ID = :user_ID";
  $statement = $db->prepare($query);
  $statement->bindValue(':user_ID', $userId);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();

  return $result;
}

function updateCartItemQuantity($userId, $productId, $quantity){
  global $db;
  $query = "UPDATE CartItem SET quantity = :quantity WHERE user_ID = :user_ID AND product_ID = :product_ID";

  $statement = $db->prepare($query);
  $statement->bindValue(':quantity', $quantity);
  $statement->bindValue(':user_ID', $userId);
  $statement->bindValue(':product_ID', $productId);
  $statement->execute();
  $statement->closeCursor();

}

function deleteCartItem($productId, $userId){
  global $db;
  $query = "DELETE FROM CartItem WHERE user_ID = :user_ID AND product_ID = :product_ID";
  $statement = $db->prepare($query);
  $statement->bindValue(':product_ID', $productId);
  $statement->bindValue(':user_ID', $userId);
  $success = $statement->execute();
  $statement->closeCursor();

  return $success;

}

function checkoutCart($userId, $cartItems, $cc){
   
    global $db;
    $db->beginTransaction();
   
  
    try {
      $totalprice = 0;
      $orderSize = 0;
  
      // Iterate through each item in the cart
      foreach ($cartItems as $item) {
          $productId = $item['product_ID'];
          $quantity = $item['quantity'];
  
          // Fetch product price from the products table
          $query = "SELECT price FROM Product WHERE product_ID = :product_ID";
          $statement = $db->prepare($query);
          $statement->bindValue(':product_ID', $productId);
          $statement->execute();
          $product = $statement->fetch(PDO::FETCH_ASSOC);
  
          // Calculate total price and order size
          $itemPrice = $product['price'] * $quantity;
          $totalprice += $itemPrice;
          $orderSize += $quantity;
      }

      
      // ------IF COUPON IS PRESENT-----
      if (($cc != "")){
        $query = "SELECT value FROM Coupon WHERE coupon_ID = :couponCode";
        $statement = $db->prepare($query);
        $statement->bindValue(':couponCode', $cc);
        $statement->execute();
        $discount = $statement->fetch(PDO::FETCH_ASSOC);
      
        $value = var_dump($discount["value"]);
        
        $floatDiscount = floatval($discount["value"]);
        $query = "INSERT INTO OrderHistory (user_ID, order_price, discounted_price, order_size, order_date) VALUES (:user_ID, :order_price, :discounted_price, :order_size, :order_date)";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':user_ID', $userId);
        $statement->bindValue(':order_price', $totalprice);
        $statement->bindValue(':discounted_price',$totalprice - ($totalprice*$floatDiscount)); ## fix this when coupon implemented
        $statement->bindValue(':order_size', $orderSize);
        $statement->bindValue(':order_date', date('Y-m-d H:i:s'));
        $statement->execute();
        $statement->closeCursor();
        
        $query = "CALL InsertCartItemsIntoOrder(:user_ID_param)";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_ID_param', $userId);
        $statement->execute();
  
      
  
        $query = "INSERT INTO Applied (coupon_ID, user_ID) VALUES (:couponCode, :user_ID)";

        $statement = $db->prepare($query);
        $statement->bindValue(':couponCode', $cc);
        $statement->bindValue(':user_ID', $userId);
        $statement->execute();
        $statement->closeCursor();

      // ------IF NO COUPON IS PRESENT-----
      } else{
        // $discount = 0;
        // debug_to_console($cc);
        $query = "INSERT INTO OrderHistory (user_ID, order_price, discounted_price, order_size, order_date) VALUES (:user_ID, :order_price, :discounted_price, :order_size, :order_date)";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_ID', $userId);
        $statement->bindValue(':order_price', $totalprice);
        $statement->bindValue(':discounted_price',$totalprice); 
        $statement->bindValue(':order_size', $orderSize);
        $statement->bindValue(':order_date', date('Y-m-d H:i:s'));
        $statement->execute();
        $statement->closeCursor();
        
        $query = "CALL InsertCartItemsIntoOrder(:user_ID_param)";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_ID_param', $userId);
        $statement->execute();
  
      }
     
      $db->commit();
      return true;

    }catch (Exception $e) {
      $db->rollback();
      error_log('Transaction failed: ' . $e->getMessage());
      return false;
    }
  
}

function clearCart($userId){
  global $db;
  $query = "DELETE FROM CartItem WHERE user_ID = :user_ID";
  $statement = $db->prepare($query);
  $statement->bindValue(':user_ID', $userId);
  $statement->execute();
  $statement->closeCursor();
}

function usedCoupon($couponCode, $userId){
  global $db;
  $query = "SELECT COUNT(*) FROM Applied WHERE coupon_ID = :couponCode AND user_ID = :userID";
  
  $statement = $db->prepare($query);
  $statement->bindValue(':couponCode', $couponCode);
  $statement->bindValue(':userID', $userId);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
  
  return $result[0];
}

function couponexists($couponCode){
  global $db;
  $query = "SELECT COUNT(*) FROM Coupon WHERE coupon_ID = :couponCode";
  
  $statement = $db->prepare($query);
  $statement->bindValue(':couponCode', $couponCode);
  $statement->execute();
  $result = $statement->fetch();
  $statement->closeCursor();
 

  return $result[0];

}

?>