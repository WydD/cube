<?php
function ep($top, $bottom, $sequence) {
	?>
	<table border="0" align="center" style='border: 1px solid black;'><tr><td align="right"><img border="0" src="ep.php?perm=<?php echo $top; ?>"/></td><td><img border="0" src="ep.php?perm=<?php echo $bottom; ?>"/></td></tr><tr align="center"><td colspan="2"><?php echo $sequence; ?></td></tr></table>
	<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<title>Méthode avancée Square-1 : Etape 5, Placement des arêtes</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<div style="float: left;"><a href="step_4.html">Étape précédente</a></div>
<div style="float: right;"><a href="step_6.html">Étape suivante</a></div>
<div style="clear:both;"/>
<h1>Square-1 : méthode de résolution avancée</h1>
<h2>Cinquième étape : Placement des arêtes</h2>
<p>Cette étape est une de celle qui prend le plus de temps et qui serait assimilable au dernier étage du 3x3. Le principe reste simple si on veut apprendre la liste complète des permutations. Le problème étant qu'il y en a beaucoup au total (une centaine en fait, symétries haut/bas comprise).</p>

<p>Je montrerai ici comment augmenter son ensemble de séquences progressivement en ayant au fur et à mesure le moins de choses à faire. La liste complète des cas peut se trouver ici <a href="http://www.cubezone.be/square1step5.html">CubeZone: Square-1 EP</a></p>

<p>En tant que préambule, je rappelle que le square-1 est un cube à parité, car il change de forme et le cube peut ne pas être entièrement résolvable en conservant la forme cubique. Ces cas-là peuvent se repérer si vous êtes notamment au courant des EPLL de Fridrich 3x3 par exemple. Si un seul des étages a une EPLL inconnue, alors il y a parité. Ces cas-là sont un peu plus longs à exécuter la plupart du temps.</p>
<h3>Le kit vraiment minimal</h3>

<p>Résoudre la fin d'un square-1, c'est soit faire preuve de patience et apprendre l'ensemble des cas, soit se contenter de peu et utiliser un peu de ruse. Le kit minimal pour savoir résoudre <em>efficacement</em> un square-1 se décompose en deux séquences principales ainsi qu'une parité.</p>
<table width="100%"><tr align="center"><td width="25%">

<b>Double Opposés</b><br/>
<?php ep('1,3', '1,3', "(1,0)/(-1,-1)/(6,0)/(1,1)/(5,0)") ;?>

</td><td width="25%">

<b>Double Adjacent</b><br/>
<?php ep('1,2', '2,3', "(-2,0)/(0,3)/(-1,-1)/(1,-2)/(2,0)") ;?>

</td><td width="50%">

<b>Parité Adjacent</b><br/>
<?php ep('1,4', '', "/(-3,0)/(0,3)/(0,-3)/(0,3)/(2,0)/(0,2)/(-2,0)/(4,0)/(0,-2)/(0,2)/(-1,4)/(0,-3)/(0,3)") ;?>

</td></tr></table>

<p>Quelques commentaires sur ceci. Tout d'abord, si vous n'avez pas de parité. Tout le cube peut se résoudre facilement avec les deux premières séquences. Leur exécution est extrêmement rapide et elles se comprennent bien en voyant que les mouvements tels que /(1,1)/ sont des <em>M2</em>. Ainsi, la première séquence pourrait se traduire sur un 3x3 par <em>M2 U2 M2 U2</em>. Et la deuxième par <em>U' R2 D M2 D' R2 U</em></p>

<p>Le double adjascent peut se passer <em>par derrière</em> aussi : (-2,0)/(3,0)/(-1,-1)/(-2,1)/(2,0). Ceci évite de faire des réalignements vraiment contraignants tels que (6,6) qui n'est pas très sympathique à passer.</p>

<p>Mon choix de parité n'a pas été facile, car plusieurs parités existent et ce n'est pas la plus courte. Par contre, elle peut passer de façon très rapide même sous stress, car les mouvements sont très automatiques. Les seuls mouvements possibles à chaque fois sont souvent les bons ce qui évite des erreurs. De plus, il respecte bien l'enchainement des mouvements de la main.</p>

<p>Vous accorderez une grande attention à la résolution de la parité. Suivant où vous l'appliquerez, ses implications ne seront pas les mêmes. Quitte à faire une parité autant vous épargner du boulot, non ?</p>

<h3>Les combinaisons classiques avec le kit minimal</h3>
Voici ci dessous, une liste non exhaustive de combinaisons que l'on peut faire avec ce petit kit. 
<ul>
<li><b>Double U</b> (i.e. double trois-cycles) : Double Opposés, puis Double Adjacent. Notez que le principe est le même pour toutes les sortes de 3-cycles. Il vous suffit de mettre les barres à droite ou à gauche.<br/>
<?php ep('1,3,2', '1,3,2', "(1,0)/(-1,-1)/(6,0)/(1,1)/ . (-3,-3) . /(3,0)/(-1,-1)/(-2,1)/(-4,3)"); ?>
</li>
<li><b>Double Z</b> : Double Adjascent deux fois (de plus des mouvements s'annulent en faisant le deuxième par derrière)<br/>
<?php ep('1,2|3,4', '1,4|2,3', "(-2,0)/(0,3)/(-1,-1)/ . (4,-2) . /(-1,-1)/(-2,1)/(2,0)"); ?></li>
<li><b>Double H</b> : Double Opposés deux fois avec un (3,-3) inséré entre les deux<br/>
<?php ep('1,3|2,4', '1,3|2,4', "(1,0)/(-1,-1)/(6,0)/(1,1)/ . (3,-3) . /(-1,-1)/(6,0)/(1,1)/(-4,3)"); ?></li>
<li><b>PLL H</b> : Double Opposés deux fois avec un (3,0) inséré entre les deux<br/>
<?php ep('1,3|2,4', '', "(1,0)/(-1,-1)/(6,0)/(1,1)/ . (3,0) . /(-1,-1)/(6,0)/(1,1)/(-4,0)"); ?></li>
<li><b>PLL Z / U</b> : Double adjacent sera de mise. Il faut toujours commencer son premier par une paire de la Z avec une partie de la U qui a la couleur opposé.<br/>
<?php ep('1,2|3,4', '1,2,3', "(-2,0)/(0,3)/(-1,-1)/(1,-2)/ . (0,-3) . /(3,0)/(-1,-1)/(-2,1)/(2,3)"); ?></li>
</ul>
Évidemment, il y a beaucoup d'autres cas qui se résolvent de façon efficace avec ce kit. Un travail conséquent sur ces quelques séquences permettra une efficacité correcte pour obtenir des performances raisonables. Toutefois, si vous cherchez à améliorer votre régularité en résolvant des cas complexes directement, jetez un petit coup d'œil sur la suite. 
<h3>Le kit <em>suffisant</em></h3>
Ce kit de cas, vous permettra de résoudre la plupart des cas de façon très rapide. Notez que le kit minimal permet de les résoudre mais vous constaterez leur importance.
<ul>
<li><b>PLL U (3-cycle)</b> : Même si sa taille semble longue, son exécution est très rapide ce qui en fait une séquence absolument importante. Vous trouverez les différents inverses-symétries si besoin.<br/>
<table border='0' align="center" cellspacing='0'><tr><td><?php ep('4,3,2', '', "(1,0)/(0,-3)/(-1,0)/(3,0)/(1,0)/(0,3)/(-1,0)/(-3,0)/"); ?></td>
<td><?php ep('4,2,3', '', "/(3,0)/(1,0)/(0,-3)/(-1,0)/(-3,0)/(1,0)/(0,3)/(-1,0)"); ?></td></tr>
<tr><td><?php ep('', '4,1,2', "(0,-1)/(3,0)/(0,1)/(0,-3)/(0,-1)/(-3,0)/(0,1)/(0,3)/"); ?></td>
<td><?php ep('', '2,1,4', "/(0,-3)/(0,-1)/(3,0)/(0,1)/(0,3)/(0,-1)/(-3,0)/(0,1)"); ?></td></tr></li>
<li><b>PLL Z</b> : Cette séquence est basée sur M2 puisque son principe est <em>M2 U M2 U' M2</em>. Sur un 3x3, elle n'est pas applicable car il y a un nombre impair de M2, ici ce n'est pas grave. Notez que les séquences basées sur M2 ont l'avantage de se symétriser Haut/Bas de façon naturelle.<br/>
<?php ep('1,4|2,3', '', "(1,0)/(-1,-1)/(3,0)/(1,1)/(-3,0)/(-1,-1)/(0,1)"); ?></li>
<li><b>Rond / Opposé</b> : En faisant une légère variante de la Z, on obtient celle-ci par <em>M2 U M2 U M2</em>. Notez que vous pouvez partir à gauche (U) comme à droite (U') suivant le sens du rond (répérez l'emplacement cible de l'arête en UF).<br/>
<?php ep('1,2,3,4', '1,3', "(1,0)/(-1,-1)/(3,0)/(1,1)/(3,0)/(-1,-1)/(6,1)"); ?></li>
<li><b>PLL H</b> : Encore une M2. Si vous connaissez celle du 3x3, c'est la même </em>M2 U M2 U2 M2 U M2</em><br/>
<?php ep('1,3|2,4', '', "(1,0)/(-1,-1)/(3,0)/(1,1)/(6,0)/(-1,-1)/(3,0)/(1,1)/(-1,0)"); ?></li>
<li><b>Adjascent / Opposé</b> : Voilà un premier exemple de séquence difficile mais qui reste indispensable car les solutions de remplacements restent longues (Double Opposés et PLL U).<br/>
<table align="center" border='0' cellspacing='0'><tr><td><?php ep('1,3', '1,4', "(1,0)/(0,-1)/(0,-3)/(5,0)/(-5,0)/(0,3)/(0,1)/(5,0)"); ?></td><td><?php ep('3,4', '1,3', "(0,-1)/(1,0)/(3,0)/(0,-5)/(0,5)/(-3,0)/(-1,0)/(0,-5)"); ?></td></tr></table.</li>
</li>
<li><b>Parité Rond</b> : C'est la parité la plus simple à comprendre. Le principe étant de former une étoile de 6 coins sur U. On applique ensuite un 6-cycle en faisant (2,0) ou (-2,0), en gras sur la séquence ici, introduisant la parité, et on refait le BTC avec le rétablissement du cube en 2 flips supplémentaires. Si le rond est horraire il faudra appliquer (-2,0) et inversement. Pour la rapidité, je vous conseille de voir de quel côté se trouve l'arête qui correpond aux coins sur UF.<br/>
<table align="center" cellspacing='0' border="0"><tr><td><?php ep('1,4,3,2', '', "/(3,3)/(1,0)/(-2,-2)/ <b>(2,0)</b> /(2,2)/(-1,0)/(-3,-3)/ . (-2,0)/(2,2)/(-3,-2)"); ?></td><td><?php ep('1,2,3,4', '', "/(3,3)/(1,0)/(-2,-2)/ <b>(-2,0)</b> /(2,2)/(-1,0)/(-3,-3)/ . (1,0)/(2,2)/(0,-2)"); ?></td></tr></table></li>
<li><b>Parité U / Adjascent</b> : Elle n'a pas belle allure, mais elle se rencontre vraiment souvent, surtout lorsque l'on contrôle l'étape précédente. Il est important de connaitre l'ensemble des inverses-symétries sur un tel cas, pour plus de simplicité elles sont à disposition ici. Avec de l'entrainement, elles passeront plus rapidement que Double Adj-Parité Adj.
<table border='0' align="center" cellspacing='0'><tr><td><?php ep('4,3,2', '1,2', "/(-3,-3)/(0,-5)/(0,2)/(0,4)/(0,4)/(4,0)/(2,0)/(-1,0)/(-3,-3)/(0,3)"); ?></td>
<td><?php ep('2,3,4', '1,4', "/(3,3)/(1,0)/(-2,0)/(-4,0)/(0,-4)/(0,-4)/(0,-2)/(0,5)/(3,3)/(0,-3)"); ?></td></tr>
<tr><td><?php ep('2,3', '4,1,2', "/(3,3)/(5,0)/(-2,0)/(-4,0)/(-4,0)/(0,-4)/(0,-2)/(0,1)/(3,3)/(-3,0)"); ?></td>
<td><?php ep('3,4', '2,1,4', "/(-3,-3)/(0,-1)/(0,2)/(0,4)/(4,0)/(4,0)/(2,0)/(-5,0)/(-3,-3)/(3,0)"); ?></td></tr></li>
</ul>
<h3>Et le reste ?</h3>
<p>Si vous connaissez déjà toutes les permutations ici présentes, vous êtes à un niveau bien avancé. Je vous conseille de bien vous entrainer à faire ces quelques EP et de trouver leurs symétries si besoin. Enfin, si vous avez besoin d'autres EP, vous pouvez vous tourner vers la liste complète que vous pouvez trouver sur le <a href='http://www.cubezone.be/square1step5.html'>site de Lars Vandenbergh</a></p>
<h3>Quelques astuces supplémentaires</h3>
<p>Avec juste ces quelques EP, vous avez tout pour tomber en dessous des 25 secondes de moyenne. Vous aurez remarqué que beaucoup de ces séquences sont simple au final et bénéficient d'une vitesse d'exécution très impressionnante. Il n'est pas toujours utile d'aller chercher la séquence optimale de résolution d'un cas complexe car une combinaison efficace de deux des cas précédents vous permettra, la plupart du temps, une réussite rapide. Quelques cas isolés sont plus délicats, notez que vous pouvez les enlever avec <em>M2 U2 M2</em>)</p>

<p>Ne doutez pas de la puissance de l'alignement dans l'étape d'avant. En effet, si vous avez au moins un bloc coin-arête (cas fréquent). Sauver ce bloc restreindra la liste des possibilité d'EPLL sur cet étage à : Adjascent, Opposé, U et U'. Donc, on enlève tous les cas difficiles et on garde que des cas rapides. Avec un peu d'expérience, il est possible de compter le nombre d'arête à permuter avant l'exécution de l'étape d'avant.</p>

<p>Soyez astucieux lors du placement de votre parité. Quand vous ne connaissez que peu de parités, l'endroit où vous allez l'exécuter change drastiquement les configurations. Mettons que vous ayez Adj/Z. Mettons que vous appliquiez la parité adjascente pure. Un mauvais alignement de départ vous donnera U/Z, sympathique certes, mais dommage. Un bon alignement vous donnera Skip/Z. Vous pouvez vous contenter du double Z, un peu plus long mais très efficace.</p>

<p>Il est extrêmement important de savoir comment se comporte la tranche du milieu par rapport aux séquences que vous exécuterez. Vous saurez ainsi directement avant la fin de votre résolution de l'étape ce qu'il faudra faire pour la suite. Dans les différents kits précédents, seules : la parité adjascente, le cas Adjascent/Opposé et son symétrique inversent la position de la tranche centrale. Une astuce pour gagner un peu de temps est d'intégrer la dernière étape aux séquences que vous exécutez. Avec l'expérience, vous arriverez à la faire naturellement sans passer par l'état stable, arrivant ainsi directement à la fin.</p>
</body>
</html>