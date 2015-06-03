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
					$fic['type'] = filetype($dirPath.'/'.$name);
					$fic['taille'] = (string) number_format( filesize($dirPath.'/'.$name) / 1048576 , 3, '.', ' ');
					$fic['date'] = date('d M Y H:i', filemtime($dirPath.'/'.$name));
					$fic['lien'] = $dirPath.$name;
					
					if(is_dir($dirPath.'/'.$name))
						$arrayDir[] = $fic;
					else
						$arrayFic[] = $fic;
				}
				$ii++;
			}
			closedir($dossier);
		}
		else
		{
			parent::setVariable('erreur', 'Impossible d\'accéder au répertoire : '.$dirPath);
		}
		$totalSize = $this->taille_dossier($GLOBALS['ROOT'].'Ressources/partage_fichiers');
		$totalSize /= 1048576; 		// on divise par 1024*1024 pour avoir des Mo
		$totalSize = (string) number_format($totalSize, 1, '.', ' ');
		
		parent::setVariable('arrayDir', $arrayDir);
		parent::setVariable('arrayFic', $arrayFic);
		parent::setVariable('returnPath', substr(dirname($dirPath).'/', 29));
		parent::setVariable('currentPath', substr($dirPath, 29));
		parent::setVariable('totalSize', $totalSize);
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
			$this->setTableauFichiers('./Ressources/partage_fichiers'.$path);
			parent::setVariable('succes', 'Répertoire créé avec succès :)');
		}		
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