<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}



include("config.php");
  $u_name = $_SESSION["username"];
  $u_id = $_SESSION["id"];

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // The player choose to answer a duel 
    $log_duel_id_to_comlpete = $_POST["duel_id"];
    $log_duel_cat_id = $_POST["duel_cat_id"];
    $log_duel_cat_name = $_POST["duel_cat_name"];
    $log_duel_rival_id = $_POST["rival_id"];
    $log_duel_rival_name = $_POST["rival_name"];
    $log_duel_user_score = $_POST["duel_user_score"];


    // Store data in session variables
    $_SESSION["duel_id_to_complete"] = $log_duel_id_to_comlpete;
    $_SESSION["cat_id"] = $log_duel_cat_id;
    $_SESSION["category"] = $log_duel_cat_name;
    $_SESSION["rival_id"] = $log_duel_rival_id;
    $_SESSION["rival_name"] = $log_duel_rival_name;
    $_SESSION["user_score"] = $log_duel_user_score;

    header("location: playground.php"); 
  }
  // Completed Duels History(i have chalenge)
  $user_duels_f_sql = "SELECT COUNT(*) FROM duels WHERE user_id = '$u_id' AND duel_status = 'finished' ";

  // Completed Duels History(rivals that chalenge you)
  $user_duels_as_rival_f_sql = "SELECT COUNT(*) FROM duels WHERE rival_id = '$u_id' AND duel_status = 'finished' ";

  // Pending duels (rival have to answer)
  $as_rival_sql = "SELECT COUNT(*) FROM duels WHERE user_id = '$u_id' AND duel_status = 'pending' ";
  
  // Pending duels (you have to answer)
  $i_have_caused_sql = "SELECT COUNT(*) FROM duels WHERE rival_id = '$u_id' AND duel_status = 'pending' ";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Notifications</title>
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
    <div class="">
      <div class="q-title">
        <h4><?php echo "History of your duels ".$u_name;; ?></h4>
      </div>
      <div class="">  

<?php  
  $stmt = $conn->prepare($user_duels_f_sql);
  $stmt->execute();
  $count = $stmt->fetchColumn();
  ?>

<!-- Start  colapse panel 1 -->
  <h4>Completed Duels History(i have chalenge)</h4>
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo1">Show / Hide Duels <span class="badge"><?php echo $count; ?></span></button>

  <div id="demo1" class="collapse">
<?php  
  

  if ($count >= 1) {

?>
        <div class="panel panel-primary ">
          <div class="panel-heading"><center>Completed Duels History(i have chalenge)</center></div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered" ><!-- Table -->
              <thead>
                <tr>
                  <th scope="col">Rival</th>
                  <th scope="col">Score(You)</th>
                  <th scope="col">Winner</th>
                  <th scope="col">Category</th>
                  <th scope="col">Time</th>
                </tr>
              </thead>
              <tbody>
<?php  
  $sql = "SELECT duels.rival_id ,users.username ,duels.user_score, duels.rival_score, duels.duel_date, categories.cat_id, categories.cat_name 
  FROM duels
  INNER JOIN users ON duels.rival_id = users.user_id AND duels.duel_status = 'finished' AND duels.user_id = '$u_id'
  INNER JOIN categories ON duels.cat_id = categories.cat_id";
  foreach ($conn->query($sql) as $row) {
  
?>
                <tr>
                  <td data-label="Rival"><?php echo $row["username"]; ?></td>
                  <td data-label="Score(You)"><?php echo $row["rival_score"]." (".$row["user_score"].")"; ?></td>
                  <td data-label="Winner"><?php 
                  if ($row["user_score"]>$row["rival_score"]) echo $u_name;
                  elseif ($row["user_score"]<$row["rival_score"]) echo $row["username"];
                  else echo "(Draw)";
                  ?></td>
                  <td data-label="Category"><?php echo $row["cat_name"]; ?></td>
                  <td data-label="Time"><?php echo $row["duel_date"]; ?></td>
                </tr> 
<?php 
    }  
  }else {
    ?>
    <h4><?php echo "You Dont have any completed duels as rival"; ?></h4> 
    <?php  
  }
