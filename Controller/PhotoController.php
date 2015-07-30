<?php
class PhotoController extends Controller
{
	function defaultAction()
	{
		$nomAlb = '-- --';
		$proprioAlb = '-- --';
		$nbTofAlb = 0;
		$descAlb = '-- --';
		$msgTop = 'Sélectionnez un album dans la liste à droite';
		$arrAlb = $this->getTableauAlbums();
	
		parent::setVariable('msgTop', $msgTop);
		parent::setVariable('nomAlb', $nomAlb);
		parent::setVariable('proprioAlb', $proprioAlb);
		parent::setVariable('nbTofAlb', $nbTofAlb);
		parent::setVariable('descAlb', $descAlb);
		parent::setVariable('arrAlb', json_encode($arrAlb));
	}
	
	function getTableauAlbums() {
		$album = array();
		$arrAlb = array();
		$reponse = $this->DB->query('SELECT * FROM album');
		while ($donnees = $reponse->fetch()) {
			$album['id'] = $donnees['id'];
			$album['nom'] = htmlspecialchars($donnees['nom'], ENT_QUOTES);
			$album['proprio'] = htmlspecialchars($donnees['proprietaire'], ENT_QUOTES);
			$album['desc'] = htmlspecialchars($donnees['description'], ENT_QUOTES);
			$arrAlb[] = $album;
		}
		$reponse->closeCursor();
		return $arrAlb;		// TODO PROBLEME DES IMAGES QUI SE PUPPRIMENT PAS
	}
	
	function AjaxGetPhotos($idAlbum) {
		$requete = $this->DB->prepare("SELECT * FROM photo WHERE idAlbum=:idAlb");
		$requete -> bindParam(':idAlb', $idAlbum);
		$requete->execute();

		$arrPhotos = array();
		$photos = array();
		while ($donnees = $requete->fetch()) {
			$photos['id'] = htmlspecialchars($donnees['id']);
			$photos['nom'] = htmlspecialchars($donnees['nom']);
			$photos['url'] = htmlspecialchars($donnees['chemin']);
			$arrPhotos[] = $photos;
		}
		echo json_encode($arrPhotos);
	}
	
