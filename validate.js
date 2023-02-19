class atributes {
   constructor(input1, output1, maxLength1) {
     this.input = input1;
     this.output = output1;
     this.maxLength = maxLength1;
   }
 }

function checkUsername(uname, msg_uname)
{
   let textNode;
   //A word character is a character a-z, A-Z, 0-9, including _ (underscore).
   let regex_uname = /^[a-zA-Z0-9_]+$/;
   let noError = true;
   if (uname == null || uname == "") {
      textNode = document.createTextNode("Username is empty.");
      msg_uname.appendChild(textNode);
      noError = false;
    }
    else if (regex_uname.test(uname) == false) {
     textNode = document.createTextNode("Username must only contain letters, numbers, and _ characters");
     msg_uname.appendChild(textNode);
     noError = false;
   }
   return noError;
}
function checkPassword(pswd, msg_pswd)
{
   let textNode;
   let noError = true;
   let regex_pswd  = /[^a-zA-Z]/;  //at least one non-letter
   if (pswd == null || pswd == "") {
      textNode = document.createTextNode("Password is empty. ");
      msg_pswd.appendChild(textNode);
      noError = false;
    }
    else 
    {
      if (pswd.length < 8) {
        textNode = document.createTextNode("Password must be 8 characters or longer.  ");
        msg_pswd.appendChild(textNode);
        noError = false;
      }
    //I recognize that this isn't a requirement on the login page - but its a feature
    //I appricate when I forgot my password.
    if (regex_pswd.test(pswd) == false) {
     textNode = document.createTextNode("Password must contain one non-letter character. ");
     msg_pswd.appendChild(textNode);
     noError = false;
    }
    return noError;
}
}
function confirmPassword(pswd, pswdc, msg_pswdr)
{
   let textNode;
   let noError = true;
   if (pswdc == null || pswdc == "") {
      textNode = document.createTextNode("Must confirm password. ");
      msg_pswdr.appendChild(textNode);
      noError = false;
    }
   else if (pswdc != pswd) {
     textNode = document.createTextNode("Passwords must match. ");
     msg_pswdr.appendChild(textNode);
     noError = false;
   }
   return noError;
}
function checkEmail(email)
{
   let textNode;
   let regex_email = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,3}$/;
   let msg_email = document.getElementById("msg_email");
   msg_email.innerHTML  = "";
   let noError = true;
   //email not empty and must be a valid format
  if (email == null || email == "") {
   textNode = document.createTextNode("Email address empty.");
   msg_email.appendChild(textNode);
   noError = false;
 } 
 else if (regex_email.test(email) == false) {
   textNode = document.createTextNode("Email address wrong format. example: username@somewhere.sth");
   msg_email.appendChild(textNode);
   noError = false;
 }
 return noError;
}
function checkQuestion(question)
{
   let textNode;
   let noError = true;
   let regex_oneHundred = /^(\S|\s){101}?/;
   let msg_question = document.getElementById("msg_question");
   msg_question.innerHTML = "";
   // question not empty
   if (question == null || question == "") {
   textNode = document.createTextNode("Question is empty.");
   msg_question.appendChild(textNode);
   noError = false;
   
   } 
   else if (regex_oneHundred.test(question)) {
      // textNode = document.createTextNode("Limit 100 characters");
      // msg_question.appendChild(textNode);
      // Error message displayed in real time
      noError = false;
      } 
      
   return noError;
}

function displayResult(valid, event)
{
   if (valid == true)
   {
      //Success
      //Goes to the next page
      //  event.preventDefault();
   }
   else
   {
      //Failure
      //Gives error warnings
      event.preventDefault();
   }
}



function LoginForm(event)
{
   var valid = true;

   var elements = event.currentTarget;
   var email = elements[0].value;
   var pswd = elements[1].value;

   //error messages
   var msg_pswd  = document.getElementById("msg_pswd");
 
   msg_pswd.innerHTML = "";

   let t1 = checkEmail(email);
   let t2 = checkPassword(pswd, msg_pswd);
   valid = (t1 && t2);
   //logic
   displayResult(valid, event);
}
function checkAvatar(avatar, msg_avatar)
{
   let textNode;
   let noError = true;
   let regex_avatar  = /.*\.(png|gif|bmp|jpe?g)$/igm;
   if (avatar == null || avatar == "")
   {
      textNode = document.createTextNode("Must select avatar. ");
      msg_avatar.appendChild(textNode);
      noError = false;
   }  else if (regex_avatar.test(avatar) == false)
   {
      textNode = document.createTextNode("Must be gif, jpeg, bmp, or png. ");
      msg_avatar.appendChild(textNode);
      noError = false;
   }
   return noError;
}

function SignUpForm(event)
{
   var valid = true;

   var elements = event.currentTarget;
   var email = elements[0].value;
   var uname = elements[1].value;
   var avatar = elements[2].value;
   var pswd = elements[3].value;
   var pswdr = elements[4].value;

   //error messages
   var msg_email = document.getElementById("msg_email");
   var msg_uname = document.getElementById("msg_uname");
   var msg_avatar = document.getElementById("msg_avatar");
   var msg_pswd  = document.getElementById("msg_pswd");
   var msg_pswdr = document.getElementById("msg_pswdr");
 
   msg_email.innerHTML  = "";
   msg_uname.innerHTML = "";
   msg_avatar.innerHTML = "";
   msg_pswd.innerHTML = "";
   msg_pswdr.innerHTML = "";
 
   let t1 = checkEmail(email);
   let t2 = checkUsername(uname, msg_uname);
   let t3 = checkPassword(pswd, msg_pswd);
   let t4 = confirmPassword(pswd, pswdr, msg_pswdr);
   let t5 = checkAvatar(avatar, msg_avatar);

   valid = (t1 && t2 && t3 && t4 && t5);
   displayResult(valid, event);
}

