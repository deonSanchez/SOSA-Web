//Author: Dan Blocker
//Add an image to the board
//TODO: code a import image function to the plane object

$("#addImage").click(function () {
    var loader = new THREE.ImageLoader();
    var preview = document.querySelector('img');
    var image = document.querySelector('input[type=file]').files[0];
    var reader = new FileReader();

    reader.onloadend = function(){
        preview.src = reader.result;
    }

    if(image){
        reader.readAsDataURL(image);
    }
    else {
        preview.src = " ";
    }
});