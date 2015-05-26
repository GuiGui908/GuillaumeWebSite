<?php
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'CVController.php');

	$controlleur = new CVController('CV', 'CV / Entreprises');
	$controlleur->defaultAction();
	$controlleur->render();
