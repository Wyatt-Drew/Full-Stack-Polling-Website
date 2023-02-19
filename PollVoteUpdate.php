<?php
try{
    session_start(); 
   //all database manipulation
   $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");

   $answer_id = $_GET['q'];
   //add tally
   $q1 = "INSERT INTO Votes (answer_id, vote_dt) values ('$answer_id', NOW())";
   $r2 = $db->query($q1);
   

    //retrieve vote numbers for all the answers
    $poll_id = trim($_SESSION["poll_id"]);
    $q1 = "UPDATE Polls SET last_vote_dt = NOW() WHERE Polls.poll_id = $poll_id";
    $r2 = $db->query($q1);
    // $q1 = "SELECT Polls.poll_id, Polls.user_id, Polls.question, Polls.created_dt, Polls.last_vote_dt, Answers.answer_id, Answers.answer, count(Votes.answer_id) AS vote_count
    // FROM Polls 
    // INNER JOIN Users 
    // ON Polls.user_id=Users.user_id 
    // INNER JOIN Answers ON (Polls.poll_id=Answers.poll_id) 
    // LEFT OUTER JOIN Votes ON (Answers.answer_id=Votes.answer_id) 
    // WHERE (Polls.poll_id=$poll_id) GROUP BY Answers.answer_id 
    // ORDER BY count(Votes.answer_id) DESC";


    $q1 = "SELECT Polls.question, Polls.created_dt, Polls.last_vote_dt, Answers.answer, count(Votes.answer_id) AS vote_count
    FROM Polls 
    INNER JOIN Users 
    ON Polls.user_id=Users.user_id 
    INNER JOIN Answers ON (Polls.poll_id=Answers.poll_id) 
    LEFT OUTER JOIN Votes ON (Answers.answer_id=Votes.answer_id) 
    WHERE (Polls.poll_id=$poll_id) GROUP BY Answers.answer_id 
    ORDER BY count(Votes.answer_id) DESC";


    // $q1 = "SELECT Polls.question, Polls.user_id, Answers.answer, Answers.answer_id
    // FROM Polls, Answers
    // WHERE (Polls.poll_id = $poll_id) AND (Answers.poll_id = $poll_id)";
    $r = $db->query($q1, PDO::FETCH_ASSOC);
    //send back as json/convert to json
    $array = array();
    //converting

    for ($i = 0; $i < $r->rowCount(); $i++) {
        $row = $r->fetch();
        $array[$i]["question"] = $row["question"];
        $array[$i]["created_dt"] = $row["created_dt"];
        $array[$i]["last_vote_dt"] = $row["last_vote_dt"];
        $array[$i]["answer"] = $row["answer"];
        $array[$i]["vote_count"] = $row["vote_count"];
        }
          echo json_encode($array);
    $db = null;
    $r = null;
} catch (PDOException $e) {
echo "PDO Error: $e\n<br />";
}

?>
