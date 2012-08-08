<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<title>Optimal Back To Cube Solver</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript">
		function onChangeUpEdges() {
			var v = document.forms[0].upedges.value;
			for(var i = 0 ; i <= 8 ; i+=2) {
				var element = document.getElementById("shapesup"+i);
				if(i == v) {
					element.style.display = 'block';
					element = document.getElementById("shapesup"+i+"radio");
					element.checked = true;
				} else
					element.style.display = 'none';
				element = document.getElementById("shapesdown"+i);
				if(i == 8-v) {
					element.style.display = 'block';
					element = document.getElementById("shapesdown"+i+"radio");
					element.checked = true;
				} else
					element.style.display = 'none';
			}
		}
		function radioValue(radio) {				
	      for (var i=0; i<radio.length;i++) {
	         if (radio[i].checked) {
	            return radio[i].value;
	         }
	      }
		}
		function compute(up, down) {
			up = up.replace(/c/g, "cC");
			down = down.replace(/c/g, "cC");
			document.getElementById("result").src = "computebtc.php?up="+up+"&down="+down;
		}			
	</script>
</head>
<body>
<center><h1>Optimal Square-One BTC</h1></center>
<form>
Number of edges on U: <select name="upedges" onChange="onChangeUpEdges()"><option>0</option><option>2</option><option selected="selected">4</option><option>6</option><option>8</option></select>
<?php
function generateDivs($id) {
	for($i = 0 ; $i <= 8 ; $i += 2) {
		echo "<div id='shapes".$id.$i."'>";
		$dir = "shapes/".$i;
		$files = scandir($dir);
		echo "<table width='100%' border='0'><tr>";
		$first = 1;
		foreach($files as $shape) {
			if(strpos($shape, "gif") === FALSE) continue;
			echo "<td align='center'><img src='shapes/".$i."/".$shape."' /><br/><input type='radio' name='".$id."' value='".str_replace(".gif", "", $shape)."' ".(first ? "id='shapes".$id.$i."radio'" : "")."/></td>";
			$first = 0;
		}
		echo "</tr></table>";
		echo "</div>";
	}
}
generateDivs("up");
generateDivs("down");
?>

	<script type="text/javascript">onChangeUpEdges();</script><center>
<input type="button" value="Compute" onclick="compute(radioValue(this.form.up),radioValue(this.form.down))"/><br/>
<img id='result' src="computebtc.php?up=cCecCecCecCe&down=ecCecCecCecC"/></center>
</form>
</body>