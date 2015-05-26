<div class="adressBar">
<?php
	$err = $this->getVariable('erreur');
	$suc = $this->getVariable('succes');
	if( isset($err) )	// Si message d'erreur
	{
		echo '<div class="erreur">ERREUR !! '.$err.'</div>';
	}
	else if( isset($suc) )	// Si message de succès
	{
		echo '<div class="succes">'.$suc.'</div>';
	}
	
	echo '<a href="';			// Arrow Left
	if($this->getVariable('returnPath') == '')
		echo '#">';
	else
		echo 'Partage.php?action=setDir&path='.$this->getVariable('returnPath').'">';
	echo '	<img src="'.$GLOBALS['URL'].'/Ressources/images/arrowLeft.jpg" />';
	echo '</a>';	
	echo '<b>.'.$this->getVariable('currentPath').'</b>';		// Chemin
?>
</div>

<div class="left noselect">
<h3><img src="<?php echo $GLOBALS['URL']; ?>/Ressources/images/dossier.jpg" /> Dossiers</h3>
<hr />
	<div class="boxFichiers">
	<?php
		$arrayDir = $this->getVariable('arrayDir');
		foreach($arrayDir as $dirName)
		{
			echo '<a href="Partage.php?action=setDir&path='.$this->getVariable('currentPath').$dirName['nom'].'/" class="fic" id="'.$dirName['id'].'">'.$dirName['nom'].'</a>';
		}
	?>
	</div>
<br /><h3><img src="<?php echo $GLOBALS['URL']; ?>/Ressources/images/fichier.jpg" /> Fichiers</h3>
<hr />
	<div class="boxFichiers">
	<?php
		$arrayFic = $this->getVariable('arrayFic');
		foreach($arrayFic as $dirName)
		{
			echo '<a href="javascript:clic'.$dirName['id'].'()" class="fic" id="'.$dirName['id'].'">'.$dirName['nom'].'</a>';
		}
	?>
	</div>
</div>

<div class="right">
	<h3>Général</h3> 
	<b>Espace utilisé :</b><br />
	<?php echo $this->getVariable('totalSize'); ?> / 999 Mo<br /><br />
	<a href="Partage.php?action=mkdir&path=toto">Créer un dossier</a><br />
	<a href="#">Importer des fichiers</a><br /><br />
	
	<h3>Détails du fichier</h3> 
	<b>Nom :</b><br />
	<span  id="detail_nom">-- -- -- --</span><br /><br />
	<b>Type : </b><span  id="detail_type">--</span><br /><br />
	<b>Taille :</b> <span  id="detail_taille">--</span> Mo<br /><br />
	<b>Date d'import :</b><br />
	<span  id="detail_date">-- -- --</span><br /><br />
	<a href="#" id="detail_dl" download="default">Télécharger</a>&nbsp;&nbsp;
	<a href="#" id="detail_suppr">Supprimer</a><br /><br />
</div>

<script type="text/javascript" src="Ressources/CustomJs/alert.js"></script>
<script type="text/javascript">
<?php
	// Crée les fonctions apellées lorsqu'on clique sur un fichier
	foreach($arrayFic as $dirName)
	{
		// affiche les détails correspondant en javaScript
		echo 'function clic'.$dirName['id'].'() {';
		echo '	document.getElementById("detail_nom").innerHTML = "'.$dirName['nom'].'";';
		echo '	document.getElementById("detail_type").innerHTML = "'.$dirName['type'].'";';
		echo '	document.getElementById("detail_taille").innerHTML = "'.$dirName['taille'].'";';
		echo '	document.getElementById("detail_date").innerHTML = "'.$dirName['date'].'";';
		echo '	document.getElementById("detail_dl").href = "'.$dirName['lien'].'";';
		echo '	document.getElementById("detail_dl").download = "'.$dirName['nom'].'";';
		echo '	document.getElementById("detail_suppr").href = "Partage.php?action=supprFile&path='.$dirName['lien'].'";';
		echo '}';
	}
?>
	document.getElementById("detail_suppr").onclick = function() {
		if( strcmp(document.getElementById("detail_suppr").href, '#')==0 )
			return false;
		var options = {
			"title": "Etes-vous vraiment sûr ?",
			"modal": "True"
		};
		msg.open( "Voulez-vous supprimer le fichier du serveur ?" , options);
		return false;
	};
</script>