	// Recoit UNE seule photo à stocker dans l'album idAlbum
	function AjaxAddPhoto($idAlbum) {
		$image = $_FILES['photo'];		// On considère qu'une seule photo est passée par la méthode post. Les éventuelles autres photos sont ignorées
		
		// Vérifie que le transfert s'est bien passé
		if($image['error'] != UPLOAD_ERR_OK) { 		// Erreur
			echo 'Le transfert de "'.$image['name'].'" a échoué !<br />';
		}
		else {
			// Récupère le chemin de l'album
			$requete = $this->DB->prepare("SELECT chemin FROM album WHERE id=:idAlb");
			$requete -> bindParam(':idAlb', $idAlbum);
			$requete->execute();
			
			$reponse = $requete->fetch();
			$chemin = $reponse['chemin'].$image['name'];
			
			// INSERT INTO photo
			$requete = $this->DB->prepare("INSERT INTO photo(idAlbum, nom, chemin) VALUES(:idAlbum, :nom, :chemin)");
			$requete -> bindParam(':idAlbum', $idAlbum);
			$requete -> bindParam(':nom', $image['name']);
			$requete -> bindParam(':chemin', $chemin);
			$requete->execute();
			
			if(!$requete)   // ERREUR
				echo "Erreur pendant le stockage en BD de la photo :(";
			
			// Déplace l'image sur le serveur dans le bon dossier
			$i = 2;
			$fileName = $image['name'];
			while(file_exists($reponse['chemin'].$fileName)) { // Préviens les doublons
				$indiceLastPt = strrpos($image['name'], '.');
				$fileName = substr_replace($image['name'], " ($i)", $indiceLastPt, 0);
				$i++;
			}
			$image['name'] = $fileName;
			move_uploaded_file($image['tmp_name'], $reponse['chemin'].$image['name']);	// Déplace le fichier
		}
	}
	
	
	function AjaxSupprPhoto($idPhoto) {
		// Cherche le chemin de la photo à supprimer
		$requete = $this->DB->prepare("SELECT chemin FROM photo WHERE id=:idTof");
		$requete -> bindParam(':idTof', $idPhoto);
		$requete->execute();
		$chemin = $requete->fetchColumn();
		if(file_exists($chemin)) {		// Supprime le fichier
			@unlink($chemin);
		}

		// Supprime la photo de la base de données
		$requete = $this->DB->prepare("DELETE FROM photo WHERE id=:idTof");
		$requete -> bindParam(':idTof', $idPhoto);
		$requete->execute();
				
		if(!$requete) {		// Pas sûr que ça marche tout ça --"
			header('HTTP/1.1 500 Internal Server Error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array('resultat' => 'ERROR', 'erreur' => 1337)));
		}
	}
	
	
	function AjaxSupprAlbum($idAlb) {
		// récup le chemin du répertoire de l'album PUIS le supprime 
		$requete = $this->DB->prepare("SELECT chemin FROM album WHERE id=:idAlb");
		$requete -> bindParam(':idAlb', $idAlb);
		$requete->execute();
		if(!$requete) echo "Erreur SELECT Chemin de l'Album :/";

		$chemin = $requete->fetchColumn();
		$files = array_diff(scandir($chemin), array('.','..'));
		foreach ($files as $file) {
			unlink($chemin.'/'.$file);
		}
		rmdir($chemin);
		
		// supprime l'album
		$requete = $this->DB->prepare("DELETE FROM album WHERE id=:idAlb");
		$requete -> bindParam(':idAlb', $idAlb);
		$requete->execute();
		if(!$requete) echo "Erreur DELETE album :/";
		
		// supprime toutes le sphotos de l'album
		$requete = $this->DB->prepare("DELETE FROM photo WHERE idAlbum=:idAlb");
		$requete -> bindParam(':idAlb', $idAlb);
		$requete->execute();
		if(!$requete) echo "Erreur DELETE photos :/";
	}
	
	
	function AjaxCreerAlbum($albName, $proprio, $description) {
		// Crée un répertoire qui existe pas déjà dans /Ressources/photos
		do {
			$folder = rand(0, 9999);
			$path = 'Ressources/photos/'.$folder.'/';
		} while(is_dir($path));
		if(!@mkdir($path)) {
			echo json_encode(array("<erreur>", "Erreur MKDIR pr creer dossier d'album"));
			return;
		}

		// Crée la liste de tous les albums (pr màj INTERFACE)
		$arrAlb = $this->getTableauAlbums();
			
		// Crée un album dans la BD (1 ligne)
		$requete = $this->DB->prepare("INSERT INTO album(nom, proprietaire, description, chemin, ip) VALUES(:nom, :proprio, :desc, :chemin, :ip)");
		$requete -> bindParam(':nom', $albName);
		$requete -> bindParam(':proprio', $proprio);
		$requete -> bindParam(':desc', $description);
		$requete -> bindParam(':chemin', $path);
		$requete -> bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		$requete->execute();
		if(!$requete) {
			echo json_encode(array("<erreur>", "Erreur INSERT INTO album :/"));
			return;
		}
		$idAlbum = $this->DB->lastInsertId();

		// Ajoute l'album qui vient d'être inséré en BD au tableau ppour l'INTERFACE
		$arrAlb[] = array(  'id' => $idAlbum,
							'nom' => $albName,
							'proprio' => $proprio,
							'desc' => $description  );

		echo json_encode( array("Succès !!!", $path, $idAlbum, $_GET['nameAlb'], $_GET['proprioAlb'], $_GET['descAlb'], json_encode($arrAlb)) );
	}
	
	
	function AjaxChangeNomAlb($nouveauNom, $idAlb) {
		$requete = $this->DB->prepare("UPDATE album SET nom=:nom WHERE id=:idAlbum");
		$requete -> bindParam(':nom', $nouveauNom);
		$requete -> bindParam(':idAlbum', $idAlb);
		$requete->execute();
		if(!$requete) {		// Si probleme
			echo "Erreur UPDATE nom album :/";
			return;
		}
		
		// Affiche le json de la liste des albums pour que la modif s'affiche dans la liste
		$arrAlb = $this->getTableauAlbums();
		echo json_encode($arrAlb);
	}
	
	function AjaxChangeProprio($nouveauNom, $idAlb) {
		$requete = $this->DB->prepare("UPDATE album SET proprietaire=:nom WHERE id=:idAlbum");
		$requete -> bindParam(':nom', $nouveauNom);
		$requete -> bindParam(':idAlbum', $idAlb);
		$requete->execute();
		if(!$requete) {		// Si probleme
			echo "Erreur UPDATE propriétaire album :/";
			return;
		}
		
		// Affiche le json de la liste des albums pour que la modif s'affiche dans la liste
		$arrAlb = $this->getTableauAlbums();
		echo json_encode($arrAlb);
	}
	
	function AjaxChangeDescription($nouvelleDesc, $idAlb) {
		$requete = $this->DB->prepare("UPDATE album SET description=:desc WHERE id=:idAlbum");
		$requete -> bindParam(':desc', $nouvelleDesc);
		$requete -> bindParam(':idAlbum', $idAlb);
		$requete->execute();
		if(!$requete) {		// Si probleme
			echo "Erreur UPDATE description album :/";
			return;
		}
		
		// Affiche le json de la liste des albums pour que la modif s'affiche dans la liste
		$arrAlb = $this->getTableauAlbums();
		echo json_encode($arrAlb);
	}
}


