<?php
	$err = $this->getVariable('erreur');
	$suc = $this->getVariable('succes');
	echo '<div class="erreur" id="erreur" style="display:none;"><img src="Ressources/images/err.jpg" alt="<err.jpg>" />';		// Ona  besoin quel e div soit tjrs présent psk on va y accéder avec le JS
	if(isset($err)) echo $err;		// Si message d'erreur

	echo '</div><div class="succes" id="succes" style="display:none;"><img src="Ressources/images/succes.jpg" alt="<succes.jpg>" />';
	if(isset($suc)) echo $suc;		// Si message de succès

	echo '</div><div class="info" id="info" style="display:none;"><img src="Ressources/images/info.jpg" alt="<info.jpg>" />';
	if(isset($info)) echo $info;	// Si message d'information
	echo '</div>';
?>
	<div class="left" id="donner"> 
		<a href="#" class="btn alignRight" onclick="displayComments();">Voir les avis</a>
		<h1>Donnez votre avis ici ! <img src="Ressources/images/clinOeil.jpg" alt=";)"/></h1>
		<h3>Vos réflexions sont anonymes et stockées dans la base de données. Ca aide à améliorer le design !</h3>
		<br />
		<form id="fomrcom" method="post" action="feedback.php?action=AjaxEnvFeedBack">
			<div class="ligne"><p>Que pensez-vous du site en général ? des couleurs ?</p>
				<textarea class="centre" rows="3" cols="55" name="general" ></textarea>
			</div>
			<div class="ligne"><p>Facile à utiliser ?</p>
				<input type="radio" name="facile" value="y" />Oui&nbsp;&nbsp;
				<input type="radio" name="facile" value="b" />Bof&nbsp;&nbsp; 
				<input type="radio" name="facile" value="n" />Non&nbsp;&nbsp;  
				<input type="radio" id="NoOp" name="facile" value="null" checked />Pas d'avis 
			</div>
			<div class="ligne"><p>A propos de la page Photos :</p>
				<textarea class="centre" rows="2" cols="55" name="photo" ></textarea>
			</div>
			<div class="ligne"><p>A propos de la section d'échange de fichiers :</p>
				<textarea class="centre" rows="2" cols="55" name="fichier" ></textarea>
			</div>
			<div class="ligne"><p>Un bug à signaler ?</p>
				<textarea class="centre" rows="2" cols="55" name="bug" ></textarea>
			</div>
			<input class="btn alignRight" type="submit" value="Valider et envoyer">
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

	$(document).ready(function() {			// Quand le DOM est prêt
		document.getElementById('voir').style.display = 'none';
		
		// Soumission Ajax du formulaire (http://chez-syl.fr/2012/01/envoyer-un-formulaire-en-ajax-avec-jquery-et-json/)
		$('#fomrcom').submit(function()
		{
			// Véréfication des champs : Si au moins l'un a été renseigné, on envoi le com
			if( $("textarea[name='general']").val().length != 0  ||
				$("textarea[name='photo']").val().length != 0  ||
				$("textarea[name='fichier']").val().length != 0  ||
				$("textarea[name='bug']").val().length != 0  ||
				$("input[name='facile']:checked").val() != "null" )
			{
				document.getElementById('info').innerHTML += "<img src=\"Ressources/images/waitBlue.gif\" alt=\"<Patientez svp>\" style=\"width:30px;\" />Envoi du formulaire en cours ....";
				document.getElementById('info').style.display = "block";

				// Envoi de la requête HTTP en mode asynchrone
				$.ajax({
					url: $(this).attr('action'), 		// Le nom du fichier indiqué dans le formulaire
					type: $(this).attr('method'), 	// La méthode indiquée dans le formulaire (get ou post)
					data: $(this).serialize(),		// Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
					dataType: "text",
					success: function(resultat) {
						$('#fomrcom')[0].reset();
						document.getElementById('info').style.display = "none";
						if(resultat.substr(0, 6) == 'false;')
						{
							document.getElementById('erreur').innerHTML += resultat.substr(7);	// substr(7) est le message d'erreur
							document.getElementById('erreur').style.display = "block";
							setTimeout("document.getElementById('erreur').style.display = 'none';", 4000);
						}
						else {
							document.getElementById('succes').innerHTML += resultat;
							document.getElementById('succes').style.display = "block";
							setTimeout("document.getElementById('succes').style.display = 'none';", 4000);
						}
					},
					error: function() {
						document.getElementById('info').style.display = "none";
						document.getElementById('erreur').innerHTML += "Erreur pendant l'appel Ajax :( Le formulaire n'a pas pu être transmis :/";
						document.getElementById('erreur').style.display = "block";
						setTimeout("document.getElementById('erreur').style.display = 'none';", 4000);
					}
				});
			}
			return false;
		});
	});
	
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
					"<img id=\"gifPatience\" src=\"Ressources/images/waitWhite.gif\" alt=\"<Veuillez patienter svp...>\" style=\"text-align:center;display:none;\"/>"+
					"<input type=\"password\" id=\"pwd\" placeholder=\"Mot de passe\" /><br />" , options);
	}
</script>