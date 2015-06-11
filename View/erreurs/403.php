<?php
	$pageName = 'Erreur403';
	require_once('../../VarServer.php');
	require_once('../contexte/header.php');
	require_once('../contexte/menu.php');
?>
<div class="left"> 
	<h1>ERREUR 403 !!!!</h1>
	<br />Accès non autorisé.<br /><br />Causes possibles :
	<ul style="margin-left:30px">
	<li>Mauvais mot de passe</li>
	<li>URL incorrecte</li>
	<li>Vous téléchargez un fichier exécutable et c'est interdit par l'hébergeur pour l'instant.<br/>Bug bientôt corrigé....</li>
	</ul>
	<br />
	<?php echo $_SERVER["REQUEST_URI"]; ?>
	<?php $arrayURI = explode("/", $_SERVER["REQUEST_URI"], 5); ?>
</div>
<?php require_once('../contexte/footer.php');?>
