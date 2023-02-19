<?php
//Please note: there is no requirement for logging out
//users can simply navigate to main and log in as another user if they need to 
//sign in as another user.  Other than that it logs them out
//when the session variable expires naturally. 
session_start();
try {
    $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");
//php for logging in
$validate = true;
if (isset($_POST["Login"]) && $_POST["Login"]) 
{
    //=========================================================================
    //query the database with the login credentials to verify that the user
    //exists and provided the correct password
    //=========================================================================
    $email = trim($_POST["email"]);
    $password = trim($_POST["charcount1"]); 
    $q2 = "SELECT * from Users WHERE email = '$email' AND password = '$password'";
    $r = $db->query($q2, PDO::FETCH_ASSOC);

    // check result length: should be exactly 1 if there's a match.
    if ($r->rowCount() == 1)
    {
        //=========================================================================
        // If the information is correct, save the necessary information to know
        //who this user is within a session variable, and redirect to the Poll Management Page.
        //=========================================================================
        $row = $r->fetch();
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["screen_name"] = $row["screen_name"];
        $_SESSION["avatar"] = $row["avatar"];
        $db = null;
        $r = null;
        header("Location: PollManagement.php");
        exit();
    } else {
        echo "Error: Invalid Login Credentials";
    }
    $r = null;
}
//=================================================================
//Note: Since the assignment requires that I order by poll_id rather than date
//for new polls.  For consistency I have made this also order by poll_id rather than open
// and close date.
//=================================================================
    $q1 = "SELECT poll_id, question
    FROM Polls 
    ORDER BY poll_id DESC
    LIMIT 5";
$result = $db->query($q1);

} catch (PDOException $e) {
    die("PDO Error >> " . $e->getMessage() . "\n<br />");
}


//PHP for directing to results or vote pages
if (isset($_POST["Vote"]) && $_POST["Vote"]) 
{
    $_SESSION["poll_id"] = $_POST["poll_id"];
    header("Location: PollVote.php");
    exit();
}

if (isset($_POST["Results"]) && $_POST["Results"]) 
{
    $_SESSION["poll_id"] = $_POST["poll_id"];
    header("Location: PollResults.php");
    exit();
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang = "en-CA">
  <head>
  <title>Micro Polling </title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" href="main.css" type="text/css"/>
  <script type="text/javascript" src="scripts.js"></script>
  <script type="text/javascript" src="validate.js"></script> 
  </head>
  <body>
  <header>
    <img class="logo"  src="Logo1.PNG" alt="logo"/>
    <h1 class = "greenText">
        <br/>
        &nbsp; Welcome to Micro Polling</h1>
        <br/>
  </header>

  <section>
    <div class = "wrapper50">
        <div>
            <h2 id = "debugArea" class = "greenText center ">
                Most Recent Polls
            </h2>
            <table class = "table1 whiteLine"> 
                <?php
                //==========================================================================
                //list the question for each poll, with a link to allow the user to vote in
                //the specific poll (link to Poll Vote Page)
                //Link is above via post
                //==========================================================================
                $polls = 0;
                if ($result->rowCount() == 0)
                {
                ?>
                    <tr> <th>No Current Polls</th></tr>
                <?php
                }
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
                {         
                $polls = $polls + 1;   
                ?>
                    <tr > <th name = "question"><?=$polls?> . &nbsp;<?=$row["question"]?></th>
                    <th>
                    <form method="post" action="main.php">
                    <div><input type="hidden" name="poll_id" value ="<?=$row["poll_id"]?>" /></div>
                    <p class="normalButtonNoBackground"><input type="submit" value="Vote" name="Vote"/></p>
                    </form>
                    </th>
                    <th>
                    <form method="post" action="main.php">
                    <div><input type="hidden" name="poll_id" value ="<?=$row["poll_id"]?>" /></div>
                    <p class="normalButtonNoBackground"><input type="submit" value="Results" name="Results"/></p>
                    </form></th></tr>
                <?php
                 }
                 ?>
            </table>
        </div>
        <div>
            <h2 class = "greenText">
                Login
            </h2>
            <form id="Login" method="post" action="main.php">
            <table>
                <tr><td>&nbsp;</td><td><label id="msg_email" class="err_msg"></label></td></tr>
                <tr><td>Email:</td><td> <input class = "textbox" type="text"  id="email"  name="email" />   </td></tr>
                <tr><td>&nbsp;</td><td><label id="msg_pswd" class="err_msg"></label></td></tr>
                <tr><td>Password:</td><td> <input class = "textbox" type="text" id="pass" name="charcount1"/> <label id="charCount1" class = "err_msg"></label>  </td></tr>
            </table>
            <p><input type="submit" name="Login" value="Submit" /></p>    
        </form>
        
            <h3 class = "greenText">
                Dont have an account yet?
            </h3>
            <a class="normalButton" href="SignUp.php">Sign Up</a>
        </div>
    </div>
</section>
<footer>
    <div class = "center">
    <br/>
    <br/>
    <a class="button" href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment6%2Fmain.php&charset=%28detect+automatically%29&doctype=XHTML+1.0+Strict&group=0">Validate XHTML</a>
    <a class="button" href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment6%2Fmain.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en">Validate CSS</a>
    </div>
</footer>
    <script type="text/javascript" src="main.js"></script>
</body>

</html>