<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}

$_SESSION["duel_id_to_complete"] = 0;
$_SESSION["rival_id"] = 0;//rival id
$_SESSION["rival_name"] = '';
$_SESSION["cat_id"] = 0;
$_SESSION["user_score"] = 0;

$u_id = $_SESSION["id"];
include("config.php");
// Pending duels (you have to answer)
$as_rival_sql = "SELECT COUNT(*) FROM duels WHERE user_id = '$u_id' AND duel_status = 'pending' ";
  
// Pending duels (rival have to answer)
$i_have_caused_sql = "SELECT COUNT(*) FROM duels WHERE rival_id = '$u_id' AND duel_status = 'pending' ";

$stmt = $conn->prepare($as_rival_sql);
$stmt->execute();
$count = $stmt->fetchColumn();

$stmt2 = $conn->prepare($i_have_caused_sql);
$stmt2->execute();
$count2 = $stmt2->fetchColumn();

$notifications = $count2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Welcome</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
  <link rel="stylesheet" href="stylesheets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheets/css/main.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

  <center>
    <img id="logo-img" src="img/quiz.png" alt="...">
  </center>
  
  <div class="m-playground col-xs-10 col-sm-6 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">   
  
    <div class="q-title">
      <h4>Welcome to our quiz <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>!</h4>       
    </div>
        
    <p><a href="categories.php" class="btn btn-primary btn-block">Start the quiz <span class="glyphicon glyphicon-play-circle"></span></a></p>
    <p><a href="players.php" class="btn btn-default btn-block">Ranked Players <span class="glyphicon glyphicon-screenshot"></span></a></p>
    <p><a href="notifications.php" class="btn btn-default btn-block"> Duel Notifications <span class="glyphicon glyphicon-bell"></span><span class="badge"><?php if ($notifications > 0) echo $notifications; ?></span></a></p>
    <p><a href="reset-password.php" class="btn btn-warning btn-block">Reset Your Password <span class="glyphicon glyphicon-edit"></span></a></p>
    <p><a href="logout.php" class="btn btn-danger btn-block">Sign Out of Your Account <span class="glyphicon glyphicon-log-out"></span></a></p>         
  </div>
        
 
</body>


</html>
