<!-- Déclaration de l'en-tête de la page html5 -->
<?php
	include('../common/html5helper.php');

	html5helper::EnTete('examples de signatures pour toute l outfit', '', '');
	
	// Identifiant de l'outfit FRC
	$FrcId = '37509488620602280';
	
	// Intérogation de l'API de planetside 2
	$json = file_get_contents('http://census.soe.com/get/ps2-beta/outfit_member?c:start=0&c:limit=4&id='.$FrcId);
	$jsonObject = json_decode($json);

	// Boucle sur tous les membres
	foreach ($jsonObject->outfit_member_list as $key => $value)
	{	
		$id = $value->character_id;
		$fond = rand(1,7);
		$url = 'http://www.gennesseaux.fr/frc/signature/signature.php?id='.$id.'&fond='.$fond;
		
		?>
		id : <?php echo $id ?></br>
		<img alt="" src="<?php echo $url ?>"></br>
	<?php	}	
			
?>

<!-- Déclaration de fin de la page html5 -->
<?php html5helper::FinFichier(); ?>