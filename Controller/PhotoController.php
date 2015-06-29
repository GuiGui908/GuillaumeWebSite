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
		
		$arrAlb = array();
		$album = array();
		$reponse = $this->DB->query('SELECT * FROM album');
		while ($donnees = $reponse->fetch()) {
			$album['id'] = $donnees['id'];
			$album['nom'] = htmlspecialchars($donnees['nom']);
			$album['proprio'] = htmlspecialchars($donnees['proprietaire']);
			$album['desc'] = htmlspecialchars($donnees['description']);
			$arrAlb[] = $album;
		}
		$reponse->closeCursor();

		
		parent::setVariable('msgTop', $msgTop);
		parent::setVariable('nomAlb', $nomAlb);
		parent::setVariable('proprioAlb', $proprioAlb);
		parent::setVariable('nbTofAlb', $nbTofAlb);
		parent::setVariable('descAlb', $descAlb);
		parent::setVariable('arrAlb', json_encode($arrAlb));
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
	
	
	function AjaxCreerAlbum() {
		// Crée un répertoire qui existe pas déjà dans /Ressources/photos
		do {
			$folder = rand(0, 9999);
			$path = 'Ressources/photos/'.$folder.'/';
		} while(is_dir($path));
		if(!@mkdir($path)) {
			echo json_encode(array("<erreur>", "Erreur MKDIR pr creer dossier d'album"));
			return;
		}
		
		// Crée un album dans la BD (1 ligne)
		$requete = $this->DB->prepare("INSERT INTO album(nom, proprietaire, description, chemin) VALUES(:nom, :proprio, :desc, :chemin)");
		$requete -> bindParam(':nom', $_GET['nameAlb']);
		$requete -> bindParam(':proprio', $_GET['proprioAlb']);
		$requete -> bindParam(':desc', $_GET['descAlb']);
		$requete -> bindParam(':chemin', $path);
		$requete->execute();
		if(!$requete) {
			echo json_encode(array("<erreur>", "Erreur INSERT INTO album :/"));
			return;
		}
		$idAlbum = $this->DB->lastInsertId();

		echo json_encode(array("Succès !!!", $path, $idAlbum, $_GET['nameAlb'], $_GET['proprioAlb'], $_GET['descAlb']));
	}
}


