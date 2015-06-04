<?php  
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'PartageController.php');
	
	system('cp /opt/php-5.5/etc/php.ini /home/u636759449/public_html/php.ini');
	system('cp /opt/php.conf.d/u636759449.ini /home/u636759449/public_html/uBLABLA.ini');
	
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
