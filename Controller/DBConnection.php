<?php

try {
	$DB = new PDO('mysql:host=mysql.hostinger.fr;dbname=u636759449_main;charset=utf8', 'u636759449_gui', 'owk2zeNCRI4r');
} catch(Exception $e) {
	die('Erreur : ' . $e->getMessage());
}
