<div class="left"> 
	<h1 id="msgTop">Sélectionnez un album dans la liste à droite</h1>
	<img id="imgBig"src="Ressources/images/rien.jpg"/>
	<hr /><br />
	<div id="listTof"><span>
		<!-- Contenu généré ici, ci-dessous un modèle de div généré
		<div class="imgMiniature" id="123" onmouseover="miniatureOver(123);" onmouseout="miniatureOut(123)">
			<a href="#" class="mini"><img src="Ressources/images/rien.jpg"/></a>
			<a href="#" class="dl"><img src="Ressources/images/DL.png" alt="DL"></a>
			<a href="#" class="suppr"><img src="Ressources/images/Suppr.png" alt="Suppr"></a>
		</div>
		-->
	</div>
</div>

<div class="right"> 
	<h3>Album "<span id="nomAlb">---</span>"<br />(<span id="nbTof">0</span> photos)</h3>
	<br />
	<b>Propriétaire : </b><br />
	<span id="proprio">--</span><br />
	<b>Description : </b><br />
	<span id="description">--</span><br /><br />
	<a href="javascript: ajouterPhoto();" id="add_Tof" class="btn">Ajouter des photos</a><br /><br />
	<a href="javascript: supprAlbum();" id="dl_Alb" class="btn">Supprimer l'album</a><br /><br />
	<a href="javascript: return false;" id="dl_Alb" class="btn">Télécharger l'album</a><br /><br />

	<h3>Tous les albums <a href="#" title="Grouper par Propriétaire"><img src="Ressources/images/arbo.jpg" alt="GroupByOwner"/></a></h3>
	<a href="javascript: creerAlbum();" id="creer_Album" class="btn">Créer un album...</a><br /><br />
	<div id="listeAlbum">
		<!-- Contenu généré ici -->
	</div>
</div>


<script>
var arrayAlbums;
$(document).ready(function() {
	 arrayAlbums = JSON.parse(' <?php echo $this->getVariable('arrAlb'); ?> ');
	 for(album in arrayAlbums) {
		 $("#listeAlbum").append("<a href=\"javascript: displayAlbum('"+ arrayAlbums[album]["id"] +"');\">"+ arrayAlbums[album]["nom"] +
									"<img class=\"loading\" id=\"loading"+ arrayAlbums[album]["id"] +"\" src=\"Ressources/images/waitWhite.gif\" alt=\"Patientez.....\" /></a>");
	}
});

function displayAlbum(idAlbum) {
	$("#loading"+idAlbum).css("display", "inline");
	for(album in arrayAlbums) {
		if(arrayAlbums[album]["id"] === idAlbum) {
			$("#msgTop").text(arrayAlbums[album]["nom"]);
			$("#nomAlb").text(arrayAlbums[album]["nom"]);
			$("#proprio").text(arrayAlbums[album]["proprio"]);
			$("#description").text(arrayAlbums[album]["desc"]);
			break;
		}
	}
	
	// Requête ajax pour charger les images
	$.ajax({
		url : 'photo.php',
		type : 'GET',
		data : 'action=AjaxGetURLPhotos&idAlbum=' + idAlbum,
		dataType : 'text',
		success : function(resultat, statut) {
			$("#loading"+idAlbum).hide();		// Efface la patience
			var Photos = JSON.parse(resultat);
			var elementPhoto;
			var cptNbImg = 0;
			$("#listTof").text("");
			
			for(photo in Photos) {
				var idTof = Photos[photo]["id"];
				var urlTof = Photos[photo]["url"];
				var elementPhoto = "<div class=\"imgMiniature\" id=\"" + idTof + "\" onmouseover=\"miniatureOver("+ idTof +");\" onmouseout=\"miniatureOut("+ idTof +");\">";
				elementPhoto += "<a href=\"javascript: affichePhoto(\'"+ urlTof +"\');\" class=\"mini\"> <img src=\""+ urlTof +"\" alt=\""+Photos[photo]["nom"] +"\"/> </a>";
				elementPhoto += "<a href=\""+ urlTof +"\" download=\""+ Photos[photo]["nom"] +"\" class=\"dl\"><img src=\"Ressources/images/DL.png\" alt=\"DL\"></a>";
				elementPhoto += "<a href=\"javascript: supprPhoto("+ idTof +");\" class=\"suppr\"><img src=\"Ressources/images/Suppr.png\" alt=\"Suppr\"></a></div>";
				
				$("#listTof").append(elementPhoto);
				cptNbImg++;
			}
			$("#nbTof").text(cptNbImg);
		},
		error : function(resultat, statut, erreur){
			$("#loading"+idAlbum).hide();		// Efface la patience
			alert('Erreur dans la requête Ajax ! Veuillez réessayer<br />Err : '+resultat);
		}
	});

	$("#loading"+idAlbum).hide();		// Efface la patience
}

function miniatureOver(idMiniature) {
	$("#"+idMiniature).children(".suppr").show();
	$("#"+idMiniature).children(".dl").show();
	$("#"+idMiniature).css("margin-right", "-75px");
}
function miniatureOut(idMiniature) {
	$("#"+idMiniature).children(".suppr").hide();
	$("#"+idMiniature).children(".dl").hide();
	$("#"+idMiniature).css("margin-right", "0px");
}

function affichePhoto(url) {
	$("#imgBig").attr("src", url);
}

function supprPhoto(idPhoto) {
	alert("TODO");
}

function creerAlbum() {
	alert("TODO");
}

function ajouterPhoto() {
	alert("TODO");
}

function supprAlbum() {
	alert("TODO");
}


</script>



<!--
<div style="display:none;">
<hr />
<br />Fonctionnalités Gauche:
<br />voir un album
<br />	propriétaire
<br />	description
<br />	lien : voir diaporama avec fil de défilement en bas avec les miniatures
<br />	images miniatures (40x40) (toutes dc pas de page Suiv-Prec)
<br />	clic dessus -> taille réelle (animation)
<br />	clic sur icone poubelle ou fleche de telechargement
<br />Rajouter un album 
<br />	-> nom de propriétaire 
<br />	-> fenetre de choix multiple de fichiers (restreindre l'extension ? La taille? le nombre ?)
<br />	-> gestion de l'upload ? => durée de connectioni, timeout free ou quoi.
<br />	-> compression des images en taille réelle et en miniature
<br />
<br />Fonctionnalités Droite:
<br />lister les albums (lien dessus)
<br />les trier
<br />icone poubelle pour supprimer
</div>
-->


<!--
	Trier par :
	<span class="custom-dropdown">
		<select class="custom-dropdown__select">
			<option>A -&gt; Z (croissant)</option>
			<option>Z -&gt; A (décroissant)</option>
			<option>Date (croissant)</option>
			<option>Date (décroissant)</option>
			<option>Propriétaire (croissant)</option>
			<option>Propriétaire (décroissant)</option>
		</select>
	</span>
	-->