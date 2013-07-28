/**
 * 
 *		Jocelyn GENNESSEAUX (hyperjoce - French Connection - www.planetside.fr)
 * 
 *		Formulaire de création de la signature
 * 
 *		License : CC BY 3.0 FR (http://creativecommons.org/licenses/by/3.0/fr/legalcode)
 * 
 */

 
 <?php
	include('../common/html5helper.php');
	include('../common/formmanager.php');
	
	html5helper::EnTete('Formulaire de création des signatures');
	html5helper::AddCss("./style/formulaire.css");
	html5helper::AddJavaScript("../script/oXHR.js");
	html5helper::AddJavaScript("./script/formulaire.js");
?>
	
	<div>
	<table>
		<tr>
			<td>Pseudo :</td>
			<td>
				<form>
						<input type="text" id="pseudo" name="pseudo" onChange="VerifierPseudo();"/>
						<img id="imgChargement"	src="./image/ajax-loader.gif"	class="PictureHidden" />
						<img id="imgOK"			src="./image/ajax-clean.png"	class="PictureHidden" />
						<img id="imgErreur"		src="./image/ajax-erreur.png"	class="PictureHidden" />
						<span id="errorText" class="erreur"></span>
				</form>
			</td>
		</tr>
		<tr>
			<td>Options :</td>
			<td>
				<input type="checkbox" name="br" id="br" checked="checked" onChange="UpdateSignature();" /> <label for="br">BR</label>
				<input type="checkbox" name="xp" id="xp" checked="checked" onChange="UpdateSignature();" /> <label for="xp">XP</label>
				<input type="checkbox" name="kd" id="kd" checked="checked" onChange="UpdateSignature();" /> <label for="kd">K/D</label>
			</td>
		</tr>
		<tr>
			<td>Fond :</td>
			<td>
				<select id="fond" onchange="UpdateSignature();">
					<option value="0">Aléatoire</option>
					<option value="1">Fond 1</option>
					<option value="2">Fond 2</option>
					<option value="3">Fond 3</option>
					<option value="4">Fond 4</option>
					<option value="5">Fond 5</option>
					<option value="6">Fond 6</option>
					<option value="7">Fond 7</option>
				</select>
			</td>
		</tr>
	</table>
	
	<p>
		<?php
			$urlLocation = dirname($_SERVER['SERVER_PROTOCOL']) . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if(strrpos($urlLocation, "/") !== false) $urlLocation = substr( $urlLocation, 0, strrpos( $urlLocation, "/")+1);
			$url = $urlLocation.'signature.php';
			echo "<img id=\"imgSignature\" src=\"".$url."\">"
		?>
		<br />
		Url de la signature :
		<br />
		<textarea id="urlSignature" rows="4" cols="78"></textarea>
	</p>
		
	<!-- Création du formulaire -->
	</div>
	
<?php	
	html5helper::FinFichier();
?>