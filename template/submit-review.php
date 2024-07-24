<?php
require 'connect-db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email'])) {  // Check if email is set in the session
    $product_ID = $_POST['product_ID'];
    $usercomment = $_POST['usercomment'];
    $email = $_SESSION['email'];  // Fetch email from session

    // Fetch the user ID from the database using the email
    $query = "SELECT user_ID FROM UserInfo WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if ($result) {
        $user_ID = $result['user_ID'];

        // Insert the review into the database
        $insertQuery = "INSERT INTO Reviews (product_ID, user_ID, comment_date, usercomment) VALUES (:product_ID, :user_ID, NOW(), :usercomment)";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bindValue(':product_ID', $product_ID);
        $insertStmt->bindValue(':user_ID', $user_ID);
        $insertStmt->bindValue(':usercomment', $usercomment);
        $insertStmt->execute();
        $insertStmt->closeCursor();

        echo "Review submitted successfully.";
    } else {
        echo "User not found.";
    }
}
?>
