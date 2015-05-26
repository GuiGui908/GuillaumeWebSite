<?php  
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'PlanController.php');
	
	$controlleur = new PartageController('Partage', 'Partage de fichiers');

	// Set $action en fonction de _GET
	if( !isset($_GET['action']))
		$action = 'defaultAction';
	else
		$action = strip_tags($_GET['action']);
	
	//**********************  TODO  ***********
	//*****************************************
	// verifier que dans la partie client on a jamais "./Ressources/partage_fichiers"
	// Import de fichier : Boite recherche fichiers de tout type, multific. Limiter taille de chaque à XX et vérif que taille dépasse pas 999Mo
	// Créer un dossier
	// Bug du "file_size_left_on_disk" sur le site en ligne
	
	
	// Switch($action)
	if($action === 'mkdir')
	{
		if(!isset($_GET['path']))	// erreur
			$controlleur->creerRep('');
		else
			$controlleur->creerRep(strip_tags($_GET['path']));
	}
	else if($action == 'setDir')
	{
		if(!isset($_GET['path']))	// erreur
			$controlleur->defaultAction();
		else
		{
			$path = strip_tags($_GET['path']);
			substr($path, 2);
			$controlleur->setTableauFichiers('./Ressources/partage_fichiers'.$path);
		}
	}
	else if($action == 'supprFile')
	{
		if(!isset($_GET['path']))	// erreur
			$controlleur->defaultAction();
		else
		{
			$path = strip_tags($_GET['path']);
			$controlleur->supprFile($path);
		}
	}
	else
		$controlleur->defaultAction();
	
	$controlleur->render();
