<?php
class PartageController extends Controller
{
	function defaultAction()
	{
		$this->setTableauFichiers('./Ressources/partage_fichiers/');
	}
	
	function setTableauFichiers($dirPath)
	{
		$arrayDir = array();
		$arrayFic = array();
		if($dossier = opendir($dirPath))
		{
			$ii = 0;
			while(false !== ($name = readdir($dossier))) 
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
			parent::setVariable('erreur', 'Accès interdit au répertoire : '.$dirPath);
			$this->defaultAction();
		}
		
		$totalSize = 666666;//disk_total_space($_SERVER['DOCUMENT_ROOT'].'Guillaume/Ressources/partage_fichiers/') / 1048576;
		$totalSize = (string) number_format($totalSize, 1, '.', ' ');
		
		parent::setVariable('arrayDir', $arrayDir);
		parent::setVariable('arrayFic', $arrayFic);
		parent::setVariable('returnPath', substr(dirname($dirPath).'/', 29));
		parent::setVariable('currentPath', substr($dirPath, 29));
		parent::setVariable('totalSize', $totalSize);
	}
	
	function creerRep($path)
	{
		if($path == '' || strpos($path,'..'))	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Impossible de créer un répertoire à cet endroit :/');
			return;
		}
		if(file_exists('./Ressources/partage_fichiers'.$path))
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Le dossier existe déjà !!!!!!!!!');
		}
		else if(!mkdir('./Ressources/partage_fichiers'.$path))	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Echec de création du répertoire :/');
		}
		else		// Youpiiiiiii
		{
			$this->setTableauFichiers('./Ressources/partage_fichiers'.$path);
			parent::setVariable('succes', 'Répertoire créé avec succès :)');
		}		
	}
	
	function supprFile($path)
	{
		if($path == '' || substr( $path, 0, 29 ) != './Ressources/partage_fichiers')	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Impossible de supprimer le fichier à cet endroit :<br />'.$path.' :/');
			return;
		}
		if(!file_exists($path))
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Le fichier "'.$path.'" n\'existe pas !!!!!!!!!');
		}
		else if(!unlink($path))	// Erreur
		{
			$this->defaultAction();
			parent::setVariable('erreur', 'Echec de la suppression du fichier :/');
		}
		else		// Youpiiiiiii
		{
			$currentPath = strripos($path, '/', -1);
			$currentPath = substr($path, 0, $currentPath).'/';
			$this->setTableauFichiers($currentPath);
			parent::setVariable('succes', 'Fichier supprimé avec succès :)');
		}		
		
	}
}