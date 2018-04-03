var login_content = $("div#login_content");
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

$("a#log-out-nav").on('click', function() {	$.ajax( {
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
})

/**
 * This document ready function fires when the page is loaded, it checks the api to see if the user is logged in. 
 * If they are logged in, it hides the login/register forms and displays a message and a logout button.
 * @returns {undefined}
 */
$(function () {
    //jQuery ajax call to the api
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
});