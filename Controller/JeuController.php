<?php
class JeuController extends Controller
{
	function defaultAction()
	{
		parent::setVariable('manuelChess', 'Ressources/Chess_mode_emploi.pdf');
		parent::setVariable('SetupChessWin', 'Ressources/Setup_Chess.zip');
		parent::setVariable('ChessLinux', 'Ressources/Chess_Sources.zip');

		parent::setVariable('SetupOthelloWin', 'Ressources/Setup_Othello.zip');
		parent::setVariable('SrcOthello', 'Ressources/Othello_Sources.zip');

		parent::setVariable('Setup2048', 'Ressources/Lancer_2048.zip');
		parent::setVariable('Src2048', 'Ressources/2048Src.zip');
	}
}