/**
 * 
 *		Jocelyn GENNESSEAUX (hyperjoce - French Connection - www.planetside.fr)
  * 
 *		Page utilisée par le formulaire permettant de tester si le pseudo appartient à un membre de la FRC
 * 
 *		License : CC BY 3.0 FR (http://creativecommons.org/licenses/by/3.0/fr/legalcode)
 * 
 */

 
 <?php
	// Inclusions
	include('../common/stringhelper.php');

	//
	$result = "Aucune donnée à vérifer";
	
	// Récupération du pseudo passer via l'url
	$Pseudo = (isset($_GET["pseudo"])) ? $_GET["pseudo"] : null;
	
	// retrouve l'id en fonction du pseudo
	if($Pseudo)
	{
		$pseudo = strtolower($Pseudo);
		
		//
		$ServiceId = 's:H41';
		$FrcId = '37509488620602280';
		$UserId = 0;
			
		// Intérogation de l'API de planetside 2
		$url = 'https://census.soe.com/'.$ServiceId.'/get/ps2/character?c:resolve=outfit&c:show=id,name&name.first_lower='.$pseudo.',outfit.id='.$FrcId;
		$json = file_get_contents($url);
		$json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
		$jsonObject = json_decode($json);
		
		if(count($jsonObject->character_list)==1 && contains($json, $FrcId)) {
			$UserId = $jsonObject->character_list[0]->id;
		}
		
		// Envoi de la réponse
		if($UserId>0) 	{ $result = "OK"; }
		else 			{ $result = "Le pseudo saisi n'est pas valide"; }
	}

	
	//
	header("Content-Type: text/plain");
	echo $result;
?>
		
