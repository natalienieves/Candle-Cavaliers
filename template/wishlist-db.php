<?php
require 'connect-db.php'; 


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

function getWishlistItems($userId){
  global $db;
  $query = "SELECT Product.product_ID, Product.size, Product.Price, Product.Name FROM Wishlist NATURAL JOIN Product WHERE user_ID = :user_ID";
  $statement = $db->prepare($query);
  $statement->bindValue(':user_ID', $userId);
  $statement->execute();
  $result = $statement->fetchAll();
  $statement->closeCursor();

  return $result;
}


function deleteWishlistItem($productId, $userId){
  global $db;
  $query = "DELETE FROM Wishlist WHERE user_ID = :user_ID AND product_ID = :product_ID";
  $statement = $db->prepare($query);
  $statement->bindValue(':product_ID', $productId);
  $statement->bindValue(':user_ID', $userId);
  $success = $statement->execute();
  $statement->closeCursor();

  return $success;

}
function InsertToCart($product_ID, $user_ID){
  global $db;
  $query = "INSERT INTO CartItem (product_ID, user_ID, quantity) VALUES (:product_ID, :user_ID, 1)";
 
  try{ 
    $statement = $db->prepare($query);
    $statement->bindValue(':product_ID', $product_ID);
    $statement->bindValue(':user_ID', $user_ID);
    $success = $statement->execute();
    $statement->closeCursor();
  } catch (PDOException $e)
  {
      $e->getMessage();
  } catch (Exception $e)
  {
      $e->getMessage();
  }
  }

?>
