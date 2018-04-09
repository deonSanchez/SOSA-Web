var login_content = $("div#login_content");
var color_selection = -1;
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
 * This document ready function fires when the page is loaded, it checks the api to see if the user is logged in. 
 * If they are logged in, it hides the login/register forms and displays a message and a logout button.
 * @returns {undefined}
 */
$(function () {
    //this part deals with login check
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
    //this part deals with loading initial stimulus set list
    $.ajax( {
		type : 'POST',
		data : 'request=createset&set_name='+ set_name,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response == 1) {
				alert("Stimulus set added!");
				$("select#stimulus-set").append("<option>"+set_name+"</option>");
			} else {
				alert("Cannot create new stimulus set with name of another set OR with non-alphanumeric name!");
			}
		},
		error : function() {
			alert("Error with create stimulus!");
		}
	});
});

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

/**
 * This runs when the create stimulus button at the top of the modal is clicked
 */
$("button#create_stimulus").on('click',function(){
	var stimulus_name = $("input#stimulus_name").val();
	var peg_r = $("input#RvalueStim").val();
	var peg_g = $("input#GvalueStim").val();
	var peg_b = $("input#BvalueStim").val();
	var set_title = $('#stimulus-set :selected').text();
	$.ajax( {
		type : 'POST',
		data : 'request=createstimulus&label='+ stimulus_name 
		+ '&peg_r=' + peg_r +'&peg_g='+peg_g+'&peg_b='+peg_b
		+'&label_r=' + peg_r +'&label_g='+peg_g+'&label_b='+peg_b
		+'&set_title='+set_title,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			alert(response);
		},
		error : function() {
			alert("Error with create stimulus!");
		}
	});
});

$("button#remove").on('click',function(){
	var selected = $('#stimulus-set :selected').text();
});

/**
 * Adds a new stimulus set to the dropdown and database
 */
$("button#add").on('click',function(){
	var set_name = $("input#set_name").val();
	$.ajax( {
		type : 'POST',
		data : 'request=createset&set_name='+ set_name,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response == 1) {
				alert("Stimulus set added!");
				$("select#stimulus-set").append("<option>"+set_name+"</option>");
			} else {
				alert("Cannot create new stimulus set with name of another set OR with non-alphanumeric name!");
			}
		},
		error : function() {
			alert("Error with create stimulus!");
		}
	});
});

$("button#save").on('click',function(){
	var set_title = $('#stimulus-set :selected').text();
});

/**
 * When dropdown changed, loads the simuli under the selected set into the individual stimulus dropdown
 */
$("#stimulus-set").on("change", function(){
	var set_title = $('#stimulus-set :selected').text();
	$.ajax( {
		type : 'POST',
		data : 'request=loadstimset&set_title='+set_title,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			var json = JSON.parse(response);
			var len = objLength(json);
			var appendLabel = "";
			for (var i = 0; i < len; i++) {
				appendLabel = appendLabel + "<option>"+json[i].label+"</option>";
			}
			$("select#individual_stimulus").html(appendLabel);
		},
		error : function() {
			alert("Error with create stimulus!");
		}
	});
});

$("button#load").on("click", function(){
});

$("button#peg").on("click", function() {	
});

$("button#label").on("click", function() {
});