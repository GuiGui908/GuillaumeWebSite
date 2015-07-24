<div class="left noselect"> 
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
	<span id="errorMsg" class="err"></span>
</div>

<div class="right"> 
	<h3 class="inline">
		Album "<span id="nomAlb" onclick="setNomAlbum()">---</span>"<br />(<span id="nbTof">0</span> photos)
		<img class="loading" id="loadingSupprAlb" src="Ressources/images/waitWhite.gif" alt="Patientez....." />
		<img class="loading" id="succesSupprAlb" src="Ressources/images/succesWhite.jpg" alt="Succès :)" />
		<img class="loading" id="errSupprAlb" src="Ressources/images/errWhite.jpg" alt="Echec :(" />
	</h3>
	<span class="hidden" id="idAlbum"></span>  <!-- champ caché inutile qui sert juste à avoir l'id
													de l'album courrant (car on le change avec du JS/Ajax seulement)  -->
	<br />
	<b>Propriétaire : </b><br />
	<span id="proprio" onclick="setProprio()">--</span><br />
	<b>Description : </b><br />
	<span id="description" onclick="setDesc()">--</span><br /><br />
	<a href="javascript: ajouterPhoto();" class="btn">Ajouter des photos</a><br /><br />
	<a href="javascript: supprAlbum();" class="btn">Supprimer l'album</a><br /><br />
	<a href="javascript: return false;" class="btn">Télécharger l'album</a><br /><br />

	<h3>Tous les albums <a href="#" onmouseover="msg.reload('tip', 'Grouper par propriétaire', null);" onmouseout="msg.close();"><img src="Ressources/images/arbo.jpg" alt="GroupByOwner"/></a></h3>
	<a href="javascript: creerAlbum();" class="btn">Créer un album...</a><br /><br />
	<div id="listeAlbum">
		<!-- Contenu généré ici -->
	</div>
</div>


<script>
var arrayAlbums;
$(document).ready(function() {
	 arrayAlbums = JSON.parse(' <?php echo $this->getVariable('arrAlb'); ?> ');
	 setAlbumListe(arrayAlbums);
});

function setAlbumListe(arrayAlbums) {
	$("#listeAlbum").html("");		// Vide la liste
	for(album in arrayAlbums) {
		 $("#listeAlbum").append("<a href=\"javascript: displayAlbum('"+ arrayAlbums[album]["id"] +"');\">"+ arrayAlbums[album]["nom"] +
									"<img class=\"loading\" id=\"loading"+ arrayAlbums[album]["id"] +"\" src=\"Ressources/images/waitWhite.gif\" alt=\"Patientez.....\" /></a>");
	}
}

var isEdditingNomAlbum = false;
var isEdditingProprio = false;
var isEdditingDescription = false;

// Fonctions appellées pour les listenners de modif des noms
function setNomAlbum() {		// Entre dans le mode d'édition du nom de l'album
	var ancienNom = $("#nomAlb").text().toString();
	if(!isEdditingNomAlbum && ancienNom != "---") {
		isEdditingNomAlbum = true;
		var nbChar = ancienNom.length;
		$("#nomAlb").html("<input type=\"text\" class=\"inline\" id=\"setNomAlbum\" value=\"" + ancienNom + "\" size=\""+ nbChar +"\" />");
		document.getElementById("setNomAlbum").focus();
		document.getElementById("setNomAlbum").addEventListener("keypress", function(e) {
			if(e.keyCode==27) {
				// Quitte le mode d'édition
				$("#nomAlb").text(ancienNom);
				isEdditingNomAlbum = false;
			}
			if(e.keyCode==13) {
				var nouveauNom = document.getElementById("setNomAlbum").value;
				// Appel AJAX pour modifier le nom de l'album
				$.ajax({
					url : 'Photo.php',
					type : 'GET',
					data : 'action=AjaxChangeNomAlb&idAlb=' + $("#idAlbum").text() + '&nom=' + nouveauNom,
					dataType : 'text',
					success : function(resultat, statut) {
						$("#nomAlb").text(nouveauNom);
						$("#msgTop").text(nouveauNom);
						arrayAlbums = JSON.parse(resultat);
						setAlbumListe(arrayAlbums);
						isEdditingNomAlbum = false;
					},
					error : function(resultat, statut, erreur) {
						$("#nomAlb").text(ancienNom);
						isEdditingNomAlbum = false;
					}
				});
			}
		});
	}
}

function setProprio() {		// Entre dans le mode d'édition du nom du propriétaire
	var ancienNom = $("#proprio").text().toString();
	if(!isEdditingProprio && ancienNom != "--") {
		isEdditingProprio = true;
		$("#proprio").html("<input type=\"text\" class=\"inline\" id=\"setProprio\" value=\"" + ancienNom + "\" size=\"26\" />");
		document.getElementById("setProprio").focus();
		document.getElementById("setProprio").addEventListener("keypress", function(e) {
			if(e.keyCode==27) {
				// Quitte le mode d'édition
				$("#proprio").text(ancienNom);
				isEdditingProprio = false;
			}
			if(e.keyCode==13) {
				var nouveauNom = document.getElementById("setProprio").value;
				// Appel AJAX pour modifier le nom du propriétaire
				$.ajax({
					url : 'Photo.php',
					type : 'GET',
					data : 'action=AjaxChangeProprio&idAlb=' + $("#idAlbum").text() + '&nom=' + nouveauNom,
					dataType : 'text',
					success : function(resultat, statut) {
						$("#proprio").text(nouveauNom);
						arrayAlbums = JSON.parse(resultat);
						setAlbumListe(arrayAlbums);
						isEdditingProprio = false;
					},
					error : function(resultat, statut, erreur) {
						$("#proprio").text(ancienNom);
						isEdditingProprio = false;
					}
				});
			}
		});
	}
}

