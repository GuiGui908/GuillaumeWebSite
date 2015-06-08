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
	if($action === 'AjaxEnvFeedBack') {
		$controlleur->AjaxStockerFeedback();
		$controlleur->AjaxEnvoyerNotif();
		exit;		// Appel ajax donc on renvoie pas tte la page !!! On fait pas "render()"
	}
	else if($action === 'AjaxCheckpwd') {
		$mdp = filter_input(INPUT_GET, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS);
		if(!isset($mdp))	// erreur
			$controlleur->AjaxCheckMotDePasse('');
		else
			$controlleur->AjaxCheckMotDePasse($mdp);
		exit;		// Appel ajax donc on renvoie pas tte la page !!! On fait pas "render()"
	}
	else
		$controlleur->defaultAction();

	$controlleur->render();
