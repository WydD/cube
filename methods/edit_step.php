<form method="POST">
	<table border="0">
		<tbody>
			<tr>
				<td>Nom : </td>
				<td><input type="text" name="name"/></td>
			</tr>
			<tr>
				<td valign="top">Description : </td>
				<td><textarea name="description" cols="50" rows="10"> </textarea></td>
			</tr>
			<tr>
				<td>Type : </td>
				<td><select name="type"><option value="atomic" selected="selected">Atomique</option><option value="choice">Choix</option><option value="sequence">Séquence</option></select></td>
			</tr>
			<tr>
				<table border="0">
						<tbody>
						<tr>
							<td>Requis : </td>
							<td><img src="imagecube.php" border="0"/> <img src="../nimagecube/imagecube_final.php?size=3&imgsize=150&algo=y2" border="0"/> Setup: </td>
						</tr>
						<tr>
							<td>Donne : </td>
							<td><img src="../nimagecube/imagecube_final.php?size=3&imgsize=150&algo=" border="0"/> <img src="../nimagecube/imagecube_final.php?size=3&imgsize=150&algo=y2" border="0"/> Setup: </td>
						</tr>
						<tr>
							<td valign="top">Symétrie : </td>
							<td>L/R <input type="checkbox" name="symetry" value="lr"/><br/>
							    F/B <input type="checkbox" name="symetry" value="fb" /><br/>
							    U/D <input type="checkbox" name="symetry" value="ud"/></td>
						</tr>
					</tbody>
				</table>
			</tr>
		</tbody>
	</table>
</form>