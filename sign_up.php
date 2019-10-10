<?php  
// Include config file
  
  include("config.php");

// Define variables and initialize with empty values
  $username = $password = $confirm_password = "";
  $username_err = $password_err = $confirm_password_err = $gender_err = $date_err = "";
  $gender = $day = $month = $year = $b_date = "";

  $log_uname = "";
  $log_pass = "";
  $log_val_pass = "";
  $log_gender = "";
  $log_day = "";
  $log_month = "";
  $log_year = "";

  try {
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

      $log_uname = trim($_POST["username"]);
      $log_pass = trim($_POST["password"]);
      $log_val_pass = trim($_POST["confirm_password"]);
      $log_gender = $_POST["gender"];
      $log_day = $_POST["b_day"];
      $log_month = $_POST["b_month"];
      $log_year = $_POST["b_year"];

      // Validate username
      if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
      }else {
        $sql = "SELECT * FROM users WHERE username= '$log_uname'";
        $res = $conn->query($sql);
        
        if ($res->fetchColumn() ==1 ) {
          $username_err = "This username is already taken.";
        }elseif (strlen($log_uname) < 4 || strlen($log_uname) > 10) {
          $username_err = "Username must be between 4 and 10 characters long.";
        } else{
          $username = $log_uname;
        }
      }

      // Validate password
      if(empty($log_pass)){
        $password_err = "Please enter a password.";   
      } elseif(strlen($log_pass) < 4 || strlen($log_pass) > 10){
        $password_err = "Password must be betwean 4 and 10 characters long.";
      } else{
        $password = $log_pass;
      }

      // Validate confirm password
      if(empty($log_val_pass)){
        $confirm_password_err = "Please confirm password."; 
      } else{
        $confirm_password = $log_val_pass;
        if(empty($password_err) && ($password != $confirm_password)){
          $confirm_password_err = "Password did not match.";
        }
      }

      // Validate gender
      if(empty($log_gender)){
        $gender_err = "Please select a gender."; 
      } else{
        $gender = $log_gender;
      }

      // Validate birthdate
      if(empty($log_day)){
        $date_err = "Please select a day.";  
      } else if (empty($log_month)) {
        $date_err = "Please select a month."; 
      }else if (empty($log_year)) {
        $date_err = "Please select a year."; 
      }else {
        $day = $log_day;
        $month = $log_month;
        $year = $log_year;
        $b_date = $year."-".$month."-".$day;
      }

      // Check input errors before inserting in database
      if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($gender_err) && empty($date_err)){

        // Prepare an insert statement
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
        $sql = "INSERT INTO users (username, password, gender, birthdate ) VALUES ('$username', '$hashed_password', '$gender', '$b_date')";

        $stmt = $conn->prepare($sql);
       
        if($stmt->execute()){
          // Redirect to login page

          header("location: index.php");
        } else{
          echo "Something went wrong. Please try again later.";
        }

        $stmt = null;
        $conn = null;

      }

    }
    
  } catch (Exception $e) {
    print "Failed: " . $e->getMessage();
  }



