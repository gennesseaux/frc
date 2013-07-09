<?php

class html5helper {

	/**
	 * 
	 * Création de l'en-tête de la page
	 * 
	 * @param $title string		Titre de la page
	 * @param string $charset	Encodage de la page
	 * @param string $css_sheet	Feuille de style à utiliser
	 */
	public static function EnTete($title, $charset=null, $css_sheet=null)
	{
		// sortie du doctype. Les guillemets HTML sont protégés par \
		echo "<!doctype html>\n";
		echo "<html lang=\"fr\">\n";
		echo "<head>\n";
	
		if($charset!=null) {
			echo "<meta charset=\"".$charset."\"/>\n";
		}
	
		if($css_sheet!=null) {
			AddCss($css_sheet);
		}
		
		echo "<title>".$title."</title>\n";
		echo "</head>\n<body>\n";
	}

	/**
	 * 
	 * Fin de la page
	 * 
	 */
	public static function FinFichier()
	{
		echo "</body>\n</html>\n";
	}

	/**
	 *
	 * Ajout d'une feuille de style à la page
	 *
	 * @param string $css_sheet	Feuille de style à utiliser
	 */
	public static function AddCss($css_sheet)
	{
		echo "<link rel=\"stylesheet\" href=\"".$css_sheet."\" />\n";
	}

	/**
	 *
	 * Ajout d'un fichier de script à la page
	 *
	 * @param string $src	Fichier de sript .js à utiliser
	 */
	public static function AddJavaScript($src)
	{
		echo "<script type=\"text/javascript\" src=\"".$src."\"></script>";
	}

	/**
	 *
	 * Ouverture d'un paragraphe
	 *
	 */
	public static function BeginParagraph()
	{
		echo "<p>";
	}

	/**
	 *
	 * Fermeture d'un paragraphe
	 *
	 */
	public static function endParagraph()
	{
		echo "</p>";
	}
	
	
}
?>
