<?php
class CVController extends Controller
{
	function defaultAction()
	{
		parent::setVariable("CVcourt", "Ressources/CV court.pdf");
		parent::setVariable("CVlong", "Ressources/CV long.pdf");
		parent::setVariable("Rapport3APath", "Ressources/Rapport%203A.pdf");
		parent::setVariable("Rapport4APath", "Ressources/Rapport%204A.pdf");
	}
}