?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign up</title>
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
      <h4 >Create an account. <small>It's free!</small></h4>       
    </div>
    
      <form class="form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" accept-charset="UTF-8" id="signUpForm">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
          <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="Username">
          <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Password">
          <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password">
          <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="<?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
          <div class="radio-inline input-md">
            <input type="radio" name="gender" value="male" >Male
          </div>
          <div class="radio-inline input-md ">
            <input type="radio" name="gender" value="female" >Female
          </div>
          <span class="help-block"><?php echo $gender_err; ?></span>
        </div>
        
        <div>
          <p>When it's your birthday?</p>
        </div>
        

        <div class="form-group ">
          <div class="<?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
            <div class="content row">
              <div class="col-xs-4" >
                <select class="form-control" id="sel_day" name="b_day">
                  <option value="0">Day</option>
                  <option value="01">1</option>
                  <option value="02">2</option>
                  <option value="03">3</option>
                  <option value="04">4</option>
                  <option value="05">5</option>
                  <option value="06">6</option>
                  <option value="07">7</option>
                  <option value="08">8</option>
                  <option value="09">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                  <option value="13">13</option>
                  <option value="14">14</option>
                  <option value="15">15</option>
                  <option value="16">16</option>
                  <option value="17">17</option>
                  <option value="18">18</option>
                  <option value="19">19</option>
                  <option value="20">20</option>
                  <option value="21">21</option>
                  <option value="22">22</option>
                  <option value="23">23</option>
                  <option value="24">24</option>
                  <option value="25">25</option>
                  <option value="26">26</option>
                  <option value="27">27</option>
                  <option value="28">28</option>
                  <option value="29">29</option>
                  <option value="30">30</option>
                  <option value="31">31</option>
                </select>
              </div>                            
              <div class="col-xs-4" >
                <select class="form-control" id="sel_month" name="b_month">
                  <option value="0">Month</option>
                  <option value="01">Jan</option>
                  <option value="02">Feb</option>
                  <option value="03">Mar</option>
                  <option value="04">Apr</option>
                  <option value="05">May</option>
                  <option value="06">Jun</option>
                  <option value="07">Jul</option>
                  <option value="08">Aug</option>
                  <option value="09">Sep</option>
                  <option value="10">Oct</option>
                  <option value="11">Nov</option>
                  <option value="12">Dec</option>
                </select>
              </div>
              <div class="col-xs-4">
                <select class="form-control" id="sel_year" name="b_year">
                  <option value="0">Year</option>
                  <option value="2018">2019</option>
                  <option value="2018">2018</option>
                  <option value="2017">2017</option>
                  <option value="2016">2016</option>
                  <option value="2015">2015</option>
                  <option value="2014">2014</option>
                  <option value="2013">2013</option>
                  <option value="2012">2012</option>
                  <option value="2011">2011</option>
                  <option value="2010">2010</option>
                  <option value="2009">2009</option>
                  <option value="2008">2008</option>
                  <option value="2007">2007</option>
                  <option value="2006">2006</option>
                  <option value="2005">2005</option>
                  <option value="2004">2004</option>
                  <option value="2003">2003</option>
                  <option value="2002">2002</option>
                  <option value="2001">2001</option>
                  <option value="2000">2000</option>
                  <option value="1999">1999</option>
                  <option value="1998">1998</option>
                  <option value="1997">1997</option>
                  <option value="1996">1996</option>
                  <option value="1995">1995</option>
                  <option value="1994">1994</option>
                  <option value="1993">1993</option>
                  <option value="1992">1992</option>
                  <option value="1991">1991</option>
                  <option value="1990">1990</option>
                  <option value="1989">1989</option>
                  <option value="1988">1988</option>
                  <option value="1987">1987</option>
                  <option value="1986">1986</option>
                  <option value="1985">1985</option>
                  <option value="1984">1984</option>
                  <option value="1983">1983</option>
                  <option value="1982">1982</option>
                  <option value="1981">1981</option>
                  <option value="1980">1980</option>
                  <option value="1979">1979</option>
                  <option value="1978">1978</option>
                  <option value="1977">1977</option>
                  <option value="1976">1976</option>
                  <option value="1975">1975</option>
                  <option value="1974">1974</option>
                  <option value="1973">1973</option>
                  <option value="1972">1972</option>
                  <option value="1971">1971</option>
                  <option value="1970">1970</option>
                  <option value="1969">1969</option>
                  <option value="1968">1968</option>
                  <option value="1967">1967</option>
                  <option value="1966">1966</option>
                  <option value="1965">1965</option>
                  <option value="1964">1964</option>
                  <option value="1963">1963</option>
                  <option value="1962">1962</option>
                  <option value="1961">1961</option>
                  <option value="1960">1960</option>
                  <option value="1959">1959</option>
                  <option value="1958">1958</option>
                  <option value="1957">1957</option>
                  <option value="1956">1956</option>
                  <option value="1955">1955</option>
                  <option value="1954">1954</option>
                  <option value="1953">1953</option>
                  <option value="1952">1952</option>
                  <option value="1951">1951</option>
                  <option value="1950">1950</option>
                  <option value="1949">1949</option>
                  <option value="1948">1948</option>
                  <option value="1947">1947</option>
                  <option value="1946">1946</option>
                  <option value="1945">1945</option>
                  <option value="1944">1944</option>
                  <option value="1943">1943</option>
                  <option value="1942">1942</option>
                  <option value="1941">1941</option>
                  <option value="1940">1940</option>
                  <option value="1939">1939</option>
                  <option value="1938">1938</option>
                  <option value="1937">1937</option>
                  <option value="1936">1936</option>
                  <option value="1935">1935</option>
                  <option value="1934">1934</option>
                  <option value="1933">1933</option>
                  <option value="1932">1932</option>
                  <option value="1931">1931</option>
                  <option value="1930">1930</option>
                  <option value="1929">1929</option>
                  <option value="1928">1928</option>
                  <option value="1927">1927</option>
                  <option value="1926">1926</option>
                  <option value="1925">1925</option>
                  <option value="1924">1924</option>
                  <option value="1923">1923</option>
                  <option value="1922">1922</option>
                  <option value="1921">1921</option>
                  <option value="1920">1920</option>
                  <option value="1919">1919</option>
                  <option value="1918">1918</option>
                  <option value="1917">1917</option>
                  <option value="1916">1916</option>
                  <option value="1915">1915</option>
                  <option value="1914">1914</option>
                  <option value="1913">1913</option>
                  <option value="1912">1912</option>
                  <option value="1911">1911</option>
                  <option value="1910">1910</option>
                  <option value="1909">1909</option>
                  <option value="1908">1908</option>
                  <option value="1907">1907</option>
                  <option value="1906">1906</option>
                  <option value="1905">1905</option>
                </select>
              </div>
              <span class="help-block"><?php echo $date_err; ?></span>
            </div>
          </div>
        </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block" id="myButton" >Register</button>
      </div>
    
    </form>
    <div class="bottom text-center">
      <p>Already have an account? <a href="index.php">LOGIN here!</a></p>
    </div>  
        
  </div>
  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


</body>

</html>
<?php  ?>
