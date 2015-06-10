<?php

class Controller {
	var $arrayVariables = array();	// Tableau des variables partagées entre le contrôleur et la vue
	var $controllerPath;			// chemin du fichier controlleur
	var $viewPath;					// chemin du fichier vue
	var $pageName;					// Nom de l'onglet
	var $DB;						// Base de données
	
	function Controller($controllerName, $pageName) {
		$this->controllerPath = 'Controller/'.$controllerName.'Controller.php';
		$this->viewPath = 'View/view'.$controllerName.'.php';
		$this->pageName = $pageName;
		// Connection à la Base de données
		try {
			if($GLOBALS['URL'] == 'http://localhost/guillaumewebsite/')
				$this->DB = new PDO('mysql:host=localhost;dbname=localehostinger;charset=utf8', 'root', '');
			else
				$this->DB = new PDO('mysql:host=mysql.hostinger.fr;dbname=u636759449_main;charset=utf8', 'u636759449_gui', 'owk2zeNCRI4r');
		} catch(Exception $e) {
			echo 'Erreur : '.$e->getMessage();
		}
		var_dump($DB);
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
