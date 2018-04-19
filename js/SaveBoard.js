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
		success: function () {
		},
		error: function() {
		}
	});
});





