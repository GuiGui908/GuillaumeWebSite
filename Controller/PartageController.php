<?php
class PartageController extends Controller
{
	function defaultAction()
	{
		$this->setTableauFichiers('./Ressources/partage_fichiers/');
	}
	
	// FONCTION PRINCIPALE, SET LES VARIABLES PR L'AFFICHAGE DU DOSSIER DIRPATH
	function setTableauFichiers($dirPath)
	{
		$arrayDir = array();
		$arrayFic = array();
		if(strpos($dirPath, '/..')===false && $dossier = @opendir($dirPath))
		{
			$ii = 0;
			while(false !== ($name = @readdir($dossier))) 
			{
				if($name != '.' && $name != '..')
				{
					$fic['id'] = 'fic'.$ii;
					$fic['nom'] = $name;
					$fic['type'] = $this->typeFichier($name);
					$fic['taille'] = (string) number_format( filesize($dirPath.'/'.$name) / 1048576 , 3, '.', ' ');
					$fic['date'] = date('d M Y H:i', filemtime($dirPath.$name));
					$fic['lien'] = $dirPath.$name;
					
					if(is_dir($dirPath.'/'.$name))
						$arrayDir[] = $fic;
					else
						$arrayFic[] = $fic;
				}
				$ii++;
			}
			usort($arrayDir, array('PartageController', 'my_sort_arrayFic'));	// Tri
			usort($arrayFic, array('PartageController', 'my_sort_arrayFic'));	// Tri

			closedir($dossier);
		}
		else
		{
			parent::setVariable('erreur', 'Impossible d\'accéder au répertoire : '.$dirPath);
		}
		$totalSize = $this->taille_dossier($GLOBALS['ROOT'].'/Ressources/partage_fichiers');
		$totalSize /= 1048576; 		// on divise par 1024*1024 pour avoir des Mo
		$totalSize = (string) number_format($totalSize, 1, '.', ' ');
		
		parent::setVariable('arrayDir', $arrayDir);
		parent::setVariable('arrayFic', $arrayFic);
		parent::setVariable('returnPath', substr(dirname($dirPath).'/', 29));
		parent::setVariable('currentPath', substr($dirPath, 29));
		parent::setVariable('totalSize', $totalSize);
	}
	
	// FONCTION DE COMPARAISON PERSONNELLE POUR TRIER LES FICHIERS ET LES DOSSIERS PAR ORDRE ALPHABETIQUE
	static function my_sort_arrayFic($a, $b)
	{
		return strcasecmp($a['nom'], $b['nom']);
	}
	
	function typeFichier($name)
	{
		$type = 'fichier ';
		$indice = strrpos($name, '.');
		if($indice) {
			$indice = substr($name, $indice+1);
			if($indice=='bmp' || $indice=='gif' || $indice=='ico' || $indice=='jpg' || $indice=='jpeg' || $indice=='png') $type = 'image';
			else if($indice=='mp3' || $indice=='wav' || $indice=='wma' || $indice=='flac') $type = 'musique';
			else if($indice=='avi' || $indice=='flv' || $indice=='mpg' || $indice=='mpeg') $type = 'vidéo';
			else if($indice=='rar' || $indice=='tar' || $indice=='gz' || $indice=='zip' || $indice=='7z') $type = 'archive';
			else if($indice=='doc' || $indice=='docx' || $indice=='odt' || $indice=='txt') $type = 'texte';
			else if($indice=='xls' || $indice=='xlsx' || $indice=='ods') $type = 'calc';
			else if($indice=='java' || $indice=='h' || $indice=='cpp' || $indice=='c' || $indice=='jar') $type = 'programmation';
			else $type .= $indice;
		}
		return $type;
	}

	// CALCULE RECURSIVEMENT LA TAILLE DU DOSSIER REP
	function taille_dossier($rep) {
		$racine = @opendir($rep);
		$taille=0;
		while($dossier = @readdir($racine)) {
			if(!in_array($dossier, array('..', '.'))) {
				if(is_dir($rep.'/'.$dossier)) {
					$taille += $this->taille_dossier($rep.'/'.$dossier);
				} else {
					$taille += filesize($rep.'/'.$dossier);
				}
			}
		}
		@closedir($racine);
		return $taille;
	}
	
