<?php
require("connect-db.php");



function getUserInfoByEmail($email) {
    global $db;
    $query = "SELECT * FROM UserInfo WHERE email = :email";
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $userInfo;
}

function updateUserInfo($user_ID, $name, $email, $password, $address, $age) {
  global $db;
  $query = "UPDATE UserInfo SET name = :name, email = :email, password = :password, address = :address, age = :age WHERE user_ID = :user_ID";
  $statement = $db->prepare($query);
  $statement->bindValue(':user_ID', $user_ID);
  $statement->bindValue(':name', $name);
  $statement->bindValue(':email', $email);
  $statement->bindValue(':password', $password);
  $statement->bindValue(':address', $address);
  $statement->bindValue(':age', $age);
  $statement->execute();
  $statement->closeCursor();
}

function deleteUserAccount($user_ID){
  global $db;
  $query = "DELETE FROM UserInfo WHERE user_ID = :user_ID";
  $statement = $db->prepare($query);
  $statement->bindValue(':user_ID', $user_ID);
  $statement->execute();
  $statement->closeCursor();
}

function ViewOrderHistory($user_ID){
  global $db;
  $query = "SELECT order_ID, discounted_price, order_size, order_date FROM OrderHistory WHERE user_ID = :user_ID";

  $statement = $db->prepare($query);
  $statement->bindValue(':user_ID', $user_ID);
  $statement->execute();
  $orderHistory = $statement->fetchAll(PDO::FETCH_ASSOC);
  $statement->closeCursor();
  
  return $orderHistory;
}

?>