<div class="adressBar">
<?php
	$err = $this->getVariable('erreur');
	$suc = $this->getVariable('succes');
	if( isset($err) )	// Si message d'erreur
	{
		echo '<div class="erreur" id="erreur"><img src="Ressources/images/err.jpg" alt="<err.jpg>" />'.$err.'</div>';
	}
	else if( isset($suc) )	// Si message de succès
	{
		echo '<div class="succes" id="succes"><img src="Ressources/images/succes.jpg" alt="<succes.jpg>" />'.$suc.'</div>';
	}
	echo '<div class="info" style="display:none;">'.
		 '<img src="Ressources/images/info.jpg" alt="<info.jpg>" />'.
		 '<img src=\"Ressources/images/waitBlue.gif\" alt=\"<Patientez svp>\" style=\"width:30px;\" />'.
		 '<span id="info"></span> </div>'.
	
	echo '<a href="';			// Arrow Left
	if($this->getVariable('returnPath') == '')
		echo '#">';
	else
		echo 'Partage.php?action=setDir&path='.$this->getVariable('returnPath').'">';
	echo '	<img src="'.$GLOBALS['URL'].'/Ressources/images/arrowLeft.jpg" alt="<Back>"/>';
	echo '</a>';	
	echo '<b>.'.$this->getVariable('currentPath').'</b>';		// Chemin
?>
</div>

<div class="left noselect">
<h3><img src="<?php echo $GLOBALS['URL']; ?>/Ressources/images/dossier.jpg" alt="<Rep_ico>"/> Dossiers</h3>
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
<br /><h3><img src="<?php echo $GLOBALS['URL']; ?>/Ressources/images/fichier.jpg" alt="<File_ico>"/> Fichiers</h3>
<hr />
	<div class="boxFichiers">
	<?php
		$arrayFic = $this->getVariable('arrayFic');
		foreach($arrayFic as $dirName)
		{
			echo '<a href="javascript:clic'.$dirName['id'].'()" class="fic" id="'.$dirName['id'].'" ondblclick="javascript:DoubleClic'.$dirName['id'].'()">'.$dirName['nom'].'</a>';
		}
	?>
	</div>
</div>

<div class="right">
	<h3>Général</h3> 
	<b>Espace utilisé :</b><br />
	<span id="totalSize"><?php echo $this->getVariable('totalSize'); ?></span> Mo / 1 Go<br /><br />
	<a href="#" id="create_dir" class="btn">Créer un dossier...</a><br /><br />
	<a href="#" id="up_file" class="btn">Importer fichiers...</a><br /><br />
	<a href="#" id="suppr_all" class="btn">Supprimer dossier...</a><br /><br /><br />
	
	<h3>Détails du fichier</h3> 
	<b>Nom :</b><br />
	<span  id="detail_nom">-- -- -- --</span><br /><br />
	<b>Type : </b><span  id="detail_type">--</span><br /><br />
	<b>Taille :</b> <span  id="detail_taille">--</span> Mo<br /><br />
	<b>Date d'import :</b><br />
	<span  id="detail_date">-- -- --</span><br /><br />
	<a href="#" id="detail_dl" download="default" class="btn">Télécharger</a><br /><br />
	<a href="#" id="detail_suppr" class="btn">Supprimer...</a><br /><br />
	<span id="suppr_link" class="hidden">#</span>
</div>

<script type="text/javascript">
$(document).ready( function() {		// Efface les messages (erreur ou succes) au bout de 4s
	if($('#erreur').length > 0) 
		setTimeout("$('#erreur').css('display', 'none');", 4000);
	else if($('#succes').length > 0) 
		setTimeout("$('#succes').css('display', 'none');", 4000);
});


