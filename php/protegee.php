<?php

	include "blibli_cuiteur.php";
	session_start();
	ob_start() ;
	
	pb_verifie_session();
	
	
	pb_html_debut("Cuiteur | Inscription", CHARSET, "");
	
	echo '<body><h2>Utilisateur connect&eacute; :</h2>',
		'<ul><li>Identidiant : '.(htmlentities(($_SESSION['usID']), ENT_COMPAT, 'ISO-8859-1')).'</li><li>Pseudo : '.(htmlentities(($_SESSION['usPseudo']), ENT_COMPAT, 'ISO-8859-1')).'</li></ul>',
		'<a href=index.php>Deconnexion</a></body></html>';
	
	ob_end_flush();
?>