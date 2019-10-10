<?php  
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}else if ($_SESSION["cat_id"] == 0 ) {
  header("location: welcome.php");
  exit;  
}

include("config.php");
include("game.php");
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Playground</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >  
  <link rel="stylesheet" type="text/css" href="stylesheets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="stylesheets/css/main.css">

  <link rel="stylesheet" href="stylesheets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheets/css/main.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body onload="setQuiz()">

  <center>
    
    <img id="logo-img" src="img/quiz.png" alt="...">

    <div class="">
      <div class="m-playground col-xs-10 col-sm-8 col-md-8 col-lg-6 col-lg-offset-3 col-md-offset-2 col-sm-offset-2 col-xs-offset-1">

        <div class="q-title">
          <h4 ><b><?php echo htmlspecialchars($_SESSION["category"]); ?></b> <b id="turns"></b></h4>
          <h4 ><b id="questionTitle"></b></h4>
        </div>

        <button id="ans1" name="ans" class="btn btn-lg btn-primary col-xs-6 col-sm-5 col-md-5 col-lg-5 " onclick="results(1)" ></button>
        <button id="ans2" name="ans" class="btn btn-lg btn-primary col-xs-6 col-sm-5 col-md-5 col-lg-5 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 " onclick="results(2)"></button>
        
        <div id="gly">
          <span class="glyphicon glyphicon-time" style="font-size: 35px;"></span>
          <span id="countdown" style="font-size: 40px;">15</span>
        </div> 
        <button id="ans3" name="ans" class="btn btn-lg btn-primary col-xs-6 col-sm-5 col-md-5 col-lg-5 " onclick="results(3)" ></button>
        <button id="ans4" name="ans" class="btn btn-lg btn-primary col-xs-6 col-sm-5 col-md-5 col-lg-5 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 " onclick="results(4)"></button>
        <div id="msg-div" style="visibility: hidden;">
          
          <p id="msg"></p>

          <button id="nextButton" type="button" class="btn back-btn btn-primary  pull-right" ><span class="glyphicon glyphicon-menu-right"></span></button>
        </div>
        <a href="welcome.php" class="btn back-btn btn-warning pull-left" ><span class="glyphicon glyphicon-menu-left"></span> Main menu</a>
      </div>

    </div>
               
  </center>
</body>

</html>
