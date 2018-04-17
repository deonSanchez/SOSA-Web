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
boardName = $("#boardName").val();
lockTilt = $("#tiltSet").is('checked');
lockRotate = $("#rotateSet").is('checked');
lock_zoom = $("#zoomSet").is('checked');
board_color = [$("#BoardRvalue").val(), $("#BoardGvalue").val() , $("#BoardBvalue").val()];
backgroundColor = [$("#BackgroundRvalueRvalue").val(), $("#BackgroundGvalue").val() , $("#BackgroundBvalue").val()];
cover_Color = [$("#CoverRvalue").val(), $("#CoverGvalue").val(), $("#CoverBvalue").val()];
image = null;

var hexBoard = fullColorHex(board_color[0],board_color[1],board_color[2]);
var hexBackground = fullColorHex(backgroundColor[0],backgroundColor[1], backgroundColor[2]);
var hexCover = fullColorHex(cover_Color[0], cover_Color[1], cover_Color[2]);


function sendBoard(){
        var json = {"boardName": boardName, "lock_tilt": lockTilt, "lock_rotate": lockRotate, "lock_zoom": lock_zoom, "board_color": hexBoard,
            "background_color": hexBackground, "cover_color": hexCover, "image": image};

        var packet = JSON.stringify(json);

        $.ajax({
            type: 'POST',
            data: 'request=saveboard&board=' + packet,
            url: 'api/index.php',
            async: true,
            cache: false,
            dataType: 'json',
            success: function (response) {
                var board = JSON.parse()
                alert('Success');
            },
            error: function () {
                alert('Failed');
            }
        });
}




