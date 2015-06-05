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
?>
	<div class="left" id="donner"> 
		<a href="#" class="btn alignRight" onclick="displayComments();">Voir les avis</a>
		<h1>Donnez votre avis ici ! <img src="Ressources/images/clinOeil.jpg" alt=";)"/></h1>
		<h3>Vos réflexions sont anonymes et stockées dans la base de données. Ca aide à améliorer le design !</h3>
		<br />
		<form name="post" method="post" action="feedback.php?action=envFeedBack" onSubmit="return envoyer();" enctype="multipart/form-data">
			<div class="ligne"><p>Que pensez-vous du site en général ? des couleurs ?</p>
				<textarea class="centre" rows="3" cols="55" name="general"></textarea>
			</div>
			<div class="ligne"><p>Facile à utiliser ?</p>
				<input type="radio" name="facile" value="y" />Oui&nbsp;&nbsp;
				<input type="radio" name="facile" value="b" />Bof&nbsp;&nbsp; 
				<input type="radio" name="facile" value="n" />Non&nbsp;&nbsp;  
				<input type="radio" id="NoOp" name="facile" value="null" checked />Pas d'avis 
			</div>
			<div class="ligne"><p>A propos de la page Photos :</p>
				<textarea class="centre" rows="2" cols="55" name="photo"></textarea>
			</div>
			<div class="ligne"><p>A propos de la section d'échange de fichiers :</p>
				<textarea class="centre" rows="2" cols="55" name="fichier"></textarea>
			</div>
			<div class="ligne"><p>Un bug à signaler ?</p>
				<textarea class="centre" rows="2" cols="55" name="bug"></textarea>
			</div>
			<a href="#" class="btn alignRight" onclick="post.submit();">Valider et envoyer</a>
		</form>
	</div>
	<div class="left" id="voir">
		<a href="#" class="btn alignRight" onclick="return displayAvis();">Donner son avis</a>
		<h1>Commentaires reçus</h1>
		<?php $coms = $this->getVariable('commentaires');
		$ii = 0;
		foreach($coms as $com) {
			echo ($ii%2==0)? '<div class="comment louder">' : '<div class="comment">';
			echo '<u>Par '.$com['ip'].' le '.$com['dat'].' :</u><br />';
			if($com['gen']!='') echo '<b>Site en gen ? coul ? </b> '.$com['gen'];
			if($com['fac']!='null') {
				echo '<br /><b>Facile a utiliser : </b> ';
				if($com['fac']=='y') echo 'Oui';
				else if($com['fac']=='b') echo 'Bof';
				else echo 'Non';
			}
			if($com['pho']!='') echo '<br /><b>Page photo : </b> '.$com['pho'];
			if($com['fic']!='') echo '<br /><b>Page fichiers : </b> '.$com['fic'];
			if($com['bug']!='') echo '<br /><b>Bug ? </b> '.$com['bug'];
			echo '</div>';
			$ii++;
		} ?>
	</div>
<script type="text/javascript">
	document.getElementById('voir').style.display = 'none';
	function envoyer()
	{
		with(window.document.post)
		{
			// Si tous les textArea sont vides et que "Pas d'avis" est coché
			if(general.value.length==0 && photo.value.length==0 && fichier.value.length==0 && bug.value.length==0 && document.getElementById("NoOp").checked)
			{
				return false;
			}
		}
	}
	
	function displayAvis()
	{
		document.getElementById("voir").style.display = "none";
		document.getElementById("donner").style.display = "block";
		return false;
	}
	
	function displayComments()
	{
		var options = {
			"title": "Vous accédez à une page sécurisée.",
			"modal": "True",
			"btnOk" : "Ok",
			"btnNop" : "Annuler",
			"action": "accesComments",
		};
		msg.open( "Pour voir les commentaires vous devez entrer le mot de passe admin :<br />"+
					"<span id=\"popupErrMsg\"></span><br /></br>"+
					"<input type=\"password\" id=\"pwd\" placeholder=\"Mot de passe\" /><br />" , options);
	}
</script>