	// CREE UN REPERTOIRE DE CHEMIN PATH (non récursif)
	function creerRep($path)
	{
		if($path == '' || strpos($path, '/..')!==false)	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Impossible de créer un répertoire à cet endroit :/');
			return;
		}
		if(file_exists('./Ressources/partage_fichiers'.$path))
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Le dossier existe déjà :(');
		}
		else if(@mkdir('./Ressources/partage_fichiers'.$path) === false)	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Echec de création du répertoire :/ Permission denied ?');
		}
		else		// Youpiiiiiii
		{
			$this->setTableauFichiers('./Ressources/partage_fichiers'.$path.'/');
			parent::setVariable('succes', 'Répertoire créé avec succès :)');
		}		
	}
	
	
	// VERIFIE PUIS STOCKE LES FICHIERS PASSES PAR LA METHODE POST
	function uploadFichiers($path)
	{
		$uploadedFiles = $this->diverse_array($_FILES["upInput"]);	
		
		// Vérifie que chaque fichier est transféré sans erreur
		$flagError = false;
		$msgError = '';
		foreach($uploadedFiles as $file) {
			if($file['error'] != UPLOAD_ERR_OK) {		// Erreur
				$flagError = true;
				$msgError .= 'Le transfert de "'.$file['name'].'" a échoué ('.$err.').<br />';
			}
		}
		
		if(!$flagError && is_dir($path)) 		// if pas d'erreurs
		{
			$succesMsg = '';
			$cpt = 0;
			$virgule = '';
			foreach($uploadedFiles as $file)
			{
				$i = 2;
				$fileName = $file['name'];
				while(file_exists($path.$fileName)) { // Préviens les doublons
					$indiceLastPt = strrpos($file['name'], '.');
					$fileName = substr_replace($file['name'], " ($i)", $indiceLastPt, 0);
					$i++;
				}
				$file['name'] = $fileName;
				move_uploaded_file($file['tmp_name'], $path.$file['name']);	// Déplace le fichier
				$succesMsg .= $virgule.$file['name'];		// Recup le nom pr le message "sucess"
				$virgule = ' ; ';
				$cpt++;
			}
			parent::setVariable('succes', $cpt.' fichiers importés avec succès : '.$succesMsg);
		}
		else {
			parent::setVariable('erreur', 'Erreur pendant le transfert ou avec le path...<br />'.$msgError);
		}			
		$this->setTableauFichiers($path);
	}
	
	// Permet de mettre le tableau en forme : tab[fileX][caracX] au lieu de tab[caracX][fileX]
	// http://php.net/manual/fr/reserved.variables.files.php#109958
	function diverse_array($vector) {
		$result = array();
		foreach($vector as $key1 => $value1)
			foreach($value1 as $key2 => $value2)
				$result[$key2][$key1] = $value2;
		return $result;
	} 
	
	
	// SUPPRIME LE FICHIER PATH
	function supprFile($path)
	{
		if($path == '' || substr( $path, 0, 29 ) != './Ressources/partage_fichiers' || strpos($path, '/..')!==false)	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Impossible de supprimer le fichier à cet endroit :<br />'.$path.' :/');
			return;
		}
		if(!file_exists($path))
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Le fichier "'.$path.'" n\'existe pas !!!!!!!!!');
			return;
		}
		else if(@unlink($path) === false)	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Echec de la suppression du fichier :/');
			return;
		}
		else		// Youpiiiiiii
		{
			$currentPath = strripos($path, '/', -1);
			$currentPath = substr($path, 0, $currentPath).'/';
			$this->setTableauFichiers($currentPath);
			parent::setVariable('succes', 'Fichier supprimé avec succès :)');
		}		
		
	}
	
	// SUPPRIME TOUT LE REPERTOIRE PATH
	function supprAllFolder($path)
	{
		if($path == '' || substr( $path, 0, 29 ) != './Ressources/partage_fichiers' || strpos($path, '/..')!==false)	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Impossible de supprimer les fichiers à cet endroit :<br />'.$path.' :/');
			return;
		}
		if(!is_dir($path))
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Le dossier "'.$path.'" n\'existe pas ou n\'est pas un dossier !!!!!!!!!');
			return;
		}
		else
		{
			if(strcmp($path, './Ressources/partage_fichiers/') === 0) {		// Si on efface tout ce qu'il y a a la racine, on ne veut PAS afficher le dossier parent, NI suppr le dossier racine :p
				$files = array_diff(scandir($path), array('.','..'));
				foreach ($files as $file) {
					(is_dir("$path/$file")) ? $this->delTree("$path/$file") : unlink("$path/$file");
				}
				//mkdir('./Ressources/partage_fichiers', );	// On recrée le répertoire de partage de fichiers qui vient d'être supprimé
				$this->defaultAction();
			} else {
				$this->delTree($path);
				$this->setTableauFichiers(dirname($path).'/');
			}
			parent::setVariable('succes', 'Répertoire effacé avec succès :)');
		}
	}
	
	// Code de la suppression récursive emprunté à nbari
	// http://php.net/manual/fr/function.rmdir.php#110489
	function delTree($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
	
}