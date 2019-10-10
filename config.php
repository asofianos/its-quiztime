<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */



$servername = "localhost";
$username = "your_db_user";
$password = "your_password";
$db_name= "your_db_name";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
  

  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $GLOBALS['conn'] = $conn;


  //echo "Connected successfully"; 
}
catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}

?>