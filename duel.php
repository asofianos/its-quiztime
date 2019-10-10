<?php 
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// The player choose a gategory
  $rival_player_id = $_POST["rival_id"]);

	// Store data in session variables
  $_SESSION["rival_id"] = $rival_player_id;
  header("location: playground.php"); 
}




?>