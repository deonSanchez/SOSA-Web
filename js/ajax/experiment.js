/**
 *@author mitchell murphy
 *@version 1.0 
 */

var login_content = $("div#login_content");
var color_selection = -1;

/**
 * Counts object length
 * @param obj
 * @returns {Number}
 */
function objLength(obj){
	var i=0;
	for (var x in obj){
		if(obj.hasOwnProperty(x)){
			i++;
		}
	} 
	return i;
}

/**
 * Verifies logged-in status
 */
function checkLogin() {
	$.ajax({
		type: 'POST',
		data: 'request=checklogin',
		url : 'api/index.php',
		async: true,
		success: function (response) {
			if(response == 1) {
				login_content.html("You are already signed in!");
				$("button#signin").hide();
				$("a#sign-in-nav").hide();
				$("a#log-out-nav").show();
			}
		},
		error: function () {
			alert("An error has occured while verifying logged-in status!");
		}
	});
}



/***
 * This loads all the stimulus sets into the modal dropdown
 */
function loadBoardModal() {
	var board = $('#boards :selected').text();
	$.ajax( {
		type : 'POST',
		data : 'request=loadboards',
		url : 'api/index.php',
		async : true,
		success : function(response) {
		if(response != "null") {
			var json = JSON.parse(response);
			var len = objLength(json);
			var appendLabel = "";
			for (var i = len-1; i > -1; i--) {
				appendLabel = appendLabel + "<option boardid=\""+json[i].idboard+"\">"+json[i].board_name+"</option>";
			}
			$("select#boards").html(appendLabel);
			$("select#boardExperiment").html(appendLabel);
		} else {
			$("select#boards").html("");
			$("select#boardExperiment").html("");
		}
		},
		error : function() {
			alert("Error with create stimulus!");
		}
	});
}

/***
 * This loads all the stimulus sets into the modal dropdown
 */
function loadExprStimulus() {
	var set_title = $('#experi_set :selected').text();
	$.ajax( {
		type : 'POST',
		data : 'request=loadstimsetbyname&set_title='+set_title,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response != "null") {
				var json = JSON.parse(response);
				var len = objLength(json);
				var appendLabel = "";
				for (var i = len-1; i > -1; i--) {
					appendLabel = appendLabel + "<option id=\""+json[i].stimulus_id+"\">"+json[i].label+"</option>";
				}
				$("select#exper_stim").html(appendLabel);
			} else {
				$("select#exper_stim").html("");
			}
		},
		error : function() {
			alert("Error with create stimulus!");
		}
	});
}

/**
 * This document ready function fires when the page is loaded, it checks the api to see if the user is logged in. 
 * If they are logged in, it hides the login/register forms and displays a message and a logout button.
 * @returns {undefined}
 */
$(function () {
    //this part deals with login check
	checkLogin();
	
    //this part deals with loading initial stimulus set list and stimuli
    loadStimulusSets(function () {
    	loadCreatorStimulus();
        loadExprStimulus();
    });
    loadBoardModal();
});

/**
 * Login form processing, takes in input and submits to php
 */
$("button#signin").on("click", function(e) {
	var username = $("input#username").val();
	var password = $("input#password").val();
	login_content.html("<img style=\"display: block;margin-left: auto; " +
			"margin-right: auto;\" src=\"./img/gear.gif\" />");
	$.ajax( {
		type : 'POST',
		data : 'request=login&username=' + username + '&password=' + password,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response == 1) {
				login_content.html("You are already signed in!");
				$("button#signin").hide();
				$("a#sign-in-nav").hide();
				$("a#log-out-nav").show();
				$("div#sign-in").modal('hide');
			} else {
				alert("Failed to login! Try again?");
				login_content.html("<input id=\"username\" type=\"text\" class=\"form-control mb-3\" placeholder=\"Username\"> " +
						"<input id=\"password\" type=\"text\" class=\"form-control\" placeholder=\"Password\">");
			}
		},
		error : function() {
			alert("Error with logout!");
		}
	});
});

/**
 * Log out processor
 */
$("a#log-out-nav").on('click', function() {	
	$.ajax( {
		type : 'POST',
		data : 'request=logout',
		url : 'api/index.php',
		async : true,
		success : function(response) {
			window.location = "index.php";
		},
		error : function() {
			  var err = eval("(" + xhr.responseText + ")");
			  alert(err.Message);
		}
	});
})

/**
 * Register form processing, takes in input and submits to php
 */
$("button#register-submit").on('click', function() {
	var username = $("input#regusername").val();
	var password = $("input#regpassword").val();
	var passwordconf = $("input#regpasswordconf").val();	
	$.ajax( {
		type : 'POST',
		data : 'request=register&username=' + username + '&password=' + password +'&passwordconf='+passwordconf,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response == 1) {
				alert("Success!");
				$("div#sign-in").modal('hide');
			} else {
				login_content.html(response);
			}
		},
		error : function() {
			alert("Error with logout!");
		}
	});
});


$("a#StartExperimentButton").on("click",function(){
	var board =  $('#boardExperiment :selected').attr('boardid');
	var stimid = $('#experi_set :selected').attr('id');
	var grid = $('#gridInputDropdown :selected').attr('value');
	var coverBoard = $("input#exprCover").is(":checked");
	var exprname = $("input#experiment_name").val();
	if(!exprname){
		alert("You must set an experiment name!");
		return;
	}
	$.ajax( {
		type : 'POST',
		data : 'request=createexperiment&board=' + board + '&stimid=' + stimid +'&grid='+grid+"&cover="+coverBoard+"&title="+exprname,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response.includes("Error")) {
				alert(response);
			} else {
				window.location.href = "./SOSA.html?token="+response;
			}
		},
		error : function() {
			alert("Error with logout!");
		}
	});
});

/**
 * When dropdown changed, loads the simuli under the selected set into the individual stimulus dropdown
 */
$("#stimulus-set").on("change", function(){
	loadCreatorStimulus();
});

/**
 * When dropdown changed, loads the simuli under the selected set into the individual stimulus dropdown
 */
$("#experi_set").on("change", function(){
	loadExprStimulus();
});