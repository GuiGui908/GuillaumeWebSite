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
			$photos['url'] = "Ressources/photos/".htmlspecialchars($donnees['chemin']);
			$arrPhotos[] = $photos;
		}
		echo json_encode($arrPhotos);
	}
	
	function AjaxSupprPhoto($idPhoto) {
		// Cherche le chemin de la photo à supprimer
		$requete = $this->DB->prepare("SELECT chemin FROM photo WHERE id=:idTof");
		$requete -> bindParam(':idTof', $idPhoto);
		$requete->execute();
		$chemin = 'Ressources/photos/' . $requete->fetchColumn();
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
}


