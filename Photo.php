<?php
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'PhotoController.php');

	$controlleur = new PhotoController('Photo', 'Mes Photos');

	if(isset($_GET['action']))
		$action = $_GET['action'];
	else
		$action = "defaultAction";
	
	if($action === "AjaxGetURLPhotos") {
		if(isset($_GET['idAlbum'])) {
			$controlleur->AjaxGetPhotos($_GET['idAlbum']);
			exit;
		}
		else
			exit;
	}
	else
		$controlleur->defaultAction();
	
	$controlleur->render();
