<?php 
// Remember to start the database server (or GCP SQL instance) before trying to connect to it
 
$envFilePath = __DIR__ . '/../.env';
if (file_exists($envFilePath)) {
    $envVariables = parse_ini_file($envFilePath);
    $username = $envVariables['username'];
    $password = $envVariables['password'];
    $host = $envVariables['host'];
    $dbname = $envVariables['dbname'];
    $dsn = "mysql:host=$host;dbname=$dbname";
} else {
    echo "<p>.env file not found.</p>";
    exit;
}
////////////////////////////////////////////

/** connect to the database **/
try 
{
   $db = new PDO($dsn, $username, $password);
   
   // display a message to let us know that we are connected to the database 
   // echo "<p>You are connected to the database -- host=$host</p>";
}
catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
{
   $error_message = $e->getMessage();        
   echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e)       // handle any type of exception
{
   $error_message = $e->getMessage();
   echo "<p>Error message: $error_message </p>";
}

?>