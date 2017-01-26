<?php

	include "blibli_generale.php";
	include "blibli_cuiteur.php";
	include "config.php";
	
	session_start();
	ob_start() ;
	
	//_______________________________________________________________________________________________________
	/*
	*Fonction permettant de vérifier si les données retournées par le formulaire sont bonnes pour une authentification
	*En cas d'échec de connection elle retournera les erreurs rencontrés
	*
	*@param	object_mysqli_connect	$BD		il s'agit de l'objet représentant la connection au serveur MySQL
	*
	*@return  array	retourne un tableau avec les erreurs rencontrés, si les données sont valides le tableau est vide
	*/
	function pbl_authentification($BD)
	{
		$erreurs = array();
		if (strlen($_POST['txtPasse'])==0)
		{
			$erreurs[]="Le mot de passe est obligatoire<br>";
		}
		if (strlen($_POST['txtPseudo'])==0)
		{
			$erreurs[]="Le Pseudo est obligatoire<br>";
		}
		
		$req="SELECT usID
				FROM users
				WHERE usPseudo='".(mysqli_real_escape_string($BD, ($_POST['txtPseudo'])))."'
				AND usPasse='".(mysqli_real_escape_string($BD, ($_POST['txtPasse'])))."'";
		
		$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		$tab=mysqli_fetch_assoc($res);
		mysqli_free_result($res);
		
		if(count($tab)!=1)
		{
			$erreurs[]="Pseudo ou mot de passe invalide";
		}
		//Variables de session
		else
		{
			$_SESSION['usID']=($tab['usID']);
			$_SESSION['usPseudo']=($_POST['txtPseudo']);
		}
		
		return $erreurs;
	}
	
	
	$BD=pb_bd_connection(BD_URL, BD_USER, BD_PASS, BD_NAME);
	
	$erreurs = array();
	
	//Redirection vers cuiteurs si aucune n'est rencontré
	if (($_POST['btnValider'])=="Connexion")
	{
		$erreurs=pbl_authentification($BD);
		if (count($erreurs)==0)
		{
			header ('location: cuiteur.php');
			exit();
		}
	}
	
	else
	{
		$_POST['txtPseudo']="";
		$_POST['txtPasse']="";
	}
	
		pb_html_debut("Cuiteur | Connexion", CHARSET, "../styles/index.css");
		echo '<body>',
		'<div id="bcPage">',
			'<header>';
				pb_aff_cuiteur_Header_Titre("Connectez-vous");
			echo '</header>',
				'<aside>',
				'</aside>',
				'<section>',
				'<p id="indications">Pour vous connecter &agrave Cuiteur, il faut vous identifier :</p>',
				'<table style="border-collapse:collapse; border-spacing:5 ;">',
				'<form method=POST action="../php/index.php">'.
					pb_form_Ligne("Pseudo", pb_form_input("text", "txtPseudo", "", 20, 15, ""), "").
					pb_form_Ligne("Mot de passe", pb_form_input("password", "txtPasse", "", 20, 15, ""), "").
					pb_form_Ligne("", pb_form_input("submit", "btnValider", "Connexion", "", "", "subInscr"), "alignDroit").
				'</form>',
				'</table><br>';
			
			if((count($erreurs)!=0))
			{
				echo '<h3>Les erreurs suivantes ont &eacute;t&eacute; d&eacute;tect&eacute;s</h3>';
				foreach($erreurs as $value)
				{
					echo $value;
				}
			}
			echo '<br>',
				'<p>Pas encore de compte? <a href="inscription.php">Inscrivez-vous</a> sans plus tarder!</p>',
				'<p>Vous h&eacutesitez &agrave vous inscrire? Laissez-vous s&eacuteduire par une <a href="../html/presentation.html">pr&eacutesentation</a> des possibilit&eacutes de Cuiteur.</p>',
				'</section>',
			
				pb_cuiteur_Footer();
			
			echo '</div>',				
			'</body>',
			'</html>';
	mysqli_close($BD);
	ob_end_flush();
?>
	