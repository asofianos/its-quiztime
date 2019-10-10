<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //The player choose a category
  $log_cat_name = trim($_POST["cat_name"]);
  $log_cat_id = $_POST["cat_id"];

  //Store data in session variables
  $_SESSION["cat_id"] = $log_cat_id;
  $_SESSION["category"] = $log_cat_name;
  header("location: playground.php"); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Categories</title>
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
            
    
          
	
    <?php 
      if ($_SESSION["rival_id"] == 0) {
          ?>
        <h3 class="panel-title">Which category you want to play <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>?</h3>
        <?php  
      }else {
          ?>
        <h3 class="panel-title">Which category you want to play against <b><?php echo htmlspecialchars($_SESSION["rival_name"]); ?></b>?</h3>
        <?php 
      }
    ?>
		<h4>Choose wisely and have fun!</h4>
    </div>
    
    

<?php  

//Display all the categories from the database
include("config.php");
$sql = "SELECT COUNT(*) FROM categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$count = $stmt->fetchColumn();
if ($count >= 1) {
  $sql = "SELECT * FROM categories ";
  foreach ($conn->query($sql) as $row) {
    
?>
    <div class="">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">                          
        <div class="form-group">
          <input type="hidden" name="cat_id" value="<?php echo $row['cat_id'] ?>">
          <input type="submit" class=" btn btn-primary btn-block " name="cat_name" value="<?php echo $row['cat_name'] ?>">
        </div> 
      </form>
    </div>
<?php 
  }
}
?>
  
    <a href="welcome.php" class="btn back-btn btn-warning " ><span class="glyphicon glyphicon-menu-left"></span> Main menu</a>
  </div>

</body>


</html>
