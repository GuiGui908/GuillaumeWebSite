<?php
class FeedBackController extends Controller
{
	function defaultAction()
	{
	}

	// mysql_real_escape_string		// Secure chars
	function stockerFeedback() {
		$general = filter_input(INPUT_POST, 'general', FILTER_SANITIZE_SPECIAL_CHARS);
		$facile  = filter_input(INPUT_POST, 'facile', FILTER_SANITIZE_SPECIAL_CHARS);
		$photo   = filter_input(INPUT_POST, 'photo', FILTER_SANITIZE_SPECIAL_CHARS);
		$fichier = filter_input(INPUT_POST, 'fichier', FILTER_SANITIZE_SPECIAL_CHARS);
		$bug     = filter_input(INPUT_POST, 'bug', FILTER_SANITIZE_SPECIAL_CHARS);
		
//echo $general.'<br>'.$facile.'<br>'.$photo.'<br>'.$fichier.'<br>'.$bug;
		
		if(false)		// Si erreur
			parent::setVariable("erreur", "Y'a eu un probleme avec le stockage des données :/</br>Opération annulée...");
	}
	
	function envoyerNotif()
	{
		$boundary = md5(uniqid(microtime(), TRUE));		// clé aléatoire de limite (utile pour les pièces jointes, pas trop pour le contenu....
		$header  = 'From: Mon site - Hostinger <noreply@siteperso.hostinger.com>'."\n";
		$header .= 'MIME-Version: 1.0'."\n";
		$header .= 'Content-Type: multipart/mixed; boundary='.$boundary."\n\n";
		$destinataire = 'guigui908b@gmail.com';
		$sujet = 'FeedBack - Mon site';
		
		$contenu = "Wesh ! Y'a un mec qui a mis un feedBack sur le site ! Va voir !";

		if(mail($destinataire, $sujet, $contenu, $header))		// Envoi
			parent::setVariable("succes", "Informations envoyées par mail à Guillaume, Merciiii :)");
		else
			parent::setVariable("erreur", "Y'a eu un probleme avec l'envoi de la notification :/</br>Opération annulée...");
	}
}