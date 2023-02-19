<?php 
try {
  //PHP for getting poll information
  session_start();  
  $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");
  if (!(isset($_SESSION["poll_id"]))) 
  {
  //must recieve poll_id for navigating to this page to be valid
  //Redirects to home if someone ends up here in error
    header('location: main.php');
    exit();
  }
  // $_SESSION["poll_id"] = 1;
  //======================================================
  //retrieve the information for this poll from the database
  //======================================================
  $poll_id = trim($_SESSION["poll_id"]);
  $q1 = "SELECT Polls.question, Polls.user_id, Answers.answer, Answers.answer_id
        FROM Polls, Answers
        WHERE (Polls.poll_id = $poll_id) AND (Answers.poll_id = $poll_id)";
  $result = $db->query($q1);
  $row = $result->fetch(PDO::FETCH_ASSOC);

  //getting data for side bar
  $poll_creator_id = $row["user_id"];
  $q2 = "SELECT Users.screen_name, Users.avatar FROM Users WHERE Users.user_id = $poll_creator_id";
  $r2 = $db->query($q2);
  $row2 = $r2->fetch(PDO::FETCH_ASSOC);
  $screen_name = $row2["screen_name"];
  $avatar = $row2["avatar"];
  $question = $row["question"];
} catch (PDOException $e) {
    echo "PDO Error >> " . $e->getMessage() . "\n<br />";
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "en-CA">
<head>
<title>Voting</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="main.css" type="text/css"/>
<script type="text/javascript" src="scripts.js"></script>
</head>

<body id = "body">
<section name = "section">
  <div class = "wrapper80">
    <div>
      <header>
        <h1 class = "center greenText" >Voting</h1>
      </header>
    </div>
    <div>

    </div>
    <div>
      <!-- ================================================ -->
      <!-- provide the question and the answer alternatives -->
      <!-- ================================================ -->
      <table class = "table3 center whiteLine">
        <tr><td class = "voteQuestion"><?=$row["question"]?></td><td class = "voteQuestion"></td></tr>
      <?php do{ ?>
        <tr>
          <td class = "whiteLineTop"><?=$row["answer"]?></td><td class = "whiteLineTop"><p class = "center">
          <input type="hidden" name="answer_id" value ="<?=$row["answer_id"]?>" />
          <input type="submit" value="Vote" name="Vote"/>
          </p> </td></tr>
      <?php }while ($row = $result->fetch(PDO::FETCH_ASSOC)); ?>
      </table>
  </div>
  <div>
        <a class = "sidebar" href="main.php">Back to homepage</a>
        <h2 class = "greenText">
        Poll Created by &nbsp;<?=$screen_name?>
        </h2>
        <img src="avatars/<?php echo $avatar; ?>" alt="avatar" class = "avatar"/>
    </div>
</div>
<div></div>
</section>
<footer>
  <div class = "center wrapper80">
    
    <div>
      <a class="button" href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollVote.php&charset=%28detect+automatically%29&doctype=XHTML+1.0+Strict&group=0">Validate XHTML</a>
      <a class="button" href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollVote.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en">Validate CSS</a>
    </div>
  </div>
</footer>
<!-- ============================================================================================ -->
<section name = "section" class = "displayNone">
    <div class = "wrapper80">
        <div>
            <header>
                <h1 class = "center greenText"> Results</h1>
              </header>
        </div>
        <div>
            <!-- grid spacer -->
        </div>
        <div>
        <table class = "managementTable whiteLine">

            <tr>
              <th class = "whiteLine" colspan = "4" name = "data">QUESTION</th> </tr>
            <tr>
              <th>
              </th>
                <th class = "small" >
                    <span class = "darkGreenText"> Created: </span>
                    <span name = "data">CREATED DATE</span>
                    <span class = "darkGreenText"> Last answer: </span>
                    <span name = "data" >NONE </span>
                </th>
            </tr>
            <?php
            for ($i = 0; $i < 5; $i++)
            {
                ?>
                
            <tr><th colspan = "4">&nbsp;</th></tr>
            <tr>
            <th class = "left graphAnswer">    Answer : VOTECOUNT Votes</th>
            <th colspan="3" class = "left" >
            <button class = "graphButton" style="width:100%">&nbsp;</button>
            </th>
            </tr>
            <tr><th colspan = "4">&nbsp;</th></tr>
            <?php

            }
            

            $r1 = null;
            ?>    
        </table>
        </div>
        <div>
        <a class = "sidebar" href="main.php">Back to homepage</a>
        <h2 class = "greenText">
        Poll Created by &nbsp;<?=$screen_name?>
        </h2>
        <img src="avatars/<?php echo $avatar; ?>" alt="avatar" class = "avatar"/>
    </div>
    </div>
    <div></div>
</section>

<!-- =========================================================================================== -->

<script type="text/javascript" src="PollVote.js"></script>
</body>
</html>
