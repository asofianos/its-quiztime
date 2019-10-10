<?php  
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $final_score = $_POST["score"];//players score
  $fturn = $_POST["turn"];

  //End the duel for one player
  date_default_timezone_set('UTC');
  $date = date("Y-m-d");//Create current date
  $q_user_id = $_SESSION["id"];//Player id
  $q_rival_id = $_SESSION["rival_id"];//Rival id
  $q_cat_id = $_SESSION["cat_id"];
  $q_score = $_SESSION["user_score"];
  
  $response = "";
  $sql = "";
  $winner_sql = "";
  $loser_sql = "";
  $draw1_sql = "";
  $draw2_sql = "";
  if($_SESSION["duel_id_to_complete"] > 0){
    $q_duel_id = $_SESSION["duel_id_to_complete"];
  }

  //Check the duel status. If its status=="pending" the duel exist and you can answer back.
  //If status=="finished" then the duel is completed 
  if ($final_score >=0 && $q_rival_id != 0) { 

    //If rival_id != 0 save to the database the duel details    
    if ($q_duel_id != 0 ) {
      $status="finished";
      //Accept the duel as rival 
      $sql = "UPDATE duels SET rival_score = '$final_score', duel_status = '$status' WHERE duel_id = '$q_duel_id' ";
      

      //Check who player wins or check if its a tie
      if ($final_score > $q_score) {
        $winner_sql = "UPDATE users SET wins = wins + 1, ranked_score = ranked_score +'$final_score' WHERE user_id = '$q_user_id' ";
        $loser_sql = "UPDATE users SET loses = loses + 1, ranked_score = ranked_score +'$q_score' WHERE user_id = '$q_rival_id' ";
      }else if ($final_score < $q_score) {
        $winner_sql = "UPDATE users SET wins = wins + 1, ranked_score = ranked_score +'$q_score' WHERE user_id = '$q_rival_id' ";
        $loser_sql = "UPDATE users SET loses = loses + 1, ranked_score = ranked_score +'$final_score' WHERE user_id = '$q_user_id' ";
      }else {
        $draw1_sql = "UPDATE users SET draws = draws + 1, ranked_score = ranked_score +'$q_score' WHERE user_id = '$q_rival_id' ";
        $draw2_sql = "UPDATE users SET draws = draws + 1, ranked_score = ranked_score +'$final_score' WHERE user_id = '$q_user_id' ";
      }
      
      
    }else {
      //New duel query
      $status = "pending";
      $rival_score = 0;
      $sql = "INSERT INTO duels (user_id, rival_id, cat_id, user_score, rival_score, duel_status, duel_date ) VALUES ('$q_user_id', '$q_rival_id', '$q_cat_id', '$final_score', '$rival_score', '$status', '$date')";

    }
    

    $_SESSION["duel_id_to_complete"] = 0;
    $_SESSION["rival_id"] = 0;//Rival id
    $_SESSION["rival_name"] = '';
    $_SESSION["cat_id"] = 0;
    $_SESSION["user_score"] = 0; 
  }
  else {
    $sql = "UPDATE users SET personal_score = personal_score +'$final_score'  WHERE user_id = '$q_user_id' ";
    $response = "den exei rival kai kanei : ".$sql;
  } 

  
  try {

    $conn->beginTransaction();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    echo "rival_id = ".$q_rival_id. "\n";
    echo "q_score = ".$q_score. "\n";
    echo "q_duel_id = ".$q_duel_id. "\n";

    if ($q_rival_id != 0 && $q_score > 0 ) {
      $stmt = $conn->prepare($winner_sql);
      $stmt->execute();

      $stmt = $conn->prepare($loser_sql);
      $stmt->execute();
    }
    
    if ($draw1_sql !="" && $draw2_sql != "") {
      $stmt = $conn->prepare($draw1_sql);
      $stmt->execute();


      $stmt = $conn->prepare($draw2_sql);
      $stmt->execute();

    }
    $conn->commit();
    echo $sql. "\n";
    echo $winner_sql. "\n";
    echo $loser_sql. "\n";
    echo $draw1_sql. "\n";
    echo $draw2_sql. "\n";

  } catch (PDOException $e) {
    $pdo->rollback();
    throw $e;
    echo "Connection failed: " . $e->getMessage();
  }

  $stmt = null;
  $conn = null;
  

  echo $response ;

   
}

?>