<?php  
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	require_once($GLOBALS['CtrlPath'].'DBConnection.php');
	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'FeedBackController.php');

	if(isset($_GET['action']))
		$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
	else
		$action = 'defaultAction';
	
	$controlleur = new FeedBackController('FeedBack', 'Donnnez votre avis !');
	if($action === 'valider') {
		$controlleur->stockerFeedback();
		$controlleur->envoyerNotif();
	}
	else
		$controlleur->defaultAction();

	$controlleur->render();
