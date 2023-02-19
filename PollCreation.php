<?php 
//=======================================================================
//Only allow access to this page if they are logged in
//=======================================================================
session_start();
if(isset($_SESSION["user_id"])) 
{
    $user_id1 = trim($_SESSION["user_id"]);
    $screen_name = trim($_SESSION["screen_name"]);
    $avatar = trim($_SESSION["avatar"]);

}else
{
  header('Location: main.php');
  exit();
}
?>

<?php 
//=======================================================================
//validate the form data to ensure that all of the required elements are 
//present and in the proper format.
//=======================================================================
$validate = true;
if (isset($_POST["SubmitPoll"]) && $_POST["SubmitPoll"]) 
{
    $question = trim($_POST["question"]);
    $answer1 = trim($_POST["answer1"]);
    $answer2 = trim($_POST["answer2"]);
    $answer3 = trim($_POST["answer3"]);
    $answer4 = trim($_POST["answer4"]);
    $answer5 = trim($_POST["answer5"]);
    $openTime = trim($_POST["openTime"]);
    $closeTime = trim($_POST["closeTime"]);
    $openDate = trim($_POST["openDate"]);
    $closeDate = trim($_POST["closeDate"]);
    $screen_name = trim($_SESSION["screen_name"]);
    $user_id = trim($_SESSION["user_id"]);
    try {
        //connect to database
        $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");
        // Validate question
        if($question == null || $question == "" || strlen($question)>100 ){
          $validate = false;
        }
        // Validate answers
        //I will require at least 2 answers so it is a valid poll
        if($answer1 == null || $answer1 == "" || strlen($answer1)>50 ){
          $validate = false;
        }
        if($answer2 == null || $answer2 == "" || strlen($answer2)>50 ){
          $validate = false;
        }
        if(strlen($answer3)>50 ){
          $validate = false;
        }
        if(strlen($answer4)>50 ){
          $validate = false;
        }
        if(strlen($answer5)>50 ){
          $validate = false;
        }     
        // Validate date/time
        $regex_date = "/^[+-]?\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
        $regex_time = "/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/";
        if (preg_match($regex_date, $openDate) == false ||preg_match($regex_date, $closeDate) == false){
            $validate = false;
        }
        if (preg_match($regex_time, $openTime) == false ||preg_match($regex_time, $closeTime) == false){
            $validate = false;
        }
        //=======================================================================
        //if the data is good, add it to the database and return the user to the
        //Poll Management Page
        //=======================================================================
        if($validate == true) {
            //creating dateTime from date and time
            $openDateTime = date('Y-m-d H:i:s', strtotime("$openDate $openTime"));
            $closeDateTime = date('Y-m-d H:i:s', strtotime("$closeDate $closeTime"));
            // Inserting poll
            $q1 = "INSERT INTO Polls (user_id, question, created_dt, open_dt, close_dt) values ('$user_id1', '$question', NOW(), '$openDateTime', '$closeDateTime')";
            $r1 = $db->query($q1);
            $stmt = $db->query("SELECT LAST_INSERT_ID()");
            $poll_id = $stmt->fetchColumn();
            $stmt = NULL;
            //2 answers must exist
            $q3 = "INSERT INTO Answers (poll_id, answer) values ('$poll_id', '$answer1')";
            $r2 = $db->query($q3);
            $q3 = "INSERT INTO Answers (poll_id, answer) values ('$poll_id', '$answer2')";
            $r3 = $db->query($q3);
            //3 more may exist
            if (strlen($answer3)>0)
            {
              $q3 = "INSERT INTO Answers (poll_id, answer) values ('$poll_id', '$answer3')";
              $r4 = $db->query($q3);              
            }
            if (strlen($answer4)>0)
            {
              $q3 = "INSERT INTO Answers (poll_id, answer) values ('$poll_id', '$answer4')";
              $r4 = $db->query($q3);              
            }
            if (strlen($answer5)>0)
            {
              $q3 = "INSERT INTO Answers (poll_id, answer) values ('$poll_id', '$answer5')";
              $r4 = $db->query($q3);              
            }
            
            if ($r2 != false && $r1 != false) {
                $r4 = null;
                $r3 = null;
                $r2 = null;
                $r1 = null;
                $db = null;
                header("Location: PollManagement.php");
                exit();
            } else {
                $r2 = null;
                $r1 = null;
                $r3 = null;
                $validate = false;
            }         
        }
        if ($validate == false) {
            echo "Error";
        }
        $db = null;
    } catch (PDOException $e) {
        echo "PDO Error >> " . $e->getMessage() . "\n<br />";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "en-CA">
<head>
<title>Poll Creation</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="main.css" type="text/css"/>
<script type="text/javascript" src="validate.js"> </script>
</head>

<body>

<section>
<div class = "wrapper80">
  <div>
    <header>
      <div class = "center">
        <h1 class = "greenText"> Creating New Poll</h1>
      </div>
    </header>
  </div>
  <div>
    <!-- grid spacer -->
  </div>  
  <div>


    <form id="newPoll" method="post" action="PollCreation.php">
      <table class = "table2 center">
        <tr><td>&nbsp;</td><td></td></tr>
        <tr><td>&nbsp;</td><td><label id="msg_question" class="err_msg"></label><label id="qwarning" class = "err_msg"></label></td></tr>
        <tr><td>Question (Max 100 characters):</td><td> <input class = "textbox" type="text" id="question" name="question" /> </td></tr>
        <tr><td>&nbsp;</td><td><label id="msg_answer1" class="err_msg"></label><label id="a1warning" class = "err_msg"></label></td></tr>
        <tr><td>Possible Answers(Max 50 characters):  
                </td><td> <input class = "textbox" type="text" id="answer1" name="answer1" /></td></tr>
        <tr><td>&nbsp;<label id="empty_warning" class = "err_msg"></label></td><td><label id="msg_answer2" class="err_msg"></label><label id="a2warning" class = "err_msg"></label></td></tr>
        <tr><td></td><td> <input class = "textbox" type="text" id="answer2" name="answer2"  /></td></tr>
        <tr><td>&nbsp;</td><td><label id="msg_answer3" class="err_msg"></label><label id="a3warning" class = "err_msg"></label></td></tr>
        <tr><td></td><td> <input class = "textbox" type="text" id="answer3" name="answer3" /> </td></tr>
        <tr><td>&nbsp;</td><td><label id="msg_answer4" class="err_msg"></label><label id="a4warning" class = "err_msg"></label></td></tr>
        <tr><td></td><td> <input class = "textbox" type="text" id="answer4" name="answer4" /> </td></tr>
        <tr><td>&nbsp;</td><td><label id="msg_answer5" class="err_msg"></label><label id="a5warning" class = "err_msg"></label></td></tr>
        <tr><td></td><td> <input class = "textbox" type="text" id="answer5" name="answer5" /> </td></tr>
        <tr><td>&nbsp;</td><td><label id="msg_Date" class="err_msg"></label></td></tr>
        <tr><td >Open Time (EST):</td><td> <input type="time" id="openTime" name="openTime" />
            <input type="date" id="openDate" name="openDate"/> 
        </td></tr>
        <tr><td>&nbsp;</td><td><label id="msg_Time" class="err_msg"></label></td></tr>
        <tr><td>Close Time (EST):</td><td> <input type="time" id="closeTime" name="closeTime" />
            <input type="date" id="closeDate" name="closeDate" /></td></tr>
    </table>
    <p class = "center"> <input type="submit" name="SubmitPoll" value="SubmitPoll" /> </p>
    </form>
  </div>
  <div>
    <a class = "sidebar" href="main.php">Back to homepage</a>
    <a class = "sidebar" href="PollManagement.php">Poll managment</a>
    <h2 class = "greenText">
        Welcome,  &nbsp<?=$screen_name?>!
    </h2>
    <img src="avatars/<?php echo $avatar; ?>" alt="avatar" class = "avatar"/>
    <a class="normalButton" href="PollCreation.php">Create New Poll</a>
  </div>
</div>
<div></div>
</section>
<footer>
  <div class = "center wrapper80">
    <div>
      <a class="button" href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollCreation.php&charset=%28detect+automatically%29&doctype=XHTML+1.0+Strict&group=0&user-agent=W3C_Validator%2F1.3+http%3A%2F%2Fvalidator.w3.org%2Fservices">Validate XHTML</a>
      <a class="button" href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollCreation.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en">Validate CSS</a>
    </div>
  </div>
</footer>
<script type="text/javascript" src="pollCreation.js"></script>
</body>
</html>