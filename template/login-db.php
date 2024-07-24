<?php
// require("connect-db.php");

function checkUserCredentials($email, $password) {
    global $db;
    $query = "SELECT * FROM UserInfo WHERE email = :email AND password = :password";

    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $password);
        $statement->execute();
        $user = $statement->fetch();
        $statement->closeCursor();
        
        if ($user) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false; 
    }
}

?>
