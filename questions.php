<?php  
include("config.php");

// Select questions from the database and fill the array 
function fill_array($log_cat_id){
	
  $conn = $GLOBALS['conn'];
	$questions_array = array();
  $q_sql = "SELECT COUNT(*) FROM questions WHERE q_cat_id = '$log_cat_id' ";

  $stmt2 =$conn->prepare($q_sql);
  $stmt2->execute();
 	$count2 = $stmt2->fetchColumn();

  if ($count2 >= 1) {
    $sql = "SELECT * FROM questions WHERE q_cat_id = '$log_cat_id' ";

    $i = 0;
    foreach ($conn->query($sql) as $row) { 

      $questions_array[$i][0] = $row['q_title'];
      $questions_array[$i][1] = $row['q_ans1'];
      $questions_array[$i][2] = $row['q_ans2'];
      $questions_array[$i][3] = $row['q_ans3'];
      $questions_array[$i][4] = $row['q_ans4'];
      $questions_array[$i][5] = $row['q_right_ans'];
      
      $i++;
    }
  }
  // Shuffle the order of the questions for more fun!
  shuffle($questions_array);
  
  $res = null;
  $conn = null;

  return $questions_array;
}
?>