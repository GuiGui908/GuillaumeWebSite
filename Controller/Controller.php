<?php

class Controller {
	var $arrayVariables = array();	// Tableau des variables partagées entre le contrôleur et la vue
	var $controllerPath;			// chemin du fichier controlleur
	var $viewPath;					// chemin du fichier vue
	var $pageName;					// Nom de l'onglet
	
	function Controller($controllerName, $pageName) {
		$this->controllerPath = 'Controller/'.$controllerName.'Controller.php';
		$this->viewPath = 'View/view'.$controllerName.'.php';
		$this->pageName = $pageName;
	}

	function setVariable($varName, $var) {
		$this->arrayVariables[$varName] = $var;
	}

	function getVariable($varName) {
		if( !isset($this->arrayVariables[$varName]) )
			return null;
		else
			return $this->arrayVariables[$varName];
	}
	
	function render() {
		extract($this->arrayVariables);

		$pageName = $this->pageName;
require_once('View/contexte/header.php');
		require_once('View/contexte/menu.php');
		if(file_exists($this->viewPath))
			require_once($this->viewPath);
		else
			echo '<div class="left"> 
					<h1>ERREUR 404 !!!!</h1>
					<br>TROU DU CUL !!!! T\'as pas mis la view "'.$this->viewPath.'" dans le répertoire --" </div>';
		require_once('View/contexte/footer.php');
		exit;
	}
}
