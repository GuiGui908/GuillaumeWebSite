// TODO trouver une api JS qui affiche l'image taille réelle au survol. Le mieux est de charger les grosses images en BG<br>
<div class="left"> 
	<h1>Jeu d'échecs</h1>
	<p>Jeu d'éhecs en C avec plusieurs modes, notamment contre l’adversaire le plus calculateur aux échecs : l’ordinateur. Vous pourrez sauvegarder votre partie en cours. Programmé en C avec GTK+.
	Pour les détails sur l'utilisation du jeu, il existe un mode d'emploi en pdf en téléchargement ci-contre.</p>
	<div class="jeuxImgBox">
		<img src="Ressources/images/chess1_s.jpg" alt="ScreenShot1"/>
		<img src="Ressources/images/chess2_s.jpg" alt="ScreenShot2"/>
		<img src="Ressources/images/chess3_s.jpg" alt="ScreenShot3"/>
		<img src="Ressources/images/chess4_s.jpg" alt="ScreenShot4"/>
	</div>

	<hr />

	<h1>Jeu d'othello</h1>
	<p>Othello en C++ fonctionnel même si le développement d'améliorations n'est pas terminé. Vous pourrez sauvegarder votre partie en cours, en charger une ancienne ou même consulter vos scores sur le total des parties. Programmé en C++ avec GTKmm.</p>
	<div class="jeuxImgBox">
		<img src="Ressources/images/oth1_s.jpg" alt="ScreenShot1"/>
		<img src="Ressources/images/oth2_s.jpg" alt="ScreenShot2"/>
		<img src="Ressources/images/oth3_s.jpg" alt="ScreenShot3"/>
		<img src="Ressources/images/oth4_s.jpg" alt="ScreenShot4"/>
	</div>

	<hr />

	<h1>2048 (comming soon)</h1>
	<p>Encore en dévelopement, mais ça arrive cet été !</p>
	<div class="jeuxImgBox">
		<img src="Ressources/images/2048.png" alt="ScreenShot1" />
		</div>
</div>
<div class="right"> 
	<h3>Jeu d'échecs</h3>
	<ul>
		<li><a href="<?php echo $this->getVariable('manuelChess'); ?>">Guide rapide (pdf)</a></li>
		<li><a href="<?php echo $this->getVariable('SetupChessWin'); ?>">Setup Windows</a></li>
		<li><a href="<?php echo $this->getVariable('ChessLinux'); ?>">Code source + Executable Linux</a></li>
	</ul>
	<br />
	<br />
	<h3>Jeu d'othello</h3>
	<ul>
		<li><a href="<?php echo $this->getVariable('SetupOthelloWin'); ?>">Setup Windows</a></li>
		<li><a href="<?php echo $this->getVariable('SrcOthello'); ?>">Sources Code::Blocks</a></li>
	</ul>
	<br />
	<h3>2048</h3>
	<br />
	<br />
	<br />
</div>