function checkDate(openDate1, closeDate1)
{
   let regex_date = /^[+-]?\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
   let noError = true;
   let textNode;
   let msg_Date = document.getElementById("msg_Date");
   msg_Date.innerHTML = "";
   // date not empty
   if (openDate1 == null || openDate1 == "") {
   textNode = document.createTextNode("Open date empty. ");
   msg_Date.appendChild(textNode);
   noError = false;
   } else if (regex_date.test(openDate1) == false) 
   {
      textNode = document.createTextNode("Open date wrong format. ");
      msg_Date.appendChild(textNode);
      noError = false;
   }
   if (closeDate1 == null || closeDate1 == "") {
      textNode = document.createTextNode("Close date empty. ");
      msg_Date.appendChild(textNode);
      noError = false;
      } else if (regex_date.test(closeDate1) == false) 
      {
         textNode = document.createTextNode("Open date wrong format. ");
         msg_Date.appendChild(textNode);
         noError = false;
      }else if (openDate1 > closeDate1) {
         textNode = document.createTextNode("Open date must be after close date");
         msg_Date.appendChild(textNode);
         noError = false;
       }
   
   return noError;
}
function checkTime(openTime1, closeTime1)
{
    let textNode;
    let noError = true;
    let regex_time = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
    let msg_Time = document.getElementById("msg_Time");
    msg_Time.innerHTML = "";
    // date not empty
    if (openTime1 == null || openTime1 == "") {
    textNode = document.createTextNode("Open time empty. ");
    msg_Time.appendChild(textNode);
    noError = false;
    
    } else if (regex_time.test(openTime1) == false)
    {
      textNode = document.createTextNode("Open time wrong format. ");
      msg_Time.appendChild(textNode);
      noError = false;
    }
    if (closeTime1 == null || closeTime1 == "") {
      
       textNode = document.createTextNode("Close time empty. ");
       msg_Time.appendChild(textNode);
       noError = false;
       } else if (regex_time.test(closeTime1) == false)
       {
         textNode = document.createTextNode("Close time wrong format. ");
         msg_Time.appendChild(textNode);
         noError = false;
       }
       
    return noError;
}
//These functions are just to combat weird errors I was getting
//where .length() doesn't work on strings
function notEmpty(answer)
{
   let regex_notEmpty = /\S+$/;
   return regex_notEmpty.test(answer);
}
function overFifty(answer)
{
   let regex_fifty = /^(\S|\s){51}?/;
   return regex_fifty.test(answer);
}

function checkAnswersLength(answer1, answer2, answer3, answer4, answer5)
{
   if (1 - 1 + overFifty(answer1) + overFifty(answer2) + overFifty(answer3) + overFifty(answer4) + overFifty(answer5) < 1)
   {
      return true;
   }
   return false;
}
function atLeastTwoAnswers(answer1, answer2, answer3, answer4, answer5, empty_warning)
{
   let noError = true;
   let total = 0;
   let textNode;
   empty_warning.innerHTML = "";
   total = notEmpty(answer1)+ notEmpty(answer2)+ notEmpty(answer3)+ notEmpty(answer4)+ notEmpty(answer5);
   if (total < 2)
   {
      
      textNode = document.createTextNode("Must have at least two answers. ");
      empty_warning.appendChild(textNode);
      noError = false;
   }
   return noError;
}

function NewPollForm(event)
{
   //event.preventDefault();
   
   var valid = true;

   var elements = event.currentTarget;
   var question = elements[0].value;
   var answer1 = elements[1].value;
   var answer2 = elements[2].value;
   var answer3 = elements[3].value;
   var answer4 = elements[4].value;
   var answer5 = elements[5].value;
   var openTime = elements[6].value;
   var openDate = elements[7].value;
   var closeTime = elements[8].value;
   var closeDate = elements[9].value;

   //error messages
   var msg_question = document.getElementById("msg_question");
   var msg_answer1 = document.getElementById("msg_answer1");
   var msg_answer2 = document.getElementById("msg_answer2");
   var msg_answer3 = document.getElementById("msg_answer3");
   var msg_answer4 = document.getElementById("msg_answer4");
   var msg_answer5 = document.getElementById("msg_answer5");
   var msg_Date = document.getElementById("msg_Date");
   var msg_Time = document.getElementById("msg_Time");
   var empty_warning = document.getElementById("empty_warning");

   msg_question.innerHTML = "";
   msg_answer1.innerHTML = "";
   msg_answer2.innerHTML = "";
   msg_answer3.innerHTML = "";
   msg_answer4.innerHTML = "";
   msg_answer5.innerHTML = "";
   msg_Time.innerHTML = "";
   msg_Date.innerHTML = "";
  
   let t1 = checkQuestion(question);
   let t2 = checkAnswersLength(answer1, answer2, answer3, answer4, answer5);
   let t3 = atLeastTwoAnswers(answer1,answer2,answer3,answer4,answer5, empty_warning);
   let t4 = checkDate(openDate, closeDate);
   let t5 = checkTime(openTime, closeTime);
   valid = (t1 && t2 && t3 && t4 && t5);

   displayResult(valid, event);
}


function charLimit(event)
{
   let maxLength = event.currentTarget.atribute.maxLength;
   let textNode;
   let inputLength = document.getElementById(event.currentTarget.atribute.input).value.length;
   let outputLocation = document.getElementById(event.currentTarget.atribute.output);
   outputLocation.innerHTML = "";

   let warning = "Limit " + maxLength.toString() + " characters: " + inputLength.toString() + "/" + maxLength.toString();

   if (inputLength > maxLength) 
   {
      textNode = document.createTextNode(warning);
      outputLocation.appendChild(textNode);
   }

}

