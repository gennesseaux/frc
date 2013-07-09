<!-- Déclaration de l'en-tête de la page html5 -->
<?php
	include('./common/html5helper.php');
	include('./common/arrayhelper.php');
	include('./common/stringhelper.php');
	
	html5helper::EnTete('Planetside 2 items', '', '');
?>

<!-- Ajout de styles -->
<style>
table {
    border-collapse: collapse;
}
td, th {
    border: 1px solid black;
    padding: 3px;
}
</style>

<!-- Création d'une table contenant les images disponibles via l'API -->
<table>
<caption><h2>Icones Planetside 2</h2></caption>
	<?php
		// Intérogation de l'API de planetside 2 pour récupérer la list des items
		//$json = file_get_contents('http://census.soe.com/get/ps2-beta/icon.attachment?c:start=0&c:limit=10&c:show=owner_id,description,extension&c:sort=owner_id');
		$json = file_get_contents('http://census.soe.com/s:H41/get/ps2/icon.attachment?c:start=0&c:limit=10000&c:show=owner_id,description,extension&c:sort=owner_id');
		$jsonObject = json_decode($json);
		$Icones = $jsonObject->{'icon.attachment_list'};
				
		//var_dump($aIcones);
		foreach ($Icones as $key => $value) 
		{	
			$id = $value->owner_id;
			$fichier = $value->description.'.'.$value->extension;
			$url = 'http://census.soe.com/s:H41/img/ps2/icon/'.$id.'/item';
				
			/*if(contains($value->description,'spawn')==false) {
				continue;
			}*/
			/*if(startsWith($value->description,'icon_vehicle_')==false) {
				continue;
			}*/
				
			?>
			<tr valign="top">
			<td>
				id : <?php echo $id ?></br>
				fichier : <?php echo $fichier ?></br>
				url : <a href="<?php echo $url ?>"><?php echo $url ?></a>
			</td>
			<td><img src="<?php echo $url ?>" alt="<?php echo $fichier ?>" /></td>
			</tr>
		<?php }	
		
	?>
</table>


<!-- Déclaration de fin de la page html5 -->
<?php html5helper::FinFichier(); ?>