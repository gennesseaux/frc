/**
 * 	Script de gestion du formulaire de signature
 */

/**
 * 	Variables globales
 */
var xhr = null;

/**
 * 	Vérifie que le pseudo saisi correspond bien à un membre de la FRC
 */
function VerifierPseudo()
{
	// Test si une requête est en cours et l'annule
	if (xhr && xhr.readyState != 0) { xhr.abort(); }
	
	// Début d'une nouvelle requête
	xhr = getXMLHttpRequest();
	var pseudo = document.getElementById("pseudo").value;

	xhr.onreadystatechange = function()
	{
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
        {
 	    	var result = xhr.responseText.trim();
 
	    	if(result === "OK")	{ PseudoValide(); }
	    	else				{ PseudoNonValide(result); }
        }
        
        //On affiche l'image de chargement
        else if (xhr.readyState < 4)
        {
			ChargementEnCours();
        }
	};

	xhr.open("GET", "VerifierPseudo.php?pseudo=" + pseudo, true);
	xhr.send(null);
};


/**
 * 	Indique que le chargement est en cours
 */
function ChargementEnCours()
{
	// On vide le contenu du champ errorText
	//document.getElementById("errorText").innerHTML = "";

	// Affichage de l'image de chargement
	document.getElementById("imgChargement").className = 'PictureVisible';

	// On cache les images imgOk et imgErreur
	document.getElementById("imgOK").className = 'PictureHidden';
	document.getElementById("imgErreur").className = 'PictureHidden';
};


/**
 * 	Le pseudo saisie n'est pas valide
 */
function PseudoNonValide(message)
{
	// On se sert de innerHTML pour afficher le resultat (erreur)
	//document.getElementById("errorText").innerHTML = message;
	
	// On cache l'image de chargement
	document.getElementById("imgChargement").className = 'PictureHidden';
	
	// On affiche l'image d'erreur
	document.getElementById("imgOK").className = 'PictureHidden';
	document.getElementById("imgErreur").className = 'PictureVisible';

	// Mise à jour de la signature
	UpdateSignature();
};


/**
 * 	Le pseudo saisie est valide
 */
function PseudoValide()
{
	// On vide le contenu du champ errorText
	//document.getElementById("errorText").innerHTML = "";

	// On cache l'image de chargement
	document.getElementById("imgChargement").className = 'PictureHidden';

	// Affichage de l'image imgOK
	document.getElementById("imgOK").className = 'PictureVisible';
	document.getElementById("imgErreur").className = 'PictureHidden';

	// Mise à jour de la signature
	UpdateSignature();
};


function UpdateSignature()
{
	var pseudo	= document.getElementById("pseudo").value;
	var br		= document.getElementById("br").checked;		br = (br==true) ? 1 : 0;
	var xp		= document.getElementById("xp").checked;		xp = (xp==true) ? 1 : 0;
	var kd		= document.getElementById("kd").checked;		kd = (kd==true) ? 1 : 0;
	var fond	= document.getElementById("fond").value;
	var img		= document.getElementById('imgSignature');
	
	//
	// Création de l'url
	//var url = 'http://localhost/frc/signature/signature.php?pseudo=' + pseudo + '&br=' + br + '&xp=' + xp + '&kd=' + kd + '&fond=' + fond;
	url = location.href;
	url = url.substring(0,url.lastIndexOf('/')+1);
	url = url + 'signature.php?pseudo=' + pseudo + '&br=' + br + '&xp=' + xp + '&kd=' + kd + '&fond=' + fond;
	
	// Mise à jour de l'image
	img.src = url;
	
	//
	url = '[img]' + url + '[/img]';
	document.getElementById("urlSignature").innerHTML = url;
}
