function checkVote(event)
{
    //obtain data
    var answer_id = event.currentTarget.previousElementSibling.value;

    var xmlhttp = new XMLHttpRequest();
    // access the onreadystatechange event for the XMLHttpRequest object
    xmlhttp.addEventListener("readystatechange", showResults, false);
    //prepare a get - send date

    xmlhttp.open("GET", "PollVoteUpdate.php?q=" + answer_id, true);
    //Do this to actually execute the either type of request
    xmlhttp.send();
}

function showResults()
{
    if (this.readyState == 4 && this.status == 200) {
        var results;
        try {
           // alert(responseText);
            //Translate back to a table
            results = JSON.parse(this.responseText);
            // if (results[0].question != NULL) {
          
                //delete the old vote table
                var body = document.getElementById("body");
                var section = document.getElementsByName("section");
                
                //shuffle the results page HTML up
                body.insertBefore(section[1],section[0]);
                section[0].style.display = "inline";
                //Successfully delete the button... and a lot of other things...
                section[1].remove();
                //Top info
                data = document.getElementsByName("data");
                //Each bar
                answers = document.getElementsByClassName("left graphAnswer");
                graphButton = document.getElementsByClassName("graphButton");
                //Update top info 0 = question, 1 = created date, 2 = last vote date
                data[0].innerHTML = results[0].question;
                data[1].innerHTML = results[0].created_dt;
                data[2].innerHTML = results[0].last_vote_dt;
                //Update each button in a loop
                size = results.length;
                max_votes = 1;
                for (i = 0; i < size; i++)
                {
                    answers[i].innerHTML = results[i].answer + " : " + results[i].vote_count;
                    max_votes = Math.max(max_votes, results[i].vote_count);
                }
                //calculating a scale factor and updating length of the bars
                max_votes = max_votes/75;
                for (i = 0; i < size; i++)
                { 
                temp = results[i].vote_count/max_votes;               
                graphButton[i].style.width = temp+ "%"; 
                }
                //if there are fewer than 5 answers - hide the remainder
                for (i = size; i < 5; i++)
                {
                    answers[i].style.display = "none";
                    graphButton[i].style.display = "none";
                }
                //return
                return;
        }
        catch {

            return;
        }
    }//endif processing sql results 
    
    return;
}



function checkNewPoll() {

    //obtain current newest poll date
    //var poll_id = document.forms[0].poll_id.value;
    
    let buttons_values = document.getElementsByName("poll_id");
    var poll_id = buttons_values[0].value;
    //alert(poll_id);
    //create XMLHttpRequest object
    var xmlhttp = new XMLHttpRequest();
    // access the onreadystatechange event for the XMLHttpRequest object
    xmlhttp.addEventListener("readystatechange", receive_ajax_checkNewPoll, false);
    //prepare a get - send date

    xmlhttp.open("GET", "mainUpdate.php?q=" + poll_id, true);
    //Do this to actually execute the either type of request
    xmlhttp.send();
    
   
}

function receive_ajax_checkNewPoll() {
    if (this.readyState == 4 && this.status == 200) {
        // try parsing AJAX response text as JSON
        var results;
        try {
            // var temp = document.getElementById("debugArea");
            // temp.innerHTML = this.responseText;
            //alert(this.responseText);
            results = JSON.parse(this.responseText);
            //alert("potato");
            if (results.poll_id > 0) {//returns a poll id if it needs updating
                //alert(results.poll_id);
                //add another, delete the last one
                var poll_id = results.poll_id;
                var question = results.question;
                // var old_poll_id = document.forms[0].poll_id.value;
                // var pollsTable = document.getElementsByName('pollsTable');
                // var all_polls = document.getElementsByName("poll");//each poll has the name poll

                var questions_text = document.getElementsByName("question");
                var buttons_values = document.getElementsByName("poll_id");
                var question_number = questions_text.length;
                var button_number = buttons_values.length;
                //=========================================
                //The only thing that seperates the polls is these 3 fields
                //=========================================
                //shuffles all answers down one 
                for (let i = question_number; i > 1; i--)
                {
                    questions_text[i-1].innerHTML = String(i) +" . &nbsp" + questions_text[i-2].innerHTML.substring(9);
                    // questions_text[i-1].innerHTML = <?=$polls?> +" . &nbsp" + questions_text[i-2].innerHTML;
                }
                //shuffles all button values down one
                for (let i = button_number; i > 2; i = i -2)
                {
                    buttons_values[i-1].value = parseInt(buttons_values[i-3].value);
                    buttons_values[i-2].value = parseInt(buttons_values[i-3].value);
                }
                //inserting into position 1
                buttons_values[0].value = parseInt(poll_id);
                buttons_values[1].value = parseInt(poll_id);
                questions_text[0].innerHTML = "1. &nbsp" + question;
            }
           // alert ("function left23");
        }
        catch {
            return;
        }
    }//endif processing sql results 
    
    return;
} //end receive_ajax_response() function


