<?php
/**
        @brief	Cette classe sert � facilit� la g�n�ration de formulaires HTML
				en PHP. Elle fournit de m�thodes pour g�n�rer le d�but, la fin du formulaire,
				ainsi que les inputs avec les options de base.
		        Les options compl�mentaires des inputs peuvent �tre s�lectionn�es
				via une variable $extraOptions des m�thodes (mais c'est un peu plus complexe).
*/
class FormManager {
        /**
        @brief	G�n�re la balise form avec sa m�thode (Post ou Get) et sont action (url)
        */
        public static function beginForm($method=null, $action=null, $extraOptions=null)
        {
			echo "<form ";
			if ($method != null) 		{ echo "method=\"".$method."\""; }
            if ($action != null) 		{ echo "action=\"".$action."\""; }
            if ($extraOptions != null) 	{ echo $extraOptions; }
			echo ">\n";
        }
        
        /**
		@brief	Ferme le formulaire
        */
        public static function endForm()
        {
                echo "</form>";
        }

        /**
		@brief	m�thode g�n�rique de g�n�ration d'un input
		@param $labelText texte du label correspondant � l'input
		@param $type type d'input : texte textarea, chackbox, radio, submit...
		@param $id ID de l'input pour la correspondance label/input
		@param $value valeur initiale du champs de l'input
		@paarm $extraOptions chaine de caract�res contenant les options suppl�mentaires de l'input suivant la syntaxe HTML.
        */
        public static function addInput($labelText, $type, $name, $id, $value=null, $extraOptions="",$paragraph=1) 
        {
        	if($paragraph==1) { echo "<p>\n"; }
        	 
        	if ($labelText!=null && $labelText!="") {
        		echo "<label for=\"".$id."\">".$labelText."</label>\n";
        	}
        	 
        	
        	echo "<input ";
			if ($type!=null)			{ echo "type=\"".$type."\" "; }
			if ($name!=null)			{ echo "name=\"".$name."\" "; }
			if ($name!=null)			{ echo "id=\"".$id."\" "; }
			if ($value!=null)			{ echo "value=\"".$value."\" "; }
			if ($extraOptions!=null)	{ echo "$extraOptions "; }
			echo "/>\n";

            if($paragraph==1) { echo "</p>\n"; }
        }

        /**
		@brief	m�thode simplifi�e pour g�n�rer un input de type text
        */
        public static function addTextInput($labelText, $name, $id, $size, $value=null, $extraOptions="", $paragraph=1)
        {
        	self::addInput($labelText, "text", $name, $id, $value, "size =\"".$size."\" ".$extraOptions, $paragraph);
        }

		/**
		@brief	m�thode simplifi�e pour g�n�rer un input de type password
        */
        public static function addPasswordInput($labelText, $name, $id, $size, $value=null, $extraOptions="", $paragraph=1)
        {
        	self::addInput($labelText, "password", $name, $id, $value, "size =\"".$size."\" ".$extraOptions, $paragraph);
        }
        /**
		@brief	m�thode simplifi�e pour g�n�rer un input de type radio
        */
        public static function addRadioInput($labelText, $name, $id, $checked, $value=null, $extraOptions="")
        {
        	self::addInput($labelText, "radio", $name, $id, $value, (strcmp($checked, 'checked')==0)? "checked =\"checked\" ":" ".$extraOptions);
        }
		/**
		@brief	m�thode simplifi�e pour g�n�rer un input de type radio
        */
        public static function addCheckboxInput($labelText, $name, $id, $checked, $value=null, $extraOptions="", $paragraph=1)
        {
        	self::addInput($labelText, "checkbox", $name, $id, $value, (strcmp($checked, 'checked')==0)? "checked =\"checked\" ":" ".$extraOptions,$paragraph);
        }
		/**
		@brief	m�thode simplifi�e pour g�n�rer un input de type radio
        */
        public static function addHiddenInput($name, $id, $value, $extraOptions="", $paragraph=1)
        {
        	self::addInput("", "hidden", $name, $id, (string)($value), $extraOptions, $paragraph);
        }
        /**
		@brief	m�thode simplifi�e pour g�n�rer un input de type textarea
        */
        public static function addTextAreaInput($labelText, $name, $id, $rows, $cols, $value=null, $extraOptions="")
        {
        	$valueOption = ($value == null) ? "" : $value;
        	if ($extraOptions == null)
        	{
        		$extraOptions="";
        	}
        	echo "<p>\n";
        	if ($labelText!=null  && $labelText!="")
        	{
        		echo "<label for=\"".$id."\">".$labelText."</label>\n";
        	}
        	echo "<textarea name=\"".$name."\" id=\"".$id."\" rows=\"".$rows."\" cols=\"".$cols."\" ".$extraOptions." >".$valueOption."</textarea>\n";
        	echo "</p>\n";
		}
        /**
		@brief	m�thode simplifi�e pour g�n�rer un input de type file (upload)
        */
        public static function addUploadInput($labelText, $name, $id, $size, $value="", $extraOptions="")
        {
        	$valueOption = ($value == null) ? "value=\"\"" : " value=\"".$value."\" ";
        	if ($extraOptions == null)
        	{
        		$extraOptions="";
        	}
        	self::addInput($labelText, "file", $name, $id, $value, "size =\"".$size."\" ".$valueOption." ".$extraOptions);
        }
		
		/**
		@brief	m�thode  pour commencer un select
        */
        public static function beginSelect($labelText, $name, $id, $multiple=false, $size=5)
        {
        	if ($multiple)
        		$multipleOption="multiple=\"multiple\" size=\"".$size."\"";
        	else
        		$multipleOption="";
        	if ($labelText!=null  && $labelText!="")
        	{
        		echo "<p><label for=\"".$id."\">".$labelText."</label>\n";
        	}
        	echo "<select name=\"".$name."\" id=\"".$id."\"".$multipleOption.">\n";
        }
        /**
		@brief	m�thode simplifi�e pour terminer un select
        */
        public static function endSelect()
        {
        	echo "</select></p>\n";
        }
        /**
        @brief	m�thode simplifi�e pour ajouter une option de select
        */
        public static function addSelectOption($value,$displayText, $selected=false)
        {
        	if ($selected)
        		$selectedOption="selected=\"selected\"";
        	else
        		$selectedOption="";
        	echo "<option value=\"".$value."\" ".$selectedOption.">".$displayText."</option>\n";
        }       

        /**
         @brief	m�thode simplifi�e pour g�n�rer un bouton submit
         */
        public static function addSubmitButton($value="Envoyer", $extraOptions="", $paragraph=1)
        {
        	self::addInput(null, "submit", "", "", $value, " ".$extraOptions, $paragraph);
        }

        /**
         @brief	m�thode simplifi�e pour g�n�rer un bouton submit
         */
        public static function addButton($value="Envoyer", $extraOptions="", $paragraph=1)
        {
        	self::addInput(null, "button", "", "", $value, " ".$extraOptions, $paragraph);
        }
        
		/**
		 @brief	m�thode simplifi�e l'ajout d'une image
		 */
		public static function addImg($id, $src, $class="")
		{
			echo "<img id=\"".$id."\" src=\"".$src."\" class=\"".$class."\" />";
		}

		/**
		 @brief	m�thode simplifi�e l'ajout d'une balise span
		 */
		public static function addSpan($id, $class="")
		{
			echo "<span id=\"".$id."\" class=\"".$class."\" ></span>";
		}
		
}
?>