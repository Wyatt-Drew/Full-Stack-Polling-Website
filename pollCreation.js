
//poll creation
document.getElementById("newPoll").addEventListener("submit", NewPollForm, false);

document.getElementById("question").atribute = new atributes("question","qwarning",100);
document.getElementById("question").addEventListener("input", charLimit);

document.getElementById("answer1").atribute = new atributes("answer1","a1warning",50);
document.getElementById("answer1").addEventListener("input", charLimit);

document.getElementById("answer2").atribute = new atributes("answer2","a2warning",50);
document.getElementById("answer2").addEventListener("input", charLimit);

document.getElementById("answer3").atribute = new atributes("answer3","a3warning",50);
document.getElementById("answer3").addEventListener("input", charLimit);

document.getElementById("answer4").atribute = new atributes("answer4","a4warning",50);
document.getElementById("answer4").addEventListener("input", charLimit);

document.getElementById("answer5").atribute = new atributes("answer5","a5warning",50);
document.getElementById("answer5").addEventListener("input", charLimit);

