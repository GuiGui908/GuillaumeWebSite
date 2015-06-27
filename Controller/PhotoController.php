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
}


