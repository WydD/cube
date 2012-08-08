<h1>État d'un cube</h1>
<style type="text/css">
#cubetdcontainer {
	background-color: #ffffff !important;
	cursor:crosshair;
}
table.cube tr td {
	text-align: center;
	background-color: #cccccc;
	border: 1px solid #777777;
}
table.cube {
	cellspacing: 0px;
	border-collapse: collapse ;
}
td[onclick] {
	cursor:pointer;
}
</style>
<script type="text/javascript" src="js/jPlex.js"></script>
<script type="text/javascript">
jPlex.include("jplex.xprototype.*");
var mode = 0;
document.observe("dom:loaded", function() {
	$('cubeimg').observe('click', function(event) {
		var e = Event.element(event);
		var x = event.pointerX();
		var y = event.pointerY();
		var offset = e.viewportOffset();
		$('result').innerHTML = "";
		// Test U
		var face = false;
		var idSticker = false;
		var testFace = affineTransform(0,1,2,3,x-offset.left,y-offset.top,e.getWidth());
		if(testFace[0] >= 0 && testFace[0] <= 3 && testFace[1] >= 0 && testFace[1] <= 3) {
			face = 5;// "U"
			idSticker = Math.floor(testFace[0])+Math.floor(testFace[1])*3;
		}
		testFace = affineTransform(2,3,4,5,x-offset.left,y-offset.top,e.getWidth());
		if(testFace[0] >= 0 && testFace[0] <= 3 && testFace[1] >= 0 && testFace[1] <= 3) {
			face = 0; //"F";
			idSticker = Math.floor(testFace[0])+Math.floor(testFace[1])*3;
		}
		testFace = affineTransform(3,1,5,6,x-offset.left,y-offset.top,e.getWidth());
		if(testFace[0] >= 0 && testFace[0] <= 3 && testFace[1] >= 0 && testFace[1] <= 3) {
			face = 4; //"R";
			idSticker = Math.floor(testFace[0])+Math.floor(testFace[1])*3;
		}
		if(face === false || idSticker === false)
			return;
		var cubie = getCubie(face, idSticker);
		sendAction(cubie);
		//$('result').innerHTML += "Face: "+face+"<br/>Sticker: "+idSticker+"<br/>Cubie: "+cubie;
	});
	['solve', 'delete', 'orient', 'place'].each(function(s) {
		$('button'+s).observe('click', changeMode);
	});
});
function changeMode(event) {
	var e = Event.element(event);
	mode = e.id.gsub('button','');
	['solve', 'delete', 'orient', 'place'].each(function(s) {
		$('button'+s).style.backgroundColor = "#cccccc";
	});
	e.style.backgroundColor = '#ff6600';
}
function sendAction(cubieId) {
	if(mode == 0)
		return;
	var params = {
			id: 1,
			action: "i",
			entity: "cubiestate",
			cubie: cubieId,
		};
	if(mode == 'delete')
		params['action'] = "d";
	if(mode == 'place' || mode == 'solve')
		params['placed'] = 1;
	if(mode == 'orient' || mode == 'solve')
		params['oriented'] = 1;
	
	new Ajax.Request("db/query.php", {
		method: 'post',
		parameters: params,
		onSuccess: function() {
			rotateCube(0,0,0);
		}
	});
}


/** 
  0  1
2  3 
     6
4  5 
*/
var coordPointCube = [[89,18], [200,31], [14,59], [138,77], [29,185], [147,211], [206, 151]];
///"F"=>0,"L"=>1, "D"=>2, "B"=>3, "R"=>4, "U"=>5
var cubieStickers = {
	1: [5*9+6, 0*9+0, 1*9+2],
	2: [5*9+0, 3*9+6, 1*9+0],
	3: [5*9+2, 3*9+8, 4*9+2],
	4: [5*9+8, 0*9+2, 4*9+0],
	5: [2*9+0, 0*9+6, 1*9+8],
	6: [2*9+6, 3*9+0, 1*9+6],
	7: [2*9+8, 3*9+2, 4*9+8],
	8: [2*9+2, 0*9+8, 4*9+6],
	U: [0*9+4],
	V: [1*9+4],
	W: [2*9+4],
	X: [3*9+4],
	Y: [4*9+4],
	Z: [5*9+4],
	A: [5*9+7, 0*9+1],
	B: [5*9+3, 1*9+1],
	C: [5*9+1, 3*9+7],
	D: [5*9+5, 4*9+1],
	E: [0*9+3, 1*9+5],
	F: [3*9+3, 1*9+3],
	G: [3*9+5, 4*9+5],
	H: [0*9+5, 4*9+3],
	I: [2*9+1, 0*9+7],
	J: [2*9+3, 1*9+7],
	K: [2*9+7, 3*9+1],
	L: [2*9+5, 4*9+7]
};
function getCubie(face, idSticker) {
	var search = face*9+idSticker;
	var result;
	$H(cubieStickers).each(function(pair) {
		if($A(pair.value).member(search)) {
			result = pair.key;
			throw $break;
		}
	});
	// Apply orientation
	if(result.charCodeAt(0) <= "8".charCodeAt(0))
		result = String.fromCharCode(corners[result.charCodeAt(0)-"1".charCodeAt(0)]+"1".charCodeAt(0));
	else if(result.charCodeAt(0) <= "L".charCodeAt(0))
		result = String.fromCharCode(edges[result.charCodeAt(0)-"A".charCodeAt(0)]+"A".charCodeAt(0));
	else if(result.charCodeAt(0) <= "U".charCodeAt(0))
		result = String.fromCharCode(faceOrient[result.charCodeAt(0)-"U".charCodeAt(0)]+"U".charCodeAt(0));
	return result;
}

