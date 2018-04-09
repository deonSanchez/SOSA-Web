//Board
var sliderR = document.getElementById("BoardRvalue");
var outputR = document.getElementById("R");
outputR.innerHTML = sliderR.value;

sliderR.oninput = function() {
  outputR.innerHTML = this.value;
}

var sliderG = document.getElementById("BoardGvalue");
var outputG = document.getElementById("G");
outputG.innerHTML = sliderG.value;

sliderG.oninput = function() {
  outputG.innerHTML = this.value;
}

var sliderB = document.getElementById("BoardBvalue");
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

var displaySliderBoard, displaySliderBackground, displaySliderCover;

function displayColorSlider1() {
  displaySliderBoard = document.getElementById("displaySliderBoard");
  displaySliderBackground = document.getElementById("displaySliderBackground");
  displaySliderCover = document.getElementById("displaySliderCover");

  if (displaySliderBoard.style.display === "none" ) {
    displaySliderBoard.style.display = "block";
    displaySliderBackground.style.display = "none";
    displaySliderCover.style.display = "none";
  }
}

function displayColorSlider2() {
  displaySliderBoard = document.getElementById("displaySliderBoard");
  displaySliderBackground = document.getElementById("displaySliderBackground");
  displaySliderCover = document.getElementById("displaySliderCover");

  if (displaySliderBackground.style.display === "none" ) {
    displaySliderBoard.style.display = "none";
    displaySliderBackground.style.display = "block";
    displaySliderCover.style.display = "none";
  }
}

function displayColorSlider3() {
  displaySliderBoard = document.getElementById("displaySliderBoard");
  displaySliderBackground = document.getElementById("displaySliderBackground");
  displaySliderCover = document.getElementById("displaySliderCover");

  if (displaySliderCover.style.display === "none" ) {
    displaySliderBoard.style.display = "none";
    displaySliderBackground.style.display = "none";
    displaySliderCover.style.display = "block";
  }
}
