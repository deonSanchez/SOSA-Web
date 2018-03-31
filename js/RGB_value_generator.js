//Board
var sliderR = document.getElementById("Rvalue");
var outputR = document.getElementById("R");
outputR.innerHTML = sliderR.value;

sliderR.oninput = function() {
  outputR.innerHTML = this.value;
}

var sliderG = document.getElementById("Gvalue");
var outputG = document.getElementById("G");
outputG.innerHTML = sliderG.value;

sliderG.oninput = function() {
  outputG.innerHTML = this.value;
}

var sliderB = document.getElementById("Bvalue");
var outputB = document.getElementById("B");
outputB.innerHTML = sliderB.value;

sliderB.oninput = function() {
  outputB.innerHTML = this.value;
}


//Stimulus
var StimSliderR = document.getElementById("RvalueStim");
var StimOutputR = document.getElementById("sR");
StimOutputR.innerHTML = StimSliderR.value;

StimSliderR.oninput = function() {
  StimOutputR.innerHTML = this.value;
}

var StimSliderG = document.getElementById("GvalueStim");
var StimOutputG = document.getElementById("sG");
StimOutputG.innerHTML = StimSliderG.value;

StimSliderG.oninput = function() {
  StimOutputG.innerHTML = this.value;
}

var StimSliderB = document.getElementById("BvalueStim");
var StimOutputB = document.getElementById("sB");
StimOutputB.innerHTML = StimSliderB.value;

StimSliderB.oninput = function() {
  StimOutputB.innerHTML = this.value;
}
