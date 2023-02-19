<?php
//Sending back to main if they are already logged in
if(isset($_SESSION["email"])) 
{
    header('Location: main.php');
    exit();
}
?>

<?php
if (isset($_POST["SignUp"]) && $_POST["SignUp"]) 
{
    //validating form data - email, username, avatar, password, confirm password
    $validate = true;
    $regex_email = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,3}$/";
    $regex_uname = "/^[a-zA-Z0-9_]+$/";
    //$regex_avatar  = "/.*\.(png|gif|bmp|jpe?g)$/igm";
    $regex_password = "/[^a-zA-Z]/";
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["pass"]);
    $cpassword = trim($_POST["cpass"]);
    try {
        //connect to database
        $db = new PDO("mysql:host=localhost; dbname=drew111w", "drew111w", "Cs115!");

        //Validate all fields before attempting a query

        // Validate email format
        $emailMatch = preg_match($regex_email, $email);
        if($email == null || $email == "" || $emailMatch == false) {
            $validate = false;
        }

        // Check if the email address is already taken.
        $q1 = "SELECT COUNT(*) FROM Users WHERE email = '$email'";
        $count = $db->query($q1)->fetchColumn(); 
        if($count > 0) {
            $validate = false;
        } 
              
        // Validate password
        $pswdLen = strlen($password);
        $pswdMatch = preg_match($regex_password, $password);
        if($password == null || $password == "" || $pswdLen < 8 || $pswdMatch == false) {
            $validate = false;
        }

        //confirm password
        if ($password != $cpassword)
        {
          $validate = false;
        }

        //validate username
        $userMatch = preg_match($regex_uname, $username);
        if($username == null || $username == "" || $userMatch == false) {
            $validate = false;
        }

        // Check if the username is already taken
        $q1 = "SELECT COUNT(*) FROM Users WHERE screen_name = '$username'";
        $count = $db->query($q1)->fetchColumn(); 
        if($count > 0) {
            $validate = false;
        } 

      //validating avatar
      if ($_FILES["avatar"]["name"]) //prevents error if no image was submitted
      {
        $target_dir = "avatars/";
        $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        //Check file is real
        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
        if($check == false) {
          $validate = false;
        }
      
        // Check if file already exists
        if (file_exists($target_file)) {
          $validate = 0;
        }
      
        // Check file size
        if ($_FILES["avatar"]["size"] > 500000) {
          $validate = 0;
        }
      
        // Validate that the image is the right format
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          $validate = false;
        }
      }else
      {
        $validate = false;
      }
    //We first attempt to move the avatar
    //If inserting the avatar fails we dont create the user
    if ($validate == true)
    {
      if (!(move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file))) 
      {
        $validate = false;
      }
    }
    // Only attempt to insert new user if all fields valid
    if($validate == true) {
        $avatar = $_FILES["avatar"]["name"];
        $q2 = "INSERT INTO Users (email, screen_name, avatar, password) values ('$email', '$username', '$avatar', '$password')";
    
        $r2 = $db->query($q2);
        
        if ($r2 != false) {
            header("Location: main.php");
            $r2 = null;
            $db = null;
            exit();

        } else {
            $r2 = null;
            $validate = false;
        }         
    }
    if ($validate == false) {
        echo "Error: Invalid Signup Data";
        //Because it specifically asks for a generic error message I won't tell people
        //When they are using a username that is already in use - weird as it is not to.
        //We will also not allow users to have the same avatar name.

        //I interpret returning to the signup form as simply continuing and loading the signup form.
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
<title>SignUp Page</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="main.css"/>
<script type="text/javascript" src="validate.js"> </script>  
</head>

<body>
<section>
  <div class = "wrapper80">
    <div>
      <header>
        <h1 class = "center greenText">User Creation
        </h1>
        
        </header>
    </div>
    <div>
      <!-- grid spacer -->
    </div>
    <div>
      <form id="SignUp" method="post" action="SignUp.php" enctype="multipart/form-data">
        <table class = "table2 wideTable">
          <tr><td>&nbsp;<label id="msg_email" class="err_msg"></label></td></tr>
          <tr><td>Email: <input class = "textbox" type="text" id="email" name="email"/> </td></tr>
          <tr><td>&nbsp;<label id="msg_uname" class="err_msg"></label></td></tr>
          <tr><td>Userename: <input class = "textbox" type="text" id="username" name="username"/></td></tr>
          <tr><td>&nbsp;<label id="msg_avatar" class="err_msg"></label></td></tr>
          <tr><td>Avatar: <input type="file" id="avatar" name="avatar" accept="image/*"/> </td></tr>
          <tr><td >&nbsp;<label id="msg_pswd" class="err_msg"></label></td></tr>
          <tr><td>Password: <input class = "textbox" type="text" id="pass" name="pass"/>   </td></tr>
          <tr><td>&nbsp;<label id="msg_pswdr" class="err_msg"></label></td></tr>
          <tr><td>Confirm Password: <input class = "textbox" type="text" id="cpass" name="cpass"/>     </td></tr>
      </table>
      <p>&nbsp;</p>
      <p class = "center"><input type="submit" name="SignUp" value="SignUp" /></p>
      </form>  
      <script type="text/javascript" src="signUp.js"></script> 
    </div>
    <div>
      <a class = "right sidebar" href="main.php">Back to homepage</a>
    </div>
  </div>
  <div></div>
</section>
<footer>
  <div class = "center wrapper80">
    <div>
      <a class="button" href="https://validator.w3.org/check?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FSignUp.php&charset=%28detect+automatically%29&doctype=XHTML+1.0+Strict&group=0">Validate XHTML</a>
      <a class="button" href="https://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.webdev.cs.uregina.ca%2F%7Edrew111w%2FAssignments%2FAssignment5%2FSignUp.php&profile=css3svg&usermedium=all&warning=1&vextwarning=&lang=en">Validate CSS</a>
    </div>
  </div>
  </footer>
</body>
</html>
