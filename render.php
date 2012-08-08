<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="fr" xml:lang="fr">
<head>
    <title>Coin !</title>
</head>
<body>
<h3>NxNxN ImageCube</h3>
Size:<input type="text" id="size" value="5" size="1"/>
Algorithm:<input type="text" id="algo"/>
<input type="button" value="render" onclick="document.getElementById('render').src='nimagecube/imagecube_final.php?size='+document.getElementById('size').value+'&algo='+document.getElementById('algo').value;" /><br/>
<img src="nimagecube/imagecube_final.php?size=5" border="0" id="render"/>
<h3>Square-1 BTC</h3>
Algorithm:<input type="text" id="sequence"/>
<input type="button" value="render" onclick="document.getElementById('rendersq1').src='square/btc.php?sequence='+document.getElementById('sequence').value;" /><br/>
<img src="square/btc.php" border="0" id="rendersq1"/>
</body>
</html>