<?php
	// Crée les fonctions apellées lorsqu'on clique sur un fichier
	foreach($arrayFic as $dirName)
	{
		// affiche les détails correspondant en javaScript
		echo 'function clic'.$dirName['id'].'() {';
		echo ' 	 document.getElementById("detail_nom").innerHTML = "'.$dirName['nom'].'";';
		echo '	 document.getElementById("detail_type").innerHTML = "'.$dirName['type'].'";';
		echo '	 document.getElementById("detail_taille").innerHTML = "'.$dirName['taille'].'";';
		echo '	 document.getElementById("detail_date").innerHTML = "'.$dirName['date'].'";';
		echo '	 document.getElementById("detail_dl").href = "'.$dirName['lien'].'";';
		echo '	 document.getElementById("detail_dl").download = "'.$dirName['nom'].'";';
		echo ' 	 document.getElementById("suppr_link").innerHTML = "Partage.php?action=supprFile&path='.$dirName['lien'].'";';
		echo '}';


		echo 'function DoubleClic'.$dirName['id'].'() {';
		echo '	 document.getElementById("detail_dl").download = "'.$dirName['nom'].'";';
		echo '   document.getElementById("detail_dl").click();';
		echo '}';
	}
?>

	var suppr = document.getElementById("detail_suppr");
	suppr.addEventListener("click", function() {
		if(document.getElementById("detail_nom").innerHTML != "-- -- -- --") {
			var options = {
				"title": "Etes-vous vraiment sûr ?",
				"btnOk" : "Oui je le veux",
				"btnNop" : "NON T ouf !",
				"modal": "True",
				"link" : document.getElementById("suppr_link").innerHTML
			};
			msg.open( "Voulez-vous supprimer le fichier \"" + document.getElementById("detail_nom").innerHTML + "\"du serveur ?<br /><br />Attention, c'est IRREVERSIBLE !<br />" , options);
		}
		return false;
	});
	
	var mkdir = document.getElementById("create_dir");
	mkdir.addEventListener("click", function() {
		var options = {
			"title": "Créer un dossier",
			"modal": "True",
			"btnOk" : "Créer",
			"btnNop" : "Annuler",
			"action": "creerep",
			"link" : "Partage.php?action=mkdir&path=<?php echo $this->getVariable('currentPath'); ?>"

		};
		msg.open( "Créer un nouveau dossier dans  \".<?php echo $this->getVariable('currentPath'); ?>\"<br /><br />"+
					"<input type=\"text\" id=\"dirNewFolder\" placeholder=\"Nom du dossier\" /><br />" , options);
		return false;
	});

	var upload = document.getElementById("up_file");
	upload.addEventListener("click", function() {
		var options = {
			"title": "Importer des fichiers",
			"modal": "True",
			"btnOk" : "Importer",
			"btnNop" : "Annuler",
			"action": "uploadfiles",
			"link" : "#"

		};
		msg.open( "Sélectionnez les fichiers à importer :<br /><br />"+
					"<form id=\"FormUp\" action=\"Partage.php?action=up&path=<?php echo $this->getVariable('currentPath'); ?>\" method=\"post\" enctype=\"multipart/form-data\">"+
					"<input type=\"file\" name=\"upInput[]\" id=\"upInput\" multiple /></form><br />" , options);
		return false;
	});

	var supprToutDossier = document.getElementById("suppr_all");
	supprToutDossier.addEventListener("click", function() {
		var options = {
			"title": "Supprimer tout le dossier",
			"modal": "True",
			"btnOk" : "Oui",
			"btnNop" : "Annuler",
			"link" : "Partage.php?action=supprAll&path=<?php echo $this->getVariable('currentPath'); ?>"

		};
		msg.open( "Voulez-vous vraiment supprimer tout le dossier<br />"+
				"<?php if($currentPath === '/')   echo 'racine';
						else {
							$currentPath = $this->getVariable('currentPath');				// Isole et echo du nom du dossier courant
							$indiceDebutNomDossierCourant = strrpos($currentPath, '/', -2);					  
							echo '\"'.substr($currentPath, $indiceDebutNomDossierCourant+1, strlen($currentPath)-$indiceDebutNomDossierCourant-2) .'\"';
						}
				?> et tout son contenu ?" , options);
		return false;
	});

</script>