// Compute the transformation matrix, returns an object {norm: the normalization constant, matrix: the matrix itself}
function getMatrix(xAxis, yAxis, origin, size) {
	var r = size/220;
	var VXx = coordPointCube[xAxis][0];
	var VXy = coordPointCube[xAxis][1];
	var VYx = coordPointCube[yAxis][0];
	var VYy = coordPointCube[yAxis][1];
	var ox = coordPointCube[origin][0];
	var oy = coordPointCube[origin][1];
	var normValue = (VXx-ox)*(VYy-oy)-(VXy-oy)*(VYx-ox);
	return {norm: normValue*r, matrix: [[VYy-oy, oy-VXy],[ox-VYx,VXx-ox]]};
}
function affineTransform(origin, xAxis, yAxis, nPoint, x, y, size) {
	var ox = coordPointCube[origin][0]*size/220;
	var oy = coordPointCube[origin][1]*size/220;
	// Return 1/r * M with r constant and M matrix 
	var m = getMatrix(xAxis, yAxis, origin, size);
	var npx = coordPointCube[nPoint][0]*size/220;
	var npy = coordPointCube[nPoint][1]*size/220;
	
	// Compute the 4th point in the new origin
	// M*([npx,npy] - [ox,oy])/r
	var nratio = [
		((npx-ox)*m.matrix[0][0]+(npy-oy)*m.matrix[1][0])/m.norm, 
		((npx-ox)*m.matrix[0][1]+(npy-oy)*m.matrix[1][1])/m.norm
	];
	// Returns the (x,y) point in the new origin with the ratio on x and y to correctly fit
    // M*[[nratio[0],0], [0,nratio[1]]]*([npx,npy] - [ox,oy])/r
	return [
		((x-ox)*m.matrix[0][0]+(y-oy)*m.matrix[1][0])*3/(m.norm*nratio[0]),
		((x-ox)*m.matrix[0][1]+(y-oy)*m.matrix[1][1])*3/(m.norm*nratio[1])
	];
}
var faceOrient = [0,1,2,3,4,5]; // F front, T top
var corners = [0,1,2,3,4,5,6,7]; // F front, T top
var edges = [0,1,2,3,4,5,6,7,8,9,10,11]; // F front, T top
var cubeOrient = [0,0,0];
function fourCycle(tab, i, j, k, l) {
	var tmp = tab[i];
	tab[i] = tab[j];
	tab[j] = tab[k];
	tab[k] = tab[l];
	tab[l] = tmp;
}
function modifier(value, cprime, cmove, cdouble, base) {
	switch(value) {
		case cprime:
			return base+"z'";
		case cmove:
			return base+"z";
		case cdouble:
			return base+"z2";
	}
	return base;
}
function rotateCube(x,y,z) {
	while(x-- > 0) {
		fourCycle(faceOrient, 0,2,3,5);
		fourCycle(corners, 0,4,5,1);
		fourCycle(corners, 3,7,6,2);
		fourCycle(edges, 0,8,10,2);
		fourCycle(edges, 1,4,9,5);
		fourCycle(edges, 3,7,11,6);
	}
	while(y-- > 0) {
		fourCycle(faceOrient, 0,4,3,1);
		fourCycle(corners, 0,3,2,1);
		fourCycle(corners, 4,7,6,5);
		fourCycle(edges, 0,3,2,1);
		fourCycle(edges, 4,7,6,5);
		fourCycle(edges, 8,11,10,9);
	}
	
	var alg = "";
	switch(faceOrient[0]) {
		case 0:
			alg = modifier(faceOrient[5], 4, 1, 2, "");
			break;
		case 1:
			alg = modifier(faceOrient[5], 0, 3, 2, "y'");
			break;
		case 2:
			alg = modifier(faceOrient[5], 4, 1, 3, "x");
			break;
		case 3:
			alg = modifier(faceOrient[5], 4, 1, 5, "x2");
			break;
		case 4:
			alg = modifier(faceOrient[5], 3, 0, 2, "y");
			break;
		case 5:
			alg = modifier(faceOrient[5], 4, 1, 0, "x'");
			break;
	}
	$("cubeimg").src="imagecube.php?state=1&algo="+alg+"&date=" + new Date().getTime();
}
</script>
<h2>Editer</h2>
<table border="0" class="cube">
<tr style="cursor:default;"><td id="buttonsolve">Résoudre</td><td id="buttonplace">Placer</td><td id="buttonorient">Orienter</td><td id="buttondelete">Supprimer</td></tr>
</table>
<table border="0" id="cube" class="cube">
<tr><td></td><td onclick="rotateCube(1,0,0);"><img src="img/arrow.gif"/></td><td></td></tr>
<tr><td onclick="rotateCube(0,1,0);"><img src="img/arrow-left.gif"/></td><td id="cubetdcontainer"><img id="cubeimg" src="imagecube.php?state=1"/></td><td onclick="rotateCube(0,3,0);"><img src="img/arrow-right.gif"/></td>
<tr><td></td><td onclick="rotateCube(3,0,0);"><img src="img/arrow-down.gif"/></td><td></td></tr>
<p id="result"></p>
<table border="0">
	<tr>
		<td>Setup: </td><td><input type="text" name="setup"/></td>
	</tr><tr>
		<td>Display orientation: </td><td><input type="checkbox" name="displayOrientFaces"/></td>
	</tr>
</table>