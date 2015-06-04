<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP - Envoi du mail</title>
</head>

<body>
<p>Envoi par mail des infos saisies.....</p>
<?php
	// Récupération des données
	$TYPE = $_POST['type'];
	$SURFACE = $_POST['surf'];
	$PRIX = $_POST['prix'];
	$ADR1 = $_POST['adr1'];
	$ADR2 = $_POST['adr2'];
	$DESCRIPTION = $_POST['desc'];

	$NOM = $_POST['nom'];
	$MAIL = $_POST['mail'];
	$TEL = $_POST['tel'];

	// clé aléatoire de limite
	$boundary = md5(uniqid(microtime(), TRUE));
	// Header:  To, From...
	$HEADER  = 'From: Site du Logement <pm.equipe.logement@gmail.com>'."\n";
	$HEADER .= 'MIME-Version: 1.0'."\n";
	$HEADER .= 'Content-Type: multipart/mixed; boundary='.$boundary."\n\n";
	$DESTINATAIRE = 'pm.equipe.logement@gmail.com';
	$SUJET = 'Annonce logement';

	// Texte du message
	$MESSAGE  = "--".$boundary."\n";
	$MESSAGE .= "Content-Type: text/html; charset=ISO-8859-1\n
	<html><body>
	<p>Hello :)</p>
	<p>Type : $TYPE</p>
	<p>Surface : $SURFACE</p>
	<p>Prix : $PRIX</p>
	<p>Adresse : $ADR1<br />$ADR2</p>
	<p>Description : <br />$DESCRIPTION</p>
	<p>Nom du contact : $NOM</p>
	<p>Mail : $MAIL</p>
	<p>Tel : $TEL</p>
	</body></html>";
	$MESSAGE .= "--".$boundary."\n";


	// Pièces jointes éventuelles
	$envoi = true;		// Si il y a eu un problème de pièce jointe autre que 'ignorée...', on envoie pas !
	for($i=1; $i<=3; $i++)
	{
		// Vérification des photos (taille max et erreurs diverses)
		switch($_FILES['photo'.$i]['error'])
		{
			case UPLOAD_ERR_NO_FILE : $err = ' :   ignorée.... '; break;
			case UPLOAD_ERR_INI_SIZE : $err = ' :   Erreur!! Fichier dépassant la taille autorisée par PHP.... '; break;
			case UPLOAD_ERR_FORM_SIZE : $err = ' :   Erreur!! Fichier dépassant la taille maximale autorisée (2Mo par photo).... '; break;
			case UPLOAD_ERR_PARTIAL : $err = ' :   Erreur!! Fichier transféré partiellement.... '; break;
			default : $err = '';
		}

		// Si la photo existe et n'a pas d'erreurs
		if($err == '' AND file_exists($_FILES['photo'.$i]['tmp_name']))							
		{
			// On stocke ses renseignements
			$photo_name    = $_FILES['photo'.$i]['name'];
			$photo_tmpname = $_FILES['photo'.$i]['tmp_name'];
			$photo_type    = $_FILES['photo'.$i]['type'];
			$content = file_get_contents($photo_tmpname);
			$content = chunk_split(base64_encode($content));

			// On l'ajoute à la suite du message
			$MESSAGE .= "--".$boundary."\n";
			$MESSAGE .= 'Content-type:'.$photo_type.';name='.$photo_name."\n";
			$MESSAGE .= 'Content-transfer-encoding: base64'."\n";
			$MESSAGE .= 'Content-Disposition: attachment; filename='.$photo_name."\n\n";
			$MESSAGE .= $content."\n";
		}
		else if($err != ' :   ignorée.... ')	// si Erreur autre que 'ignorée...' : on arrête l'envoi
		{
			echo '  Photo'.$i.$err;
			$envoi = false;
		}
	}
	// Fin des pièces jointes
	$MESSAGE .= "--".$boundary."--\n";

	// Envoi
	if($envoi)
		$ok = @mail($DESTINATAIRE, $SUJET, $MESSAGE, $HEADER);
	if($ok)
		echo "<br /><br /><br />Mail envoyé avec SUCCES :)<br />
		      Les infos saisies ont bien été transmises... ";
	else
		echo "<br /><br /><br />ECHEC de l'envoi du mail :(<br />Réessayez... ";
?>
Continuer à <a href="index.html">surfer sur notre site</a>.
</body>
</html>