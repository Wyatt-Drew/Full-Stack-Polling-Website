<?php
session_start();

//Code to grab polls and answers
if(isset($_SESSION["poll_id"])) 
{
    try {
        $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");
    } catch (PDOException $e) {
        die("PDO Error >> " . $e->getMessage() . "\n<br />");
        
    }
    $poll_id = trim($_SESSION["poll_id"]);
    //=================================================================
    //query the database to get all of the required information to show the current results of this poll
    //=================================================================
    $q1 = "SELECT Polls.poll_id, Polls.user_id, Polls.question, Polls.created_dt, Polls.last_vote_dt, Answers.answer_id, Answers.answer, count(Votes.answer_id) AS vote_count
    FROM Polls 
    INNER JOIN Users 
    ON Polls.user_id=Users.user_id 
    INNER JOIN Answers ON (Polls.poll_id=Answers.poll_id) 
    LEFT OUTER JOIN Votes ON (Answers.answer_id=Votes.answer_id) 
    WHERE (Polls.poll_id=$poll_id) GROUP BY Answers.answer_id 
    ORDER BY count(Votes.answer_id) DESC";

    $r1 = $db->query($q1);
    $row = $r1->fetch(PDO::FETCH_ASSOC);
    $question = $row["question"];
    $user_id = $row["user_id"];
    //Getting info for side bar
    $q1 = "SELECT Users.screen_name, Users.avatar FROM Users WHERE user_id = $user_id";
    $r2 = $db->query($q1);
    $answer_number = 0;
    $row2 = $r2->fetch(PDO::FETCH_ASSOC);
    $screen_name = $row2["screen_name"];
    $avatar = $row2["avatar"];
    //This just adjusts the graph so the bars always are an appropriate length
    $max_votes = MAX($row["vote_count"],1);
    while ($row2 = $r2->fetch(PDO::FETCH_ASSOC))
    {
    $max_votes = MAX($max_votes, $row["vote_count"]);
    }
    //votes set to 75% scale
    $max_votes = $max_votes/75;
    $r2 = null;
    $db = null;
}else
{   
    //This occurs if someone ended up here without clicking
    //on any particular results to view
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
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "en-CA">
  <head>
  <title>Results </title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" href="main.css" type="text/css"/>
  </head>

  <body>
  <section>
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
                <th class = "whiteLine" colspan = "4"><?=$row["question"]?></th>
                </tr>
                <tr>
                <th><form method="POST" action="">
	  			<input type="hidden" name="poll_id" value ="<?=$row["poll_id"]?>" />
	  			<p class="normalButtonNoBackground"><input type="submit" value="Vote" name="Vote"></p>
	  			</form></th>
                <th>
                
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
            do 
            {
                ?>
            <tr>
            <tr><th colspan = "4">&nbsp</th></tr>

            <th class = "left graphAnswer">
                <?=$row["answer"]?> : <?=$row["vote_count"]?> Votes
            </th>
            <th colspan="3" class = "left" >
            <button class = "graphButton" style="width:<?=$row["vote_count"]/$max_votes?>%">&nbsp</button>
            </th>
            </tr>
            <tr><th colspan = "4">&nbsp</th></tr>
            <?php

            } while ($row = $r1->fetch(PDO::FETCH_ASSOC));
            

            $r1 = null;
            ?>    
        </table>
        </div>
        <div>
        <a class = "sidebar" href="main.php">Back to homepage</a>
        <h2 class = "greenText">
        Poll Created by &nbsp<?=$screen_name?>
        </h2>
        <img src="avatars/<?php echo $avatar; ?>" alt="avatar" class = "avatar"/>
    </div>
    </div>
    <div></div>
</section>
<footer>
    <div class = "center wrapper80">
      <div>
        <a class="button" href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollResults.php&charset=%28detect+automatically%29&doctype=XHTML+1.0+Strict&group=0">Validate XHTML</a>
        <a class="button" href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FPollResults.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en">Validate CSS</a>
      </div>
    </div>
  </footer>
</body>

</html>