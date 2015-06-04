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
	}
}