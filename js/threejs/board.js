var frameId = 0;
var scene, camera, renderer, center, container;
var board, material;
init();

var r = document.querySelector('#r'),
    g = document.querySelector('#g'),
    b = document.querySelector('#b'),

    r_out = document.querySelector('#r_out'),
    g_out = document.querySelector('#g_out'),
    b_out = document.querySelector('#b_out'),
    hex_out = document.querySelector('#hex');

function setColor(){
  var r_hex = parseInt(r.value, 10).toString(16),
  g_hex = parseInt(g.value, 10).toString(16),
  b_hex = parseInt(b.value, 10).toString(16),
  hex = pad(r_hex) + pad(g_hex) + pad(b_hex);

  board.material.color.setHex('0x'+hex);
  redraw();
}

function pad(n){
  return (n.length<2) ? "0"+n : n;
}

r.addEventListener('change', function() {
  setColor();
  r_out.value = r.value;
}, false);

r.addEventListener('input', function() {
  setColor();
  r_out.value = r.value;
}, false);

g.addEventListener('change', function() {
  setColor();
  g_out.value = g.value;
}, false);

g.addEventListener('input', function() {
  setColor();
  g_out.value = g.value;
}, false);

b.addEventListener('change', function() {
  setColor();
  b_out.value = b.value;
}, false);

b.addEventListener('input', function() {
  setColor();
  b_out.value = b.value;
}, false);

function init() {
  container = document.getElementById("ThreeJSboard");

  //creates the scene
  scene = new THREE.Scene();
  scene.background = new THREE.Color("rgb(0, 0 , 0)");

  //Camera
  camera = new THREE.PerspectiveCamera(35, 1, 1, 1000);
  center = new THREE.Vector3();
  camera.up = new THREE.Vector3(0, 0, 1);
  camera.position.set(200,0,0);
  camera.lookAt(center);

  //Board
  var geometry = new THREE.BoxGeometry( 2.5, 100, 100 );;
  material = new THREE.MeshPhongMaterial();
  board = new THREE.Mesh(geometry, material);
  scene.add(board);

  //Light Point
  var pointLight = new THREE.PointLight(0xFFFFFF, 1);
  pointLight.position.set(90,90,45);
  scene.add(pointLight);

  //Ambient
  var ambiColor = "0xFFFFFF";
  var ambient = new THREE.AmbientLight(ambiColor, .5);
  scene.add(ambient);

  //Axis Helper
  var axisHelper = new THREE.AxisHelper(750);
  scene.add(axisHelper);

  renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.setSize(250, 250);
  container.appendChild(renderer.domElement);


  Controls.addMouseHandler(renderer.domElement, drag);
  redraw();
}

  //drag movement
function drag(deltaX, deltaY) {
  var radPerPixel = (Math.PI / 450),
  deltaPhi = radPerPixel * deltaX,
  deltaTheta = radPerPixel * deltaY,
  pos = camera.position.sub(center),
  radius = pos.length(),
  theta = Math.acos(pos.z / radius),
  phi = Math.atan2(pos.y, pos.x);

  // Subtract deltaTheta and deltaPhi
  theta = Math.min(Math.max(theta - deltaTheta, 0), Math.PI);
  phi -= deltaPhi;

  // Turn back into Cartesian coordinates
  pos.x = radius * Math.sin(theta) * Math.cos(phi);
  pos.y = radius * Math.sin(theta) * Math.sin(phi);
  pos.z = radius * Math.cos(theta);

  camera.position.add(center);
  camera.lookAt(center);
  redraw();
  }


  function render() {
    renderer.render(scene, camera);
  }

  function redraw() {
    cancelAnimationFrame(frameId);
    frameId = requestAnimationFrame(render);
  }
