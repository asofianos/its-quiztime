<?php
  // Initialize the session
session_start();
 
 // Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
} 

include("config.php");

// Define variables and initialize with empty values
$username = $password = $hashed_password = "";
$username_err = $password_err = "";

$log_id = "";
$log_uname = "";
$log_pass = "";

  

if ($_SERVER["REQUEST_METHOD"] == "POST") {


  $log_uname = trim($_POST["username"]);
  $log_pass = trim($_POST["password"]);

  // Check if username is empty
  if(empty($log_uname)){
    $username_err = "Please enter username.";
  } else{
    $username = $log_uname;
  }

  // Check if username is empty
  if(empty($log_pass)){
    $password_err = "Please enter password.";
  } else{
    $password = $log_pass;
  }
  
  // Validate credentials
  if (empty($username_err) && empty($password_err)) {
    // Prepare a select statement
    $sql = "SELECT COUNT(*) FROM users WHERE username = '$username'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    if ($count == 1) {
      $sql = "SELECT * FROM users WHERE username = '$username'";
      //-----------------------------------------------------------------------------------------------------
      foreach ($conn->query($sql) as $row) {
        $log_id = $row['user_id'];
        $log_username = $row['username'];
        $hashed_password = $row['password'];
      }
      //-----------------------------------------------------------------------------------------------------
      
      if (password_verify($password, $hashed_password)) {
        
        // Password is correct, so start a new session
        //session_start();
        
        // Store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $log_id;
        $_SESSION["username"] = $log_username; 
        $_SESSION["rival_id"] = 0; 
                                  
        
        // Redirect user to welcome page
        header("location: welcome.php");

      } else {
        // Display an error message if password is not valid
        $password_err = "The password you entered was not valid.";
      }

    } else {
      // Display an error message if username doesn't exist
      $username_err = "No account found with that username.";
    }
    $res = null;
    $conn = null;

  }

}
  
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
  <link rel="stylesheet" href="stylesheets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheets/css/main.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body class="bg">

  <center>
    <img id="logo-img" src="img/quiz.png" alt="...">
  </center>

  <div class="m-playground col-xs-10 col-sm-6 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
    
    <div class="q-title">
      <h4 >Login to It's Quiz Time!! <small>It's free!</small></h4>       
    </div>

    <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="UTF-8" id="loginForm">
      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
        <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>" placeholder="Username">
        <span class="help-block"><?php echo $username_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <input type="password" class="form-control" name="password" id="pw" placeholder="Password">
        <span class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block" id="myButton">Login <span class="glyphicon glyphicon-log-in"></span></button>
      </div>
    </form>
    <div class="bottom text-center">
      <p>Don’t have an account? <a href="sign_up.php">SIGN UP now!</a></p>
    </div>
  </div>

  <script type="text/javascript">
    document.getElementById("username").focus();
  </script>

</body>

</html>

<?php  ?>