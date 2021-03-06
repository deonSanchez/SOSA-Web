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
			if(response == 1) {
				loadCreatorStimulus();
				$('#invididual_stimulus').val(stimulus_name);
			} else {
				alert(response);
			}
		},
		error : function() {
			alert("Error with create stimulus!");
		}
	});
});

/**
 * load stimulus data into form for change or inspection
 */
$("button#load").on("click", function(){
	var stimulus_id = $("select#individual_stimulus :selected").attr('id');
	$.ajax( {
		type : 'POST',
		data : 'request=loadstimulus&stimulus_id='+ stimulus_id,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			var json = JSON.parse(response);
			//set the field values to reflect current internal values
			var stimulus_name = json[0].label;
			var peg_r = json[0].peg_r;
			var peg_g = json[0].peg_g;
			var peg_b = json[0].peg_b;
			var label_r = json[0].label_r;
			var label_g = json[0].label_g;
			var label_b = json[0].label_b;
			console.log(json);
			$("input#stimulus_name").val(stimulus_name);
			
			$("input#RvalueStim").val(peg_r);
			$("span#sR").val(peg_r);
			$("input#GvalueStim").val(peg_g);
			$("span#sG").val(peg_g);
			$("input#BvalueStim").val(peg_g);
			$("span#sB").val(peg_b);
			setPeg(peg_r,peg_g,peg_b);

			$("input#lRvalueStim").val(label_r);
			$("span#lR").val(label_r);
			$("input#lGvalueStim").val(label_g);
			$("span#lB").val(label_g);
			$("input#lBvalueStim").val(label_b);
			$("span#lG").val(label_b);
			setLabel(label_r,label_g,label_b);
			
			setLabelValue(stimulus_name);
			$("input#selected_stimulus").val(stimulus_id) 
		},
		error : function() {
			alert("Error with load stimulus!");
		}
	});
});

/**
 * Saves stimulus updates
 */
$("button#save").on('click',function(){
	var set_title = $('#stimulus-set :selected').text();
	var stimulus_name = $("input#stimulus_name").val();
	var peg_r = $("input#RvalueStim").val();
	var peg_g = $("input#GvalueStim").val();
	var peg_b = $("input#BvalueStim").val();

	var label_r = $("input#lRvalueStim").val();
	var label_g = $("input#lGvalueStim").val();
	var label_b = $("input#lBvalueStim").val();
	var stimulus_id = $("input#selected_stimulus").val();
	$.ajax( {
		type : 'POST',
		data : 'request=updatestimulus&set_name='+ stimulus_name+'&peg_r='
			+peg_r+'&peg_g='+peg_g+'&peg_b='+peg_b+'&stimulus_id='+stimulus_id+'&label_r='
			+label_r+'&label_g='+label_g+'&label_b='+label_b,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response!=1) {
				alert(response);
			} else {
				alert("Stimulus successfully updated!");
				loadCreatorStimulus();
			}
		},
		error : function() {
			alert("Error with save stimulus!");
		}
	});
	
});

/**
 * Handles deleting individual stimulus
 */
$("button#remove_stim").on("click", function() {	
	var stimulus_id = $("select#individual_stimulus :selected").attr('id');
	$.ajax( {
		type : 'POST',
		data : 'request=deletestimulus&stimulus_id='+ stimulus_id,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response!=1) {
				alert(response);
			} else {
				loadCreatorStimulus();
			}
		},
		error : function() {
			alert("Error with delete stimulus!");
		}
	});
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
				$("select#stimulus-set").prepend("<option>"+set_name+"</option>");
			} else {
				alert(response);
			}
		},
		error : function() {
			alert("Error with add stimulus!");
		}
	});
});

/**
 * Deletes the currently selected stimulus set from the database
 */
$("button#remove").on('click',function(){
	var selected = $('#stimulus-set :selected').text();
	$.ajax( {
		type : 'POST',
		data : 'request=deleteset&set_name='+ selected,
		url : 'api/index.php',
		async : true,
		success : function(response) {
			if(response == 1) {
				loadStimulusSets(loadCreatorStimulus);
			} else {
				alert(response);
			}
		},
		error : function() {
			alert("Error with remove stimulus!");
		}
	});
});
/**
 * This loads all the stimulus sets then loads the selected set's entries
 * @param callback
 */
function loadStimulusSets(callback) {
    $.ajax( {
		type : 'POST',
		data : 'request=loadstimsets',
		url : 'api/index.php',
		async : true,
		success : function(response) {
			var json = JSON.parse(response);
			var len = objLength(json);
			var appendLabel = "";
			for (var i = 0; i < len; i++) {
				appendLabel = appendLabel + "<option id="+json[i].stimset_id+">"+json[i].title+"</option>";
			}
			$("select#stimulus-set").html(appendLabel);
			$("select#experi_set").html(appendLabel);
			if(typeof callback === 'function') {
				callback();
			}
		},
		error : function() {
			alert("Error with loading stimulus sets!");
		}
	});
}

/***
 * This loads all the stimulus sets into the modal dropdown
 */
function loadCreatorStimulus() {
	var set_title = $('#stimulus-set :selected').text();
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
				$("select#individual_stimulus").html(appendLabel);
			} else {
				$("select#individual_stimulus").html("");
			}
		},
		error : function() {
			alert("Error with loading invididual stimulus into editor!");
		}
	});
}