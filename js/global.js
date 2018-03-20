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
