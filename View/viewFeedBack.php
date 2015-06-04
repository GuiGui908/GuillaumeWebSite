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
<div class="left"> 
    <h1>Donnez votre avis ici ! <img src="Ressources/images/clinOeil.jpg" alt=";)"/></h1>
    <h3>Vos réflexions sont anonymes et stockées dans la base de données. Ca aide à améliorer le design !</h3>
	<br />
    <form name="post" method="post" action="feedback.php?action=valider" onSubmit="return envoyer();" enctype="multipart/form-data">
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
		<div class="submitButtons">
		<input class="btn" type="submit" value="Valider et envoyer">
		<input class="btn" type="reset" value="Réinitialiser tous les champs">
		</div>
    </form>
</div>

<script type="text/javascript">
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
</script>