function setDesc() {		// Entre dans le mode d'édition de la dscription de l'album
	var ancienneDesc = $("#description").text().toString();
	if(!isEdditingDescription && ancienneDesc != "--") {
		isEdditingDescription = true;
		$("#description").html("<input class=\"inline\" id=\"setDesc\" value=\""+ ancienneDesc +"\" size=\"26\" />");
		document.getElementById("setDesc").focus();
		document.getElementById("setDesc").addEventListener("keypress", function(e) {
			if(e.keyCode==27) {
				// Quitte le mode d'édition
				$("#description").text(ancienneDesc);
				isEdditingDescription = false;
			}
			if(e.keyCode==13) {
				var nouvelleDesc = document.getElementById("setDesc").value;
				// Appel AJAX pour modifier la description
				$.ajax({
					url : 'Photo.php',
					type : 'GET',
					data : 'action=AjaxChangeDescription&idAlb=' + $("#idAlbum").text() + '&desc=' + nouvelleDesc,
					dataType : 'text',
					success : function(resultat, statut) {
						$("#description").text(nouvelleDesc);
						arrayAlbums = JSON.parse(resultat);
						setAlbumListe(arrayAlbums);
						isEdditingDescription = false;
					},
					error : function(resultat, statut, erreur) {
						$("#description").text(ancienneDesc);
						isEdditingDescription = false;
					}
				});
			}
		});
	}
}


function displayAlbum(idAlbum) {
	$("#imgBig").attr("src", "Ressources/images/rien.jpg");
	$("#loading"+idAlbum).css("display", "inline");
	for(album in arrayAlbums) {
		if(arrayAlbums[album]["id"] === idAlbum) {
			$("#msgTop").text(arrayAlbums[album]["nom"]);
			$("#idAlbum").text(arrayAlbums[album]["id"]);
			$("#nomAlb").text(arrayAlbums[album]["nom"]);
			$("#proprio").text(arrayAlbums[album]["proprio"]);
			$("#description").text(arrayAlbums[album]["desc"]);
			break;
		}
	}
	
	// Requête ajax pour charger les images
	$.ajax({
		url : 'Photo.php',
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
				elementPhoto += "<a href=\"javascript: supprPhoto("+ idTof +");\" class=\"suppr\"><img src=\"Ressources/images/suppr.png\" alt=\"Suppr\"></a></div>";
				
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
	var options = {
		"title": "Etes-vous vraiment sûr de supprimer la photo ?",
		"btnOk" : "Supprimer",
		"btnNop" : "Annuler",
		"modal": "True",
		"action" : "SupprPhoto",
		"data" : idPhoto
	};
	msg.open( "Voulez-vous vraiment supprimer la photo ?" , options);
}

function creerAlbum() {
	var options = {
		"title": "Créer un nouvel album",
		"btnOk" : "Créer",
		"btnNop" : "Annuler",
		"modal": "True",
		"action" : "creerAlbum",
	};	
	msg.open( "<form id=\"FormNewAlb\" action=\"Photo.php?action=creerAlbm\" method=\"post\" enctype=\"multipart/form-data\">"+
				"<span class=\"formAlertLbL\"><span class=\"RED\">* </span>Nom :</span>"+
					"<input type=\"text\" name=\"nameAlb\" id=\"nameAlb\" placeholder=\"Nom de l'album\" />  <br />"+
				"<span class=\"formAlertLbL\">&nbsp;&nbsp;Propriétaire :</span>"+
					"<input type=\"text\" name=\"proprioAlb\" id=\"proprioAlb\" placeholder=\"Nom du propriétaire de l'album\" />  <br />"+
				"<span class=\"formAlertLbL\"><span class=\"RED\">* </span>Photos :</span>"+
					"<input type=\"file\" name=\"listPhotos[]\" id=\"listPhotos\" multiple />  <br />"+
				"<span class=\"formAlertLbL\"><span class=\"RED\">* </span>Description :</span>"+
					"<textarea name=\"descAlb\" id=\"descAlb\" cols=\"30\" placeholder=\"Un petit mot...\"></textarea>  <br />"+
				"<span id=\"popupErrMsg\"></span>"+
			  "</form><br />" , options);
}

function ajouterPhoto() {
	alert("TODO");
}

function supprAlbum() {
	var idAlbum = $("#idAlbum").text();
	if(idAlbum === "") return;

	var options = {
		"title": "Etes-vous vraiment sûr de supprimer tout l'album ?",
		"btnOk" : "Supprimer",
		"btnNop" : "Annuler",
		"modal": "True",
		"action" : "supprAlbm",
		"data" : idAlbum
	};
	msg.open( "Supprimer tout l'album entier ? ATTENTION !! c'est irréversible" , options);
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