<?php
class CVController extends Controller
{
	function defaultAction()
	{
		parent::setVariable("CVPath", "Ressources/CV.pdf");
		parent::setVariable("Rapport3APath", "Ressources/Rapport%203A.pdf");
		parent::setVariable("Rapport4APath", "Ressources/Rapport%204A.pdf");
	}
}