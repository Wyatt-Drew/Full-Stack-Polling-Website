<?php
try{
    $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");

    //TODO 2: query the User table... 
   $current_poll = $_GET['q'];
   //$current_poll = intVal($current_poll);
   
   $q1 = "SELECT poll_id, question
   FROM Polls 
   WHERE poll_id > $current_poll
   ORDER BY poll_id DESC
   LIMIT 1";

   $r = $db->query($q1, PDO::FETCH_ASSOC);
   $row = $r->fetch();
   $newest_poll = $row["poll_id"];
   
   if (intVal($current_poll) != intVal($newest_poll))
   {
    //send poll_id and question back
    echo json_encode($row);

   }else
   {
    //do nothing
    //echo json_encode($row);
   }
    $db = null;
    } catch (PDOException $e) {
    echo "PDO Error: $e\n<br />";
}

?>
