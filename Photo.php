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
		else exit;
	}
	else if($action === "AjaxSupprPhoto") {
		if(isset($_GET['idPhoto'])) {
			$controlleur->AjaxSupprPhoto($_GET['idPhoto']);
			exit;
		}
		else exit;
	}
	else if($action === "AjaxSupprAlbm") {
		if(isset($_GET['idAlbum'])) {
			$controlleur->AjaxSupprAlbum($_GET['idAlbum']);
			exit;
		}
		else exit;
	}
	else if($action === "AjaxCreerAlb") {
		$controlleur->AjaxCreerAlbum();
		exit;
	}
	else
		$controlleur->defaultAction();
	
	$controlleur->render();
