<?php
class FeedBackController extends Controller
{
	function defaultAction()
	{
		$reponse = $this->DB->query('SELECT * FROM feedback');
		$comments = array();
		$com = array();
		while ($donnees = $reponse->fetch()) {
			$com['ip']  = htmlspecialchars($donnees['ip']);
			$com['dat'] = htmlspecialchars($donnees['date']);
			$com['gen'] = htmlspecialchars($donnees['general']);
			$com['fac'] = htmlspecialchars($donnees['facile']);
			$com['pho'] = htmlspecialchars($donnees['photo']);
			$com['fic'] = htmlspecialchars($donnees['fichier']);
			$com['bug'] = htmlspecialchars($donnees['bug']);
			$comments[] = $com;
		}
		$reponse->closeCursor();
		parent::setVariable('commentaires', $comments);
	}

	// mysql_real_escape_string		// Secure chars
	function AjaxStockerFeedback() {
		$requete = $this->DB->prepare("INSERT INTO feedback(ip, general, facile, photo, fichier, bug) VALUES(:ip, :gen, :fac, :pho, :fic, :bug)");
		$requete -> bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		$requete -> bindParam(':gen', $_POST['general']);
		$requete -> bindParam(':fac', $_POST['facile']);
		$requete -> bindParam(':pho', $_POST['photo']);
		$requete -> bindParam(':fic', $_POST['fichier']);
		$requete -> bindParam(':bug', $_POST['bug']);
		$requete -> execute();
		if(!$requete)		// Si erreur
			echo "false;Y'a eu un problème pendant l'enregistrement des données....";
	}
	
	function AjaxEnvoyerNotif()
	{
		if(	$GLOBALS['URL'] == 'http://localhost/guillaumewebsite/' )		// On envoie rien avec localhost
		{
			echo "On est en Localhost. Infos stockées, Merciiii :)";
			return;
		}
		
		$boundary = md5(uniqid(microtime(), TRUE));		// clé aléatoire de limite (utile pour les pièces jointes, pas trop pour le contenu....
		$header  = 'From: FeedBack - mon site hostinger <noreply@siteperso.hostinger.com>'."\n";
		$header .= 'MIME-Version: 1.0'."\n";
		$header .= 'Content-Type: multipart/mixed; boundary='.$boundary."\n\n";
		$destinataire = 'guigui908b@gmail.com';
		$sujet = 'FeedBack de '.$_SERVER['REMOTE_ADDR'].'- Mon site';
		
		$contenu = "Wesh ! Y'a un mec qui a mis un feedBack sur le site ! Va voir !";

		if(mail($destinataire, $sujet, $contenu, $header))		// Envoi
			echo "Informations stockées et notifiées par mail à Guillaume, Merciiii :)";
		else
			echo "false;Y'a eu un probleme avec l'envoi du mail de notification :/</br>Opération annulée...";
	}
	
	function AjaxCheckMotDePasse($mdp)
	{
		$requete = $this->DB->query("SELECT mdp FROM user WHERE nom='admin'");
		$reponse = $requete->fetch();
		// echo '$mdp === $reponse[mdp] = '."\n".$mdp."\n".$reponse['mdp'];   		// LES DEUX CHAINES SONT IDENTIQUES.....
		//if($reponse && $mdp == $reponse['mdp'])		// mdp admin = md5(toor)	// ET POURTANT ELLES SONT PAS EGALES !!!!!!!!!
		if($mdp === '7b24afc8bc80e548d66c4e7ff72171c5')
			echo 'good';
		else
			echo 'bad';
	}
}