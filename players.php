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
  $rival_player_id = $_POST["rival_id"];
  $rival_player_name = $_POST["rival_name"];

  // Store data in session variables
  $_SESSION["rival_id"] = $rival_player_id;
  $_SESSION["rival_name"] = $rival_player_name;
  header("location: categories.php"); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Players</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
  <link rel="stylesheet" href="stylesheets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheets/css/main.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

  <center>
    <img id="logo-img" src="img/quiz.png" alt="...">
  </center>


  
  <div class="m-playground col-xs-10 col-sm-8 col-md-6 col-lg-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3 col-lg-offset-3"> 
    <div class="q-title">
      <h4>All Ranked Players</h4>       
    </div>
    
      <div class="table-responsive">
        <table class="table table-hover table-bordered" ><!-- Table -->
          <p>All players in the game with ranked score, personal score, wins, draws, defeats!</p>
          <p>Choose a player you want to chalenge wisely!</p>
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">RS</th>
              <th scope="col">PS</th>
              <th scope="col">Wins</th>
              <th scope="col">Draws</th>
              <th scope="col">Loses</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
  <?php  

  // Display all the players from the database
  include("config.php");
  $name = $_SESSION["username"];

  $sql = "SELECT COUNT(*) FROM users ";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $count = $stmt->fetchColumn();
  if ($count >= 1) {
    $sql = "SELECT * FROM users ORDER BY wins DESC, ranked_score DESC";
    $rank = 0;
    foreach ($conn->query($sql) as $row) {
    $rank++;
    ?>
            <tr>
              <td data-label="# Rank"><b><?php echo $rank; ?></b></td>
              <td data-label="Name"><?php echo $row["username"]; ?></td>
              <td data-label="Ranked Score"><b><?php echo $row["ranked_score"]; ?></b></td>
              <td data-label="Personal Score"><?php echo $row["personal_score"]; ?></td>
              <td data-label="Wins"><b><?php echo $row["wins"]; ?></b></td>
              <td data-label="Draws"><?php echo $row["draws"]; ?></td></td>
              <td data-label="Loses"><?php echo $row["loses"]; ?></td>
              <td data-label="Action">
                <?php if( $row["user_id"] != $_SESSION["id"]) {  ?>
                  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="rival_id" value="<?php echo $row["user_id"]; ?>">
                    <input type="hidden" name="rival_name" value="<?php echo $row["username"]; ?>">
                    <button type="submit" class="btn btn-sm btn-danger" ><span class="glyphicon glyphicon-screenshot"></span></button>
                  </form>
                <?php
                }
                
                ?>
              </td>
            </tr> 
<?php 
    }
    
  }

?>
          </tbody>
        </table>
      </div>
              
            
            
      <a href="welcome.php" class="btn back-btn btn-warning " ><span class="glyphicon glyphicon-menu-left"></span> Main menu</a>
    </div>
  </div>
</div>  

</body>

</html>
