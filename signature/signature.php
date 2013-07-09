<?php
	// Inclusions 
	include('../common/imagehelper.php');
	include('../common/arrayhelper.php');
	include('../common/stringhelper.php');

	// Service ID
	$ServiceId = 's:H41';
	
	// http://localhost/frc/signature/signature.php?pseudo=Hyperjoce&br=1&xp=1&kd=0

	// Création d'un fond blanc comme image principale
	$image = imagecreatetruecolor(650, 140);
	imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));
		
	// Chargement de l'image de fond
	$fondindex = 1;
	if(isset($_GET["fond"]))
	{
		$fondindex = $_GET["fond"];
		if( ($fondindex<1) or ($fondindex>7)) {
			$fondindex = 1;
		}
	}
	else 
	{
		$fondindex = rand(1,7);
	}
	$fond=imagecreatefrompng("./image/fond_".$fondindex.".png");
	imageAlphaBlending($fond, true);
	imageSaveAlpha($fond, true);
 
	//On prepare les couleurs, en RGB pour la police d'écriture
	$blanc = imagecolorallocate($fond, 255, 255, 255);
	$noir = imagecolorallocate($image, 0, 0, 0);
	
	// Police TTF utiliséée pour l'écriture dans l'image
	putenv('GDFONTPATH=' . realpath('.'));
	$fontReg = './font/Lato-Reg.ttf';
	$fontBol = './font/Lato-Bol.ttf';
	
	// Adresse du site de la frc
	$adresse = "wwww.planetside.fr";
	imagettftext($fond, 10, 0, 460, 15, $blanc, $fontReg, $adresse);
	
	// Ajout du logo de la frc
	$logo=imagecreatefrompng("./image/logo.png");
	imagecopymerge_alpha($fond, $logo, 400, 10, 0, 0, imagesx($logo), imagesy($logo),80);
	imagedestroy($logo);
	
	// Identifiant de l'outfit FRC : il faut être membre de la FRC pour que la signature fonctionne
	$FrcId = '37509488620602280';
	$UserId = 0;
	
	// retrouve l'id en fonction du pseudo
	if(isset($_GET["pseudo"]) && isset($_GET["id"])==false) 
	{
		$Pseudo = $_GET["pseudo"];
		$pseudo = strtolower($Pseudo);
		
		// Intérogation de l'API de planetside 2
		$url = 'https://census.soe.com/'.$ServiceId.'/get/ps2/character?c:resolve=outfit&c:show=id,name&name.first_lower='.$pseudo.',outfit.id='.$FrcId;
		$json = file_get_contents($url);
		$json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
		$jsonObject = json_decode($json);
		
		if(count($jsonObject->character_list)==1 && contains($json, $FrcId))
		{
			$UserId = $jsonObject->character_list[0]->id;
		}
	}
		
	// retrouve le pseudo en fonction de l'id
	if(isset($_GET["id"]) && isset($_GET["pseudo"])==false)
	{
		$UserId = $_GET["id"];
	
		// Intérogation de l'API de planetside 2
		$url = 'https://census.soe.com/'.$ServiceId.'/get/ps2/character?c:resolve=outfit&c:show=name&id='.$UserId;
		$json = file_get_contents($url);
		$json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
		$jsonObject = json_decode($json);

		if(count($jsonObject->character_list)==1 && contains($json, $FrcId))
		{
			$Pseudo = $jsonObject->character_list[0]->name->first;
			$pseudo = strtolower($Pseudo);
		}
	}
	
	
	// Récupère le pseudo
	if($UserId>0) 
	{
		// Intérogation de l'API de planetside 2
		$json = file_get_contents('http://census.soe.com/get/ps2-beta/character?c:resolve=outfit&c:show=name,experience,stats.kill_death_ratio,stats.play_time,stats.score&id='.$UserId);
		$json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
		$jsonObject = json_decode($json);
		
		$outfit = null;
		if(count($jsonObject->character_list)==1)
		{
			$outfit = $jsonObject->character_list[0]->outfit;
		}
		
		// Il faut être membre de la FRC pour que les informations s'affichent
		if($outfit && $outfit->id>0)
		{
			// Ajout du sigle vanu
			// ----------------------------------------------------------------
			$vanu=imagecreatefrompng("./image/vanu.png");
			imagecopymerge_alpha($fond, $vanu, 5, 5, 0, 0, imagesx($vanu), imagesy($vanu),50);
				
			// Ajout du pseudo
			// ----------------------------------------------------------------
			imagettftext($fond, 20, 0, 55, 35, $blanc, $fontBol, $Pseudo);
			
			// Ajout de l'outfit
			// ----------------------------------------------------------------
			$outfit = $jsonObject->character_list[0]->outfit->name;
			imagettftext($fond, 10, 0, 55, 51, $blanc, $fontBol, $outfit." - Miller");
				
			// Battle rank / Experience / Kill death
			// ----------------------------------------------------------------
			$score = "";
			$AddBr = ( (isset($_GET["br"]) and $_GET["br"]==1) or (isset($_GET["br"])==0) );
			$AddXp = ( (isset($_GET["xp"]) and $_GET["xp"]==1) or (isset($_GET["xp"])==0) );
			$AddKd = ( (isset($_GET["kd"]) and $_GET["kd"]==1) or (isset($_GET["kd"])==0) );
			if($AddBr) {
				$br = $jsonObject->character_list[0]->experience[0]->rank;
				$score = $score."BR : ".$br."  ";
			};
			if($AddXp) {
				$xp = $jsonObject->character_list[0]->experience[0]->score;
				$xp = number_format($xp,0,'.',' ');
				$score = $score."XP : ".$xp."  ";
			};
			if($AddKd) {
				$kd = $jsonObject->character_list[0]->stats->kill_death_ratio->value;
				$kd = number_format($kd,2);
				$score = $score."K/D : ".$kd."  ";
			};
			imagettftext($fond, 10, 0, 10, 105, $blanc, $fontReg, $score);

			// Temps de jeux / Score
			// ----------------------------------------------------------------
			$playtime = $jsonObject->character_list[0]->stats->play_time->value;
			
			// Classes les plus utilisées
			// ----------------------------------------------------------------
			// Récèpére la liste des classes et les transformes en array afin de trier les classes les plus jouées
			$Classes = $jsonObject->character_list[0]->stats->play_time->class;
			$aClasses = array();
			foreach (ObjectToArray($Classes,false) as $key => $value) {
				array_push_associative($aClasses, array(str_replace('_','',$key)=>$value->value));
			}
			arsort($aClasses);
			// Supprime les derniers élèments afin de ne garder que les 3 premiers
			while(count($aClasses)>3) {
				array_pop($aClasses);
			}
							
			// Ajout des icones pour les classes les plus jouées
			$x = 1;
			$yi = 121;
			$yt = $yi;
			$playtimeclass = 0;
			foreach ($aClasses as $key => $value) {
				// Ajout de l'icone de la classe
				$ico1=imagecreatefrompng("./icone/iconblack".strtolower($key)."18.png");
				imagecopymerge_alpha($image, $ico1, $x, $yi, 0, 0, imagesx($ico1), imagesy($ico1),100);
				$x = $x + 20;
				// Ajout du pourcentage de jeux
				$playtimeclass = number_format(($value*100)/$playtime,0,'.',' ')."%";
				$box = @imageTTFBbox(10,0,$fontBol,$playtimeclass);
				$width = abs($box[4] - $box[0]);
				$height = abs($box[5] - $box[1]);
				$yt = $yi+18+1-($height/2);
				imageTTFText($image, 10, 0, $x, $yt, $noir, $fontBol, $playtimeclass);
				$x = $x + $width + 2;
			}
			
			// Véhicules les plus utilisées
			// ----------------------------------------------------------------
			// Récupére la liste des véhicules et les transformes en array afin de trier les classes les plus jouées
			$Vehicules = $jsonObject->character_list[0]->stats->play_time->vehicle;
			$aVehicules = array();
			foreach (ObjectToArray($Vehicules,false) as $key => $value) {
				array_push_associative($aVehicules, array($value->name=>$value->value));
			}
			arsort($aVehicules);
			// Supprime les derniers éléments afin de ne garder que les 2 premiers
			while(count($aVehicules)>2) {
				array_pop($aVehicules);
			}
				
			// Ajout des icones pour les véhicules les plus jouées
			$x = $x + 15;
			foreach ($aVehicules as $key => $value) {
				// Ajout de l'icone du véhicule
				$ico1=imagecreatefrompng("./icone/iconvehicle".strtolower($key)."18.png");
				imagecopymerge_alpha($image, $ico1, $x, $yi, 0, 0, imagesx($ico1), imagesy($ico1),100);
				$x = $x + 36;
				// Ajout du pourcentage de jeux
				$playtimevehicule = number_format(($value*100)/$playtime,0,'.',' ')."%";
				$box = @imageTTFBbox(10,0,$fontBol,$playtimevehicule);
				$width = abs($box[4] - $box[0]);
				$height = abs($box[5] - $box[1]);
				$yt = $yi+18+1-($height/2);
				imageTTFText($image, 10, 0, $x, $yt, $noir, $fontBol, $playtimevehicule);
				$x = $x + $width + 2;
			}

			// Classe avec le meilleur score
			// ----------------------------------------------------------------
			// Récupère la liste des classes et les transformes en array afin de trier les classes les plus jouées
			$Classes = $jsonObject->character_list[0]->stats->score->class;
			$aClasses = array();
			foreach (ObjectToArray($Classes,false) as $key => $value) {
				array_push_associative($aClasses, array(str_replace('_','',$key)=>$value->value));
			}
			arsort($aClasses);
			// Supprime les derniers éléments afin de ne garder que le premiers
			while(count($aClasses)>1) {
				array_pop($aClasses);
			}
				
			// Ajout des icones pour la classe
			$x = $x + 160;
			$scoreclass = 0;
			foreach ($aClasses as $key => $value) {
				// Ajout de l'icone de la classe
				$ico1=imagecreatefrompng("./icone/iconblack".strtolower($key)."18.png");
				imagecopymerge_alpha($image, $ico1, $x, $yi, 0, 0, imagesx($ico1), imagesy($ico1),100);
				$x = $x + 20;
				// Ajout du score
				$scoreclass = number_format($value,0,'.',' ');
				$box = @imageTTFBbox(10,0,$fontBol,$scoreclass);
				$width = abs($box[4] - $box[0]);
				$height = abs($box[5] - $box[1]);
				$yt = $yi+18+1-($height/2);
				imageTTFText($image, 10, 0, $x, $yt, $noir, $fontBol, $scoreclass);
				$x = $x + $width;
			}

			// Récupére la liste des véhicules et les transformes en array afin de trier les classes les plus jouées
			$Vehicules = $jsonObject->character_list[0]->stats->score->vehicle;
					$aVehicules = array();
			foreach (ObjectToArray($Vehicules,false) as $key => $value) {
				array_push_associative($aVehicules, array($value->name=>$value->value));
			}
			arsort($aVehicules);
			// Supprime les derniers éléments afin de ne garder que le premiers
			while(count($aVehicules)>1) {
				array_pop($aVehicules);
			}
			
			// Ajout des icones pour les véhicules les plus jouées
			$x = $x + 15;
			$scorevehicule = 0;
			foreach ($aVehicules as $key => $value) {
				// Ajout de l'icone du véhicule
				$ico1=imagecreatefrompng("./icone/iconvehicle".strtolower($key)."18.png");
				imagecopymerge_alpha($image, $ico1, $x, $yi, 0, 0, imagesx($ico1), imagesy($ico1),100);
				$x = $x + 36;
				// Ajout du score
				$scorevehicule = number_format($value,0,'.',' ');
				$box = @imageTTFBbox(10,0,$fontBol,$scorevehicule);
				$width = abs($box[4] - $box[0]);
				$height = abs($box[5] - $box[1]);
				$yt = $yi+18+1-($height/2);
				imageTTFText($image, 10, 0, $x, $yt, $noir, $fontBol, $scorevehicule);
				$x = $x + $width;
			}
		}
	}

	// Ajout du fond sur l'image blanche
	imagecopymerge_alpha($image, $fond, 0, 0, 0, 0, imagesx($fond), imagesy($fond),100);
	
	// on spécifie que le type de document que l'on va créer est une image png
	header ("Content-type: image/png");
	
	// Rendu de l'image final
	imagepng ($image);
	imagedestroy($image);
	imagedestroy($fond);
?>