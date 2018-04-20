//Author: Dan Blocker
//Save Board AJAX POST
//TODO: Take data once the save button is clicked
var boardName = null;
var lockTilt = false;
var lockRotate = false;
var lock_zoom = false;
var board_color = " ";
var backgroundColor = " ";
var cover_Color = " ";
var image = null;

var fullColorHex = function(r,g,b) {   
	var red = rgbToHex(r);
	var green = rgbToHex(g);
	var blue = rgbToHex(b);
	return red+green+blue;
};
$("button#saveCreatedBoard").on('click', function(){
	boardName = $("input#board_name").val();
	lockTilt = $("input#tiltSet").is(":checked");
	lockRotate = $("input#rotateSet").is(":checked");
	lock_zoom = $("input#zoomSet").is(":checked");
	cover_board = $("input#coverSet").is(":checked");

    var camerax = BoardCamera.position.x;
    var cameray = BoardCamera.position.y;
    var cameraz = BoardCamera.position.z;
	
	board_color = [$("#BoardRvalue").val(), $("#BoardGvalue").val() , $("#BoardBvalue").val()];
	backgroundColor = [$("#BackgroundRvalue").val(), $("#BackgroundGvalue").val() , $("#BackgroundBvalue").val()];
	cover_Color = [$("#CoverRvalue").val(), $("#CoverGvalue").val(), $("#CoverBvalue").val()];
	
	var hexBoard = fullColorHex(board_color[0],board_color[1],board_color[2]);
	var hexBackground = fullColorHex(backgroundColor[0],backgroundColor[1], backgroundColor[2]);
	var hexCover = fullColorHex(cover_Color[0], cover_Color[1], cover_Color[2]);

	image = null;
	var json = {"boardName": boardName, "lock_tilt": lockTilt, "lock_rotate": lockRotate, "lock_zoom": lock_zoom, "board_color": hexBoard,
			"background_color": hexBackground, "cover_color": hexCover, "image": image, "cover_board": cover_board,"camerax" : camerax, "cameray" : cameray, "cameraz" : cameraz};
	var packet = JSON.stringify(json);
	
		$.ajax({
			type: 'POST',
			data: 'request=saveboard&board=' + packet,
			url: 'api/index.php',
			async : true,
			dataType: 'json',
			success: function (json) {
				if($('#boardImg').val()) {
					var file_data = $('#boardImg').prop('files')[0];   
					var form_data = new FormData();                  
					form_data.append('file', file_data);
					form_data.append('request','uploadboardimg');
					form_data.append('board_name',boardName);
					alert(form_data);    
					$.ajax({
						url: 'api/index.php',
						dataType: 'text',
						cache: false,
						contentType: false,
						processData: false,
						data: form_data,                         
						type: 'post',
						success: function(php_script_response){

						loadBoardModal();
						}
					});
				} else {

					loadBoardModal();
				}
			},
			error: function() {
				alert("Failed!");
			}
	});
});

$("button#loadCreatedBoard").on('click', function(){
	var boardid = $('#boards :selected').attr('boardid');
	$.ajax({
		type: 'POST',
		data: 'request=loadboard&board=' + boardid,
		url: 'api/index.php',
		async : true,
		dataType: 'json',
		success: function (json) {
			var len = objLength(json);
			
			var background_hex = json[0].background_color;
			var board_hex = json[0].board_color;
			var board_name  = json[0].board_name;
			var cover_hex = json[0].cover_color;
			var lock_tilt =  json[0].lock_tilt;
			var lock_rotate =  json[0].lock_rotate;
			var lock_zoom =  json[0].board_color;
			var cover_board = json[0].cover_board;
			var camerax =  json[0].camerax;
			var cameray =  json[0].cameray;
			var cameraz =  json[0].cameraz;
			
			$("input#tiltSet").prop('checked', Boolean(lock_tilt));
			$("input#rotateSet").prop('checked', Boolean(lock_rotate));
			$("input#zoomSet").prop('checked', Boolean(lock_zoom));

			if(Boolean(lock_tilt))
				setTiltLock();
			if(Boolean(lock_rotate))
				setRotLock();
			if(Boolean(lock_zoom))
				setZoomLock();
			
			BoardCamera.position.set(camerax,cameray,cameraz);
			boardName = $("input#board_name").val(board_name);
			Boardmaterial.color.setHex(background_hex);
			skyBoxMaterial.color.setHex(background_hex);
			if(cover_board == 1) {
				Boardmaterial.color.setHex(cover_hex);
			}
			
			/*for (var i = 0; i < lend; i++) {
				appendLabel = appendLabel + "<option>"+json[i].title+"</option>";
			}*/
		},
		error: function() {
		}
	});
});