?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- end colapse panel 1 -->

<!-- start  colapse panel 2 -->
<?php  
  
  $stmt = $conn->prepare($user_duels_as_rival_f_sql);
  $stmt->execute();
  $count = $stmt->fetchColumn();
  ?>

  <h4>Completed Duels History(rivals that chalenge you)</h4>
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo2">Show / Hide Duels <span class="badge"><?php echo $count; ?></span></button>

  <div id="demo2" class="collapse">

<?php  
  
  
  if ($count >= 1) {

?>

    
      <div class="panel panel-primary ">
        <div class="panel-heading"><center>Completed Duels History(rivals that chalenge you)</center></div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" ><!-- Table -->
            <thead>
              <tr>
                <th scope="col">Rival</th>
                <th scope="col">Score(You)</th>
                <th scope="col">Winner</th>
                <th scope="col">Category</th>
                <th scope="col">Time</th>
              </tr>
            </thead>
            <tbody>
<?php  
  
  $sql = "SELECT duels.rival_id ,users.username ,duels.user_score, duels.rival_score, duels.duel_date, categories.cat_id, categories.cat_name 
    FROM duels
    INNER JOIN users ON duels.user_id = users.user_id AND duels.duel_status = 'finished' AND duels.rival_id = '$u_id'
    INNER JOIN categories ON duels.cat_id = categories.cat_id";
    foreach ($conn->query($sql) as $row) {

?>
              <tr>
                <td data-label="Rival"><?php echo $row["username"]; ?></td>
                <td data-label="Score(You)"><?php echo $row["user_score"]." (".$row["rival_score"].")"; ?></td>
                <td data-label="Winner"><?php 
                if ($row["user_score"]>$row["rival_score"]) echo $row["username"];
                elseif ($row["user_score"]<$row["rival_score"]) echo $u_name;
                else echo "(Draw)";
                ?></td>
                <td data-label="Category"><?php echo $row["cat_name"]; ?></td>
                <td data-label="Time"><?php echo $row["duel_date"]; ?></td>
              </tr> 
<?php 
    }  
  }else {
    ?>
    <h4><?php echo "You Dont have any completed duels from a rival" ?></h4> 
    <?php  
  }
?>
            </tbody>
          </table>
        </div>
      </div>
    </div><!-- end colapse panel 2 -->


<!-- start  colapse panel 3 -->
<?php  
  
  $stmt = $conn->prepare($as_rival_sql);
  $stmt->execute();
  $count = $stmt->fetchColumn();
  ?>

  <h4>Pending duels (rival have to answer)</h4>
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo3">Show / Hide Duels <span class="badge"><?php echo $count; ?></span></button>

  <div id="demo3" class="collapse">

<?php  
  
  if ($count >= 1) {

?>
      <div class="panel panel-primary ">
        <div class="panel-heading"><center>Pending duels (rival have to answer)</center></div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered" ><!-- Table -->
            <thead>
              <tr>
                <th scope="col">Rival</th>
                <th scope="col">Your Score</th>
                <th scope="col">Category</th>
                <th scope="col">Time</th>
              </tr>
            </thead>
            <tbody>
<?php  

  $sql = "SELECT duels.duel_id, duels.rival_id, users.username, duels.user_score, duels.rival_score, duels.duel_date, categories.cat_id, categories.cat_name 
  FROM duels
  INNER JOIN users ON duels.rival_id = users.user_id AND duels.user_id = '$u_id' AND duels.duel_status = 'pending' 
  INNER JOIN categories ON duels.cat_id = categories.cat_id";
  foreach ($conn->query($sql) as $row) {  

?>
              <tr>
                <td data-label="Rival"><?php echo $row["username"]; ?></td>
                <td data-label="Your Score"><?php echo $row["user_score"]; ?></td>
                <td data-label="Category"><?php echo $row["cat_name"]; ?></td>
                <td data-label="Time"><?php echo $row["duel_date"]; ?></td>
              </tr> 
<?php 
    }  
  }else {
    ?>
    <h4><?php echo "You Dont have any pending duels that rivals have to answer" ?></h4> 
    <?php  
  }
?>
            </tbody>
          </table>
        </div>   
      </div>
    </div><!-- end colapse panel 3 -->  



<!-- start  colapse panel 4 -->
<?php  
  
  $stmt = $conn->prepare($i_have_caused_sql);
  $stmt->execute();
  $count = $stmt->fetchColumn();
  ?>

  <h4>Pending duels (you have to answer)</h4>
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo4">Show / Hide Duels <span class="badge"><?php echo $count; ?></span></button>

  <div id="demo4" class="collapse">

<?php  
  

  if ($count >= 1) {

?>

    
        <div class="panel panel-primary ">
          <div class="panel-heading"><center>Pending duels (you have to answer)</center></div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered" >
              <thead>
                <tr>
                  <th scope="col">Rival</th>
                  <th scope="col">Your Score</th>
                  <th scope="col">Category</th>
                  <th scope="col">Time</th>
                  <th scope="col">Answer</th>
                </tr>
              </thead>
              <tbody>
<?php  

  $sql = "SELECT duels.duel_id, duels.user_id, duels.rival_id, users.username, duels.user_score, duels.rival_score, duels.cat_id, duels.duel_date, categories.cat_id, categories.cat_name 
    FROM duels
    INNER JOIN users ON duels.user_id = users.user_id AND duels.rival_id = '$u_id' AND duels.duel_status = 'pending' 
    INNER JOIN categories ON duels.cat_id = categories.cat_id";
    foreach ($conn->query($sql) as $row) {

?>
              <tr>
                <td data-label="Rival"><?php echo $row["username"]; ?></td>
                <td data-label="Rival's Score"><?php echo $row["user_score"]; ?></td>
                <td data-label="Category"><?php echo $row["cat_name"]; ?></td>
                <td data-label="Time"><?php echo $row["duel_date"]; ?></td>
                <td data-label="Answer">
                  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="rival_id" value="<?php echo $row["user_id"]; ?>">
                    <input type="hidden" name="rival_name" value="<?php echo $row["username"]; ?>">
                    <input type="hidden" name="duel_cat_id" value="<?php echo $row["cat_id"]; ?>">
                    <input type="hidden" name="duel_cat_name" value="<?php echo $row["cat_name"]; ?>">
                    <input type="hidden" name="duel_id" value="<?php echo $row["duel_id"]; ?>">
                    <input type="hidden" name="duel_user_score" value="<?php echo $row["user_score"]; ?>">
                    <button type="submit" class="btn btn-sm btn-danger" ><span class="glyphicon glyphicon-tower"></span></button>
                  </form>
                </td>
              </tr> 
<?php 
    }  
  }else {
    ?>
    <h4><?php echo "You Dont have pending duels to answer" ?></h4> 
    <?php  
  }
?>
            </tbody>
          </table>
        </div>
      </div><!-- end colapse panel 4 -->
  

      <a href="welcome.php" class="btn back-btn btn-warning " ><span class="glyphicon glyphicon-menu-left"></span> Main menu</a>
    </div> 
   

   </div>
 </div>
</div><!-- end of container --> 




<?php 
$res = null;
$conn = null;
?>
<a id="back-to-top" class="btn btn-primary btn-lg btn-back-to-top"  role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>

<!-- back to top button -->  
<script type="text/javascript">
$(document).ready(function(){
  //$('#back-to-top').fadeOut();
  $(window).scroll(function () {
    
    if ($(this).scrollTop() > 50) {//50      
      $('#back-to-top').fadeIn();
    } else {
      $('#back-to-top').fadeOut();  
    }
  });

  // scroll body to 0px on click

  $('#back-to-top').click(function () {
     $('#back-to-top').tooltip('hide');
    $('body,html').animate({
      scrollTop: 0
      }, 800);
      return false;
    });
  $('#back-to-top').tooltip();
  });
</script>


</body>


</html>