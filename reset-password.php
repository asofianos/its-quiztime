<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
include("config.php");
 
// Define variables and initialize with empty values
$new_password = $confirm_password = $old_password ="";
$new_password_err = $confirm_password_err = $old_password_err ="";

$log_id = "";
$log_uname = "";
$log_pass = "";
$u_id = $_SESSION["id"];

try {

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $log_old_pass = trim($_POST["old_password"]);
    $log_new_pass = trim($_POST["new_password"]);
    $log_confirm_pass = trim($_POST["confirm_password"]);
    
    // Validate old password
    if(empty($log_old_pass)){
      $old_password_err = "Please enter the old password.";     
    } elseif(strlen($log_old_pass) < 4 || strlen($log_old_pass) > 8){
      $old_password_err = "Password must be betwean 4 and 8 characters long.";
    } else{
      $old_password = $log_old_pass;
    }

    // Validate new password
    if(empty($log_new_pass)){
      $new_password_err = "Please enter the new password.";     
    } elseif(strlen($log_new_pass) < 4 || strlen($log_new_pass) > 8){
      $new_password_err = "Password must be betwean 4 and 8 characters long.";
    } elseif($log_new_pass == $log_old_pass){
      $new_password_err = "New password must not be the same with the old one.";
    } else{
      $new_password = $log_new_pass;
    }
    
    // Validate confirm password
    if(empty($log_confirm_pass)){
        $confirm_password_err = "Please confirm password.";   
    } else {
      $confirm_password = $log_confirm_pass;
      if(empty($new_password_err) && ($new_password != $confirm_password)){
        $confirm_password_err = "Password did not match.";
      }
    }

    $test_pass ="";

    if (empty($old_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
      
      $sql = "SELECT password FROM users WHERE user_id = '$u_id' ";
      
      
      $res = $conn->query($sql);
      
      
      if ($row = $res->fetch()) {
        $test_pass = $row['password'];
        
        if (password_verify($log_old_pass, $test_pass)) {
          // The old password is the same so continue with the new password

          $new_hashed_pass = password_hash($new_password, PASSWORD_DEFAULT); 
          $sql = "UPDATE users SET password = '$new_hashed_pass' WHERE user_id = '$u_id' ";
          $stmt = $conn->prepare($sql);

          if($stmt->execute()){
            // Redirect to login page
            session_destroy();
            header("location: index.php");
            exit();
          } else{
            echo "Something went wrong. Please try again later.";
          }

          $stmt = null;
          $res = null;
          $conn = null;

        }else {
          $old_password_err = "The old password did not match.";
        }

      }

    }
    

  }
  
} catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
}


?>
 
<!DOCTYPE html>
<html lang="en">

<head>  
  <title>Reset Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="stylesheets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
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
      <h3 class="panel-title">Fill out this form to reset your password.</h3>
    </div>
      <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="UTF-8">
        <div class="form-group <?php echo (!empty($old_password_err)) ? 'has-error' : ''; ?>">
          <input type="password" class="form-control" name="old_password" value="<?php echo $old_password; ?>" placeholder="Old Password">
          <span class="help-block"><?php echo $old_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
          <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>" placeholder="New Password">
          <span class="help-block"><?php echo $new_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
          <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
          <button type="submit" class="btn back-btn btn-primary btn-block">Submit</button>
        </div>             
      </form>
          
      <div class="bottom text-center">
        
        <p><a href="welcome.php" class="btn  btn-warning " ><span class="glyphicon glyphicon-menu-left"></span> Main menu</a></p>
        <p><a href="logout.php" class="btn  btn-danger">Sign Out of Your Account <span class="glyphicon glyphicon-log-out"></span> </a></p>
    </div>
</div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</body>

</html>