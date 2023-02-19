<?php
session_start();
//======================================================
// retrieve all of the polls for the logged in user from the database
//======================================================
if(isset($_SESSION["user_id"])) 
{
    $user_id = trim($_SESSION["user_id"]);
    $screen_name = trim($_SESSION["screen_name"]);
    $avatar = trim($_SESSION["avatar"]);
    $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");
    //changed the command so it works
    $q1 = "SELECT Polls.poll_id,  Polls.user_id,  Answers.answer_id, Polls.question, Polls.created_dt, Polls.last_vote_dt, Answers.answer, count(Votes.answer_id) AS vote_count
    FROM Polls INNER JOIN Users ON Polls.user_id=Users.user_id
    INNER JOIN Answers ON (Polls.poll_id=Answers.poll_id)
    LEFT OUTER JOIN Votes ON (Answers.answer_id=Votes.answer_id)
    WHERE (Users.user_id=$user_id)
    GROUP BY Answers.answer_id ORDER BY Polls.created_dt DESC, count(Votes.answer_id) DESC";
    $r1 = $db->query($q1);
    //Calculating a factor to help make the graphs pretty for each graph
    $row = $r1->fetch(PDO::FETCH_ASSOC);
    $max_votes = 0;
    $max_vote_array = array();
    if (isset($row["poll_id"]))
    {
        $max_votes = $row["vote_count"];
        $prev = $row["poll_id"];
        while ($row = $r1->fetch(PDO::FETCH_ASSOC))
        {
            if ($prev != $row["poll_id"])
            {
                if ($max_votes == 0)
                {
                    $max_votes = 100;
                }
                //votes set to 75% scale
                $max_vote_array[] = $max_votes/75;
                $max_votes = 0;
            }
            $max_votes = MAX($max_votes, $row["vote_count"]);
            $prev = $row["poll_id"];
        }
        if ($max_votes == 0)
        {
            $max_votes = 100;
        }
        $max_vote_array[] = $max_votes/75;
    }
    $r1 = $db->query($q1);
    $db = null;
}else
{
    header('Location: main.php');
    exit();
}
//code to process vote and result input
if (isset($_POST["Vote"]) && $_POST["Vote"]) 
{
    $poll_id = trim($_POST["poll_id"]);
    $_SESSION["poll_id"] = $poll_id;
    header("Location: PollVote.php");
    exit();
}
if (isset($_POST["Results"]) && $_POST["Results"]) 
{
    $poll_id = trim($_POST["poll_id"]);
    $_SESSION["poll_id"] = $poll_id;
    header("Location: PollResults.php");
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "en-CA">
  <head>
  <title>Poll Management </title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" href="main.css" type="text/css"/>
  </head>
  <body>
  <header>
    <h1 class = "center greenText"> Poll Management</h1>
  </header>

  <section>
    <div class = "wrapper80">
    <div>
        <h2 class = "greenText center"> Your Polls </h2>
        <table class = "managementTable whiteLine">
            <?php
            //======================================================
            //• provide links for each poll to the Poll Vote Page and Poll Results Page
            //======================================================
            $previous = "";
            $previous_poll = "";
            $poll_counter = -1;
            while ($row = $r1->fetch(PDO::FETCH_ASSOC)) 
            {
                if ($previous_poll != $row["poll_id"])
                {
                    $poll_counter = $poll_counter + 1;
                    
                }
                $resize_factor = $row["vote_count"]/$max_vote_array[$poll_counter];
                if ($previous != $row["question"])
                {
                ?>
                 <tr>
                <th class = "whiteLine" colspan = "4"><?=$row["question"]?></th>
                </tr>
                <tr>
                <th><form method="POST" action="">
	  			<input type="hidden" name="poll_id" value ="<?=$row["poll_id"]?>" />
	  			<p class="normalButtonNoBackground"><input type="submit" value="Vote" name="Vote"></p>
	  			</form></th>
                <th>
                <form method="POST" action="">
	  			<input type="hidden" name="poll_id" value ="<?=$row["poll_id"]?>" />
	  			<p class="normalButtonNoBackground"><input type="submit" value="Results" name="Results"></p>
	  			</form></th>
                
                <th class = "small">
                    <span class = "darkGreenText"> Created: </span>
                    <?=$row["created_dt"]?>
                    <span class = "darkGreenText"> Last answer: </span>
                    <?php
                        if ($row["last_vote_dt"] == NULL)
                        {
                            echo "None";
                        }else
                        {
                            echo $row["last_vote_dt"];
                        }
                    ?>
                </th>
            </tr>
            <?php
                }
                ?>
            <tr>
            <th class = "left graphAnswer" >
                <?=$row["answer"]?> : <?=$row["vote_count"]?> Votes
            </th>
            <th colspan="3" class = "left" >
            <button class = "graphButton" style="width:<?=$resize_factor?>%">&nbsp</button>
            </th>
            </tr>
            <?php
            $previous = $row["question"];
            $previous_poll = $row["poll_id"];
            }
            $r1 = null;
            ?>          
        </table>
    </div>
    <div>
        <a class = "sidebar" href="main.php">Back to homepage</a>
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
        <a class="button" href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollManagement.php&charset=%28detect+automatically%29&doctype=XHTML+1.0+Strict&group=0">Validate XHTML</a>
        <a class="button" href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollManagement.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en">Validate CSS</a>
      </div>
    </div>
  </footer>
</body>

</html>