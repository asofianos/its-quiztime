<?php 
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: index.php");
  exit;
}

// Set the game settings and details until the end of the game 
include("config.php");
// Prepare the questions of the choosen category
include("questions.php");

$log_cat_id = $_SESSION["cat_id"];
$log_cat_name = $_SESSION["category"];
$log_duel_id_to_comlpete = $_SESSION["duel_id_to_complete"];

// Set the questions form the database
$questions_array = array();
$q_array = array();
$q_array = fill_array($log_cat_id);

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">



// Set up of game
  var rId = "";
  var pID = "";//The button id that user choose
  var turn = 1;//First turn
  // For the countdown
  var seconds;
  var temp;
  var choose = false;
  var timeLeft = 0;

  var quizScore;
  
  // Pass the questions array from php to javascript 
  var categoryQuestions = <?php echo json_encode($q_array); ?>;
	// Elements which display attributes of question array 
  var cRound,title,a1,a2,a3,a4; 
  function setQuiz(){

	  for (var i = turn-1 ; i < turn ; i++) {
	  	cRound = document.getElementById("turns").innerHTML = "Round : "+turn;
	    title = document.getElementById("questionTitle").innerHTML = categoryQuestions[i][0];
	    a1 = document.getElementById("ans1").innerHTML = categoryQuestions[i][1];
	    a2 = document.getElementById("ans2").innerHTML = categoryQuestions[i][2];
	    a3 = document.getElementById("ans3").innerHTML = categoryQuestions[i][3];
	    a4 = document.getElementById("ans4").innerHTML = categoryQuestions[i][4];

	    rId = categoryQuestions[i][5];

	  }
	  timeLeft = 15;
	  
  	countdown();
}


// Show feedack messages to the player
function showMessage(message, type) {

  var x = document.getElementById("msg");

  if (type == "error") {
    document.getElementById("msg").classList.add('red-msg');
  }
  else if (type == "success") {
    document.getElementById("msg").classList.add('green-msg');
  }
  document.getElementById('msg').innerHTML = ""+message;
  x.style.visibility = "visible";
}


// Countdown timer
function countdown() {
  seconds = document.getElementById('countdown').innerHTML;
  seconds = parseInt(seconds, 10);
  if (seconds == 1) {
    temp = document.getElementById('countdown');
    temp.innerHTML = "0";
    timeLeft = 0;
  	results("zero");
    return;
  }
  // If the player choose an answer the turn have to stop and reveal the right answer
  if (choose == false) {

  	timeLeft --;
  	seconds--;
	  temp = document.getElementById('countdown');
	  temp.innerHTML = seconds;
	  timeoutMyOswego = setTimeout(countdown, 1000);
  }

  
} 

// Display right answer and players answer if its wrong
function results(pick) {

  var newMsg = ""
  var rightAnswer = rId;

  var userAnswer = "ans"+pick;
  pID = userAnswer;

  var x = document.getElementById(rightAnswer);
 
 if ( document.getElementById('msg').classList.contains('btn-primary') )
    document.getElementById('msg').classList.toggle('btn-success');

  if (pick == "zero") {
    newMsg = "Next time be faster!";
    showMessage(newMsg,"error");
    score(timeLeft);  
  }
  else if (userAnswer == rightAnswer) {

    choose = true;
    newMsg = "Success !";
    score(timeLeft);

    showMessage(newMsg,"success");
  }
  else if (userAnswer != rightAnswer){
    choose = true;
    var a = document.getElementById(userAnswer);

    document.getElementById(userAnswer).classList.remove('btn-primary');
    document.getElementById(userAnswer).classList.add('btn-danger');
    newMsg = "Wrong choice!";
    showMessage(newMsg,"error");
    score(0);

  }if (userAnswer != "anszero") {
    document.getElementById(userAnswer).classList.remove('btn-primary');
    document.getElementById(userAnswer).classList.add('btn-success');

  }
  

  // Disable all the buttons so the player cant choose again in this turn 
  a1 = document.getElementById("ans1").disabled = true;
  a2 = document.getElementById("ans2").disabled = true;
  a3 = document.getElementById("ans3").disabled = true;
  a4 = document.getElementById("ans4").disabled = true;

  // Display nextButton to procced to the next turn
  var b = document.getElementById("nextButton");
  b.setAttribute("class","btn back-btn btn-success pull-right");
  b.setAttribute("onclick","nextQuestion()");
  if (turn < 4 ) {
    document.getElementById("nextButton").innerHTML = 'Next question <span class="glyphicon glyphicon-menu-right"></span>';
  }else {
    document.getElementById("nextButton").innerHTML = 'Final Score <span class="glyphicon glyphicon-star-empty"></span>'; 
  }
  b.style.visibility = "visible";

}


// Change the display for the next turn
function nextQuestion(){

  // Enable all the buttons again
  a1 = document.getElementById("ans1").disabled = false;
  a2 = document.getElementById("ans2").disabled = false;
  a3 = document.getElementById("ans3").disabled = false;
  a4 = document.getElementById("ans4").disabled = false;
  
  if (turn < (categoryQuestions.length)) {
    var x = document.getElementById("nextButton");
    x.style.visibility = "hidden";

    var x1 = document.getElementById("msg");
    x1.style.visibility = "hidden";

    var x2 = document.getElementById(rId);
    document.getElementById(rId).classList.remove('btn-success');
    document.getElementById(rId).classList.add('btn-primary');

    document.getElementById("msg").classList.remove('green-msg');
    document.getElementById("msg").classList.remove('red-msg');

    if (pID != "anszero") {
      var x3 = document.getElementById(pID);
      document.getElementById(pID).classList.remove('btn-success');
      document.getElementById(pID).classList.remove('btn-danger');
      document.getElementById(pID).classList.add('btn-primary');
    }

    var x4 = document.getElementById("countdown").innerHTML = 15;
    choose = false;
    turn++;
    setQuiz();
  }
  else {
    displayFinalScore();
  }
  // End of turn
}


function score(sec) {
  if (turn == 1) quizScore =0;
  quizScore += sec;
}

function removeElement(elementId) {
    // Removes an element from the document
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}
function finishDuel() {

  var score = quizScore;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Success
      }
  };
  xmlhttp.open("GET", "playground.php?score=" + quizScore +"&turn=" + turn, true);
  xmlhttp.send();
}

function closeGame(){

  var values = quizScore;

  var jqxhr = $.post( "close-game.php", { score:  ""+quizScore , turn : ""+turn })
  .done(function( resdata ) {
    // success
  })
  .fail(function(textStatus, errorThrown) {
    // Error
  })
  .always(function() {
    // Request completed
  });

}

// Display the final score
function displayFinalScore() {
	removeElement("turns");
	removeElement("questionTitle");
	removeElement("ans1");
	removeElement("ans2");
	removeElement("ans3");
	removeElement("ans4");
	removeElement("gly");
	removeElement("nextButton");

	var scoreMsg = "The final score is "+quizScore+ ' <span class="glyphicon glyphicon-star-empty"></span>' ; 
	showMessage(scoreMsg,"success");

  if (turn==4) {
    closeGame();
  } 

}

function showHint(str) {
    if (str.length == 0) { 
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "new.php?q=" + str, true);
        xmlhttp.send();
    }
}



</script>


