<?php

/**
 *
 *		Jocelyn GENNESSEAUX (hyperjoce - French Connection - www.planetside.fr)
 *
 *		Image de la signature
 *
 *  	cette signature affiche :
 *   		- le BR
 *   		- le nombre d'XP
 *   		- le kill death ratio
 *   		- les trois classes les plus utilisées
 *   		- les deux véhicules les plus utiliséq
 *   		- le meilleur score de la première classe
 *   		- le meilleur score du premier véhicule
 *
 *		License : CC BY 3.0 FR (http://creativecommons.org/licenses/by/3.0/fr/legalcode)
 *
 */

    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);

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

    //On prepare les couleurs, en RGB pour la police d'�criture
    $blanc = imagecolorallocate($fond, 255, 255, 255);
    $noir = imagecolorallocate($image, 0, 0, 0);

    // Police TTF utilise pour l'écriture dans l'image
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

    // Identifiant de l'outfit FRC : il faut �tre membre de la FRC pour que la signature fonctionne
    $FrcId = '37509488620602280';
    $UserId = 0;
    $outfit = null;


    // retrouve l'id en fonction du pseudo
    if(isset($_GET["pseudo"]) && isset($_GET["id"])==false)
    {
        $Pseudo = $_GET["pseudo"];
        $pseudo = strtolower($Pseudo);

        // Int�rogation de l'API de planetside 2
        $url = 'https://census.soe.com/'.$ServiceId.'/get/ps2/character?c:resolve=outfit&c:hide=battle_rank,certs,daily_ribbon,times&name.first_lower='.$pseudo.',outfit.id='.$FrcId;
        $json = file_get_contents($url);
        $json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
        $jsonObject = json_decode($json);

        if(count($jsonObject->character_list)==1 && contains($json, $FrcId))
        {
            $UserId = $jsonObject->character_list[0]->character_id;
            $outfit = $jsonObject->character_list[0]->outfit;
        }
    }

    // retrouve le pseudo en fonction de l'id
    if(isset($_GET["id"]) && isset($_GET["pseudo"])==false)
    {
        $UserId = $_GET["id"];

        // Int�rogation de l'API de planetside 2
        $url = 'https://census.soe.com/'.$ServiceId.'/get/ps2/character?c:resolve=outfit&c:hide=battle_rank,certs,daily_ribbon,times&id='.$UserId;
        $json = file_get_contents($url);
        $json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
        $jsonObject = json_decode($json);

        if(count($jsonObject->character_list)==1 && contains($json, $FrcId))
        {
            $Pseudo = $jsonObject->character_list[0]->name->first;
            $pseudo = strtolower($Pseudo);
            $outfit = $jsonObject->character_list[0]->outfit;
        }
    }


    // Il faut �tre membre de la FRC pour que les informations s'affichent
    if($UserId>0 && $outfit && $outfit->outfit_id==$FrcId)
    {
        // Int�rogation de l'API de planetside 2
        $url = 'https://census.soe.com/'.$ServiceId.'/get/ps2/single_character_by_id/?character_id='.$UserId;
        $json = file_get_contents($url);
        $json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
        $jsonObject = json_decode($json);

        // Ajout du sigle vanu
        // ----------------------------------------------------------------
        $vanu=imagecreatefrompng("./image/vanu.png");
        imagecopymerge_alpha($fond, $vanu, 5, 5, 0, 0, imagesx($vanu), imagesy($vanu),50);

        // Ajout du pseudo
        // ----------------------------------------------------------------
        imagettftext($fond, 20, 0, 55, 35, $blanc, $fontBol, $Pseudo);

        // Ajout de l'outfit
        // ----------------------------------------------------------------
        $outfitname = $outfit->name;
        imagettftext($fond, 10, 0, 55, 51, $blanc, $fontBol, $outfitname." - Miller");

        // Battle rank / Experience / Kill death / playtime
        // ----------------------------------------------------------------
        $br = $jsonObject->single_character_by_id_list[0]->stats->stat_history->battle_rank->all_time;
        $xp = $jsonObject->single_character_by_id_list[0]->stats->stat_history->score->all_time;
        $k = $jsonObject->single_character_by_id_list[0]->stats->stat_history->kills->all_time;
        $d = $jsonObject->single_character_by_id_list[0]->stats->stat_history->deaths->all_time;
        $playtime = $jsonObject->single_character_by_id_list[0]->stats->stat_history->time->all_time;

        $score = "";
        $AddBr = ( (isset($_GET["br"]) and $_GET["br"]==1) or (isset($_GET["br"])==0) );
        $AddXp = ( (isset($_GET["xp"]) and $_GET["xp"]==1) or (isset($_GET["xp"])==0) );
        $AddKd = ( (isset($_GET["kd"]) and $_GET["kd"]==1) or (isset($_GET["kd"])==0) );
        if($AddBr) {
            $score = $score."BR : ".$br."  ";
        };
        if($AddXp) {
            $xp = number_format($xp,0,'.',' ');
            $score = $score."XP : ".$xp."  ";
        };
        if($AddKd) {
            $kd = $k/$d;
            $kd = number_format($kd,2);
            $score = $score."K/D : ".$kd."  ";
        };
        imagettftext($fond, 10, 0, 10, 105, $blanc, $fontReg, $score);

        // Scores et temps de jeux par classe
        $aClasseScore = array();
        $aClassePlayTime = array();
        for ($i = 0; $i < count($jsonObject->single_character_by_id_list[0]->stats->stat); $i++)
        {
            $Stat = $jsonObject->single_character_by_id_list[0]->stats->stat[$i];
            if($Stat->stat_name=="score") {
                array_push_associative($aClasseScore, array($Stat->profile_id=>intval($Stat->value_forever)));
            }
            if($Stat->stat_name=="play_time") {
                array_push_associative($aClassePlayTime, array($Stat->profile_id=>intval($Stat->value_forever)));
            }
        }
        arsort($aClasseScore);
        arsort($aClassePlayTime);

        // Scores et temps de jeux par v�hicule
        $aVehiculesScore = array();
        $aVehiculesPlayTime = array();
        for ($i = 0; $i < count($jsonObject->single_character_by_id_list[0]->stats->weapon_stat); $i++)
        {
            $WeaponStat = $jsonObject->single_character_by_id_list[0]->stats->weapon_stat[$i];
            if($WeaponStat->item_id=="0" && $WeaponStat->stat_name=="weapon_score" && intval($WeaponStat->vehicle_id)>0 && intval($WeaponStat->vehicle_id)<13 ) {
                array_push_associative($aVehiculesScore, array($WeaponStat->vehicle_id=>intval($WeaponStat->value)));
            }
            if($WeaponStat->item_id=="0" && $WeaponStat->stat_name=="weapon_play_time" && intval($WeaponStat->vehicle_id)>0 && intval($WeaponStat->vehicle_id)<13 ) {
                array_push_associative($aVehiculesPlayTime, array($WeaponStat->vehicle_id=>intval($WeaponStat->value)));
            }
        }
        arsort($aVehiculesScore);
        arsort($aVehiculesPlayTime);


        // Ajout des icones pour les classes les plus jou�es
        // ----------------------------------------------------------------
        // Supprime les derniers �l�ments afin de ne garder que les 3 premiers
        while(count($aClassePlayTime)>3) {
            array_pop($aClassePlayTime);
        }
        // Ajout des icones
        $x = 1;
        $yi = 121;
        $yt = $yi;
        $playtimeclass = 0;
        foreach ($aClassePlayTime as $key => $value) {
            // Ajout de l'icone de la classe
            $ico1=imagecreatefrompng("./icone/iconblack".GetClasse($key)."18.png");
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


        // Ajout des icones pour les v�hicules les plus jou�es
        // ----------------------------------------------------------------
        // Supprime les derniers �l�ments afin de ne garder que les 2 premiers
        while(count($aVehiculesPlayTime)>2) {
            array_pop($aVehiculesPlayTime);
        }
        // Ajout des icones
        $x = $x + 15;
        foreach ($aVehiculesPlayTime as $key => $value) {
            // Ajout de l'icone du v�hicule
            $ico1=imagecreatefrompng("./icone/iconvehicle".GetVehicule($key)."18.png");
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

        // Ajout des icones pour les classes ayant le meilleur score
        // ----------------------------------------------------------------
        // Supprime les derniers �l�ments afin de ne garder que le premier
        while(count($aClasseScore)>1) {
            array_pop($aClasseScore);
        }
        // Ajout des icones
        $x = $x + 160;
        $scoreclass = 0;
        foreach ($aClasseScore as $key => $value) {
            // Ajout de l'icone de la classe
            $ico1=imagecreatefrompng("./icone/iconblack".GetClasse($key)."18.png");
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

        // Ajout des icones pour les v�hicules ayant le meilleur score
        // ----------------------------------------------------------------
        // Supprime les derniers �l�ments afin de ne garder que le premier
        while(count($aVehiculesScore)>1) {
            array_pop($aVehiculesScore);
        }
        // Ajout des icones
        $x = $x + 15;
        $scorevehicule = 0;
        foreach ($aVehiculesScore as $key => $value) {
            // Ajout de l'icone du v�hicule
            $ico1=imagecreatefrompng("./icone/iconvehicle".GetVehicule($key)."18.png");
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

    // Ajout du fond sur l'image blanche
    imagecopymerge_alpha($image, $fond, 0, 0, 0, 0, imagesx($fond), imagesy($fond),100);

    // on spécifie que le type de document que l'on va créer est une image png
    header ("Content-type: image/png");

    // Rendu de l'image final
    imagepng ($image);
    imagedestroy($image);
    imagedestroy($fond);
?>

<?php
/*
 * V�hicule en fonctuion de son id
 */
function GetVehicule($id)
{
    switch(intval($id))
    {
        case 1:		return "flash";			break;
        case 2:		return "sunderer";		break;
        case 3:		return "lightning";		break;
        case 4:		return "magrider";		break;
        case 5:		return "vanguard";		break;
        case 6:		return "prowler";		break;
        case 7:		return "scythe";		break;
        case 8:		return "reaver";		break;
        case 9:		return "mosquito";		break;
        case 10:	return "liberator";		break;
        case 11:	return "galaxy";		break;
        case 12:	return "harasser";		break;

        default:    return "flash";			break;
    }
}

/*
 * Classe en fonctuion de son id
 */
function GetClasse($id)
{
    switch(intval($id))
    {
        case 1:		return "infiltrator";		break;
        case 2:		return "sniper";			break;
        case 3:		return "lightassault";		break;
        case 4:		return "combatmedic";		break;
        case 5:		return "engineer";			break;
        case 6:		return "heavyassault";		break;
        case 7:		return "max";				break;

        default:    return "infiltrator";		break;
    }
}
?>