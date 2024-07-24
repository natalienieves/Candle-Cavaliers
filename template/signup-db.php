<?php 
function addAccount($email, $password, $name, $address, $age){
    global $db;
    $query = "INSERT INTO UserInfo (email, password, name, address, age) VALUES (:email, :password, :name, :address, :age)";
    
    try{ 

        $statement = $db->prepare($query);

        //fill in the value
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':address', $address);
        $statement->bindValue(':age', $age);

        // execute
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e)
    {
        echo "error". $e->getMessage();
        die();
    } catch (Exception $e)
    {
      echo "error". $e->getMessage();
      die();
    }
}

?>
