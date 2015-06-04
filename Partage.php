<?php  
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'PartageController.php');
	
	// Augmentation des quotas d'importation
	ini_set('upload_max_filesize', '200M');
	ini_set('post_max_size', '200M');
	ini_set('max_input_time', '300');
	
	$controlleur = new PartageController('Partage', 'Partage de fichiers');

	// Set $action en fonction de _GET
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
	if( !isset($action))
		$action = 'defaultAction';
	
	//**********************  TODO  ***********
	//*****************************************
	// Import de fichier : Boite recherche fichiers de tout type, multific. L
	
	
	// Switch($action)
	if($action == 'setDir')
	{
		$path = filter_input(INPUT_GET, 'path', FILTER_SANITIZE_SPECIAL_CHARS);
		if(!isset($path))	// erreur
			$controlleur->defaultAction();
		else
		{
			substr($path, 2);
			$controlleur->setTableauFichiers('./Ressources/partage_fichiers'.$path);
		}
	}
	else if($action == 'mkdir')
	{
		$path = filter_input(INPUT_GET, 'path', FILTER_SANITIZE_SPECIAL_CHARS);
		if(!isset($path))	// erreur
			$controlleur->defaultAction();
		else
			$controlleur->creerRep($path);
	}
	else if($action == 'up')
	{
		$path = filter_input(INPUT_GET, 'path', FILTER_SANITIZE_SPECIAL_CHARS);
		if(!isset($path))	// erreur
			$controlleur->defaultAction();
		else
			$controlleur->uploadFichiers('./Ressources/partage_fichiers'.$path);
	}
	else if($action == 'supprFile')
	{
		$path = filter_input(INPUT_GET, 'path', FILTER_SANITIZE_SPECIAL_CHARS);
		if(!isset($path))	// erreur
			$controlleur->defaultAction();
		else
			$controlleur->supprFile($path);
	}
	else if($action == 'supprAll')
	{
		$path = filter_input(INPUT_GET, 'path', FILTER_SANITIZE_SPECIAL_CHARS);
		if(!isset($path))	// erreur
			$controlleur->defaultAction();
		else
			$controlleur->supprAllFolder('./Ressources/partage_fichiers'.$path);
	}
	else
		$controlleur->defaultAction();
	
	$controlleur->render();
