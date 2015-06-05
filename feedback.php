<?php  
/*	Crée la page, doctype, header, footer. Appelle le controlleur. Insère la vue */
	require_once('VarServer.php');

	// Importation des contôlleurs :
	require_once($GLOBALS['CtrlPath'].'Controller.php');
	require_once($GLOBALS['CtrlPath'].'FeedBackController.php');

	if(isset($_GET['action']))
		$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
	else
		$action = 'defaultAction';
	
	$controlleur = new FeedBackController('FeedBack', 'Donnnez votre avis !');
	if($action === 'envFeedBack') {
		$controlleur->stockerFeedback();
		$controlleur->envoyerNotif();
	}
	else if($action === 'checkpwd') {
		$mdp = filter_input(INPUT_GET, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS);
		if(!isset($mdp))	// erreur
			$controlleur->checkMotDePasse('');
		else
			$controlleur->checkMotDePasse($mdp);
	}
	else
		$controlleur->defaultAction();

	$controlleur->render();
