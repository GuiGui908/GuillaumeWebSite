<?php  
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'PlanController.php');
	
	$controlleur = new PlanController('Plan', 'Plan du site');
	$controlleur->defaultAction();
	$controlleur->render();
	
	
	