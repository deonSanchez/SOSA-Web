// Author: Dan Blocker
// Query for the saved board and stimulus set from the server
//TODO: Get the saved board from the load board element and get the stimulus set from the load stimulus set

var selectedStimulus = "stimul";//$("#exampleFormControlSelect2 option:selected").text();
//var selectedBoard = $("#");
var receivedStimulus;


$("#loadStimulus").click(function () {
    $.ajax({
        type: 'POST',
        data: 'request=loadstimulus' + selectedStimulus,
        url: 'api/index.php',
        async: 'true',
        success: function (response) {
            var stimulus = JSON.parse(response);
            console.log(stimulus);
            var stimLength = objLength(stimulus);
            for(var i = 0; i < stimLength; i++){
                receivedStimulus[i] = stimulus[i];
            }
            alert("Success");
        },

        error: function () {
            alert("Stimulus failed to load");
        }
    });
});

$("#loadBoard").click(function () {

});
