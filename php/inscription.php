<?php
	include "blibli_cuiteur.php";
	include "blibli_generale.php";
	include "config.php";

	session_start();
	ob_start() ;
	
	
	//_______________________________________________________________________________________________________
	/*
	*Fonction permettant de vérifier si les données retournées par le formulaires sont bonnes pour une inscription
	*En cas d'échec de connection elle affichera l'erreur
	*
	*@param	object_mysqli_connect	$BD		il s'agit de l'objet représentant la connection au serveur MySQL
	*
	*@return  array	retourne un tableau avec les erreurs rencontrés, si les données sont valides le tableau est vide
	*/
	function pbl_new_user($BD)
	{
		$erreurs = array();
		$req="SELECT usPseudo
		FROM users";
		
		$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		//Vérification des données renseignées
		if (strlen($_POST['txtPseudo'])<4 || strlen($_POST['txtPseudo'])>20)
		{
			$erreurs[]="Le pseudo doit avoir de 4 &agrave; 30 caract&egrave;res<br>";
		}
		
		while($tab=mysqli_fetch_assoc($res))
		{
			if (($tab['usPseudo'])==(mysqli_real_escape_string($BD, $_POST['txtPseudo'])))
			{
				$erreurs[]="Le pseudo doit &ecirc;tre chang&eacute;<br>";
			}
		}
		
		if (strlen($_POST['txtPasse'])==0)
		{
			$erreurs[]="Le mot de passe est obligatoire<br>";
		}
		if (($_POST['txtVerif'])!=($_POST['txtPasse']))
		{
			$erreurs[]="Le mot de passe est diff&eacute;rent dans les 2 zones<br>";
		}
		if (strlen($_POST['txtNom'])==0)
		{
			$erreurs[]="Le nom est obligatoire<br>";
		}
		if (strlen($_POST['txtMail'])==0)
		{
			$erreurs[]="Le mail est obligatoire<br>";
		}
		if (strpos(($_POST['txtMail']), "@")==FALSE || strpos(($_POST['txtMail']), ".")==FALSE)
		{
			$erreurs[]="L'adresse mail n'est pas valide<br>";
		}
		//Inscription dans la base de données
		if (count($erreurs)==0)
		{
			$localtime=localtime(time(), true);
			$req="INSERT INTO users (usPseudo, usPasse, usNom, usMail, usDateInscription)
				VALUES
				('".(mysqli_real_escape_string($BD, ($_POST['txtPseudo']))).
				"','".(mysqli_real_escape_string($BD, ($_POST['txtPasse']))).
				"','".(mysqli_real_escape_string($BD, ($_POST['txtNom']))).
				"','".(mysqli_real_escape_string($BD, ($_POST['txtMail']))).
				"','".($localtime['tm_year']+1900).(str_pad((($localtime['tm_mon'])+1), 2, '0', STR_PAD_LEFT)).(str_pad(($localtime['tm_mday']), 2, '0', STR_PAD_LEFT)).
				"');";
				
				
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);			
			
			$req="SELECT usID
				FROM users
				WHERE usPseudo='".(mysqli_real_escape_string($BD, ($_POST['txtPseudo'])))."'";
				
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			
			$tab=mysqli_fetch_assoc($res);
			$_SESSION['usID'] = ($tab['usID']);
			$_SESSION['usPseudo']   = ($_POST['txtPseudo']);
			
			mysqli_free_result($res);
			
		}
		return $erreurs;
	}
	
	
	$BD=pb_bd_connection(BD_URL, BD_USER, BD_PASS, BD_NAME);
	
	$erreurs = array();
	
	//Si pas d'erreurs lors de l'inscription, on redirige vers compte.php
	if (($_POST['btnValider'])=="Je m'inscris")
	{
		$erreurs=pbl_new_user($BD);
		if (count($erreurs)==0)
		{
			header ('location: compte.php');
			exit();
		}
	}
	else
	{
		$_POST['txtPseudo']="";
		$_POST['txtPasse']="";
		$_POST['txtVerif']="";
		$_POST['txtNom']="";
		$_POST['txtMail']="";
	}
	
	pb_html_debut("Cuiteur | Inscription", "ISO-9661", "../styles/index.css");
		
	echo '<body>',
		'<div id="bcPage">',
		'<header>';
			pb_aff_cuiteur_Header_Titre("Inscription");
			echo '</header>',
				'<aside>',
				'</aside>',
				'<p id="indications">Pour vous inscrire il suffit de :</p><br>',
				'<table style="border-collapse:collapse; border-spacing:5 ;">',
				'<form method="POST" action="../php/inscription.php">'.
					pb_form_Ligne("Choisir un pseudo", pb_form_input("text", "txtPseudo", "", 20, 15, ""), "").
					pb_form_Ligne("Choisir un mot de passe", pb_form_input("password", "txtPasse", "", 20, 15, ""), "").
					pb_form_Ligne("R&eacutep&eacuteter le mot de passe", pb_form_input("password", "txtVerif", "", 20, 15, ""), "").
					pb_form_Ligne("Indiquer votre nom", pb_form_input("text", "txtNom", "", 40, 30, ""), "").
					pb_form_Ligne("Donner une adresse mail", pb_form_input("text", "txtMail", "", 40, 30, ""), "").
					pb_form_Ligne("", pb_form_input("submit", "btnValider", "Je m'inscris", "", "", "subInscr"), "alignDroit").
				'</form>',
				'</table>';
			
			if (count($erreurs)!=0)
			{
				echo '<h3>Les erreurs suivantes ont &eacute;t&eacute; d&eacute;tect&eacute;s</h3>';
				foreach($erreurs as $value)
				{
					echo $value;
				}
			}
				
			pb_cuiteur_Footer();
			
		echo '</div>',				
		'</body>',
		'</html>';
	
	
	mysqli_close($BD);
	ob_end_flush();
?>