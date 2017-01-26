<?php

	session_start();
	ob_start() ;
	
	include "blibli_generale.php";
	include "blibli_cuiteur.php";
	include "config.php";
	include "requests.php";
	
	pb_verifie_session();
	
	//_______________________________________________________________________________________________________
	/*
	*Fonction permettant de vérifier le premier formulaire
	*En cas de probleme elle retorunera les erreurs
	*
	*@param	object_mysqli_connect	$BD		il s'agit de l'objet représentant la connection au serveur MySQL
	*
	*@return  array	retourne un tableau avec les erreurs rencontrés, si les données sont valides le tableau est vide
	*/
	function pbl_verif_form1($BD)
	{
		$erreurs=array();
		//Vérification des données
		if (strlen($_POST['txtNom'])<4 || strlen($_POST['txtNom'])>40)
		{
			$erreurs[]="Le pseudo doit avoir de 4 &agrave; 40 caract&egrave;res<br>";
		}
		
		if (strlen($_POST['txtVille'])>40)
		{
			$erreurs[]="La ville ne doit pas avoir plus de 40 caract&egrave;res<br>";
		}
		
		if (checkdate(($_POST['selNais_m']),($_POST['selNais_j']),($_POST['selNais_a']))==FALSE)
		{
			$erreurs[]="La date de naissance n'est pas valide<br>";
		}
		//Mise a jour des données de l'utilisateur
		if(count($erreurs)==0)
		{
			$req="UPDATE users
				SET usNom='".(mysqli_real_escape_string($BD, ($_POST['txtNom'])))."', usVille='".(mysqli_real_escape_string($BD, ($_POST['txtVille'])))."', usBio='".(mysqli_real_escape_string($BD, ($_POST['txtBio'])))."', usDateNaissance='".($_POST['selNais_a']).(str_pad(($_POST['selNais_m']), 2, '0', STR_PAD_LEFT)).(str_pad(($_POST['selNais_j']), 2, '0', STR_PAD_LEFT))."'
				WHERE usID='".$_SESSION['usID']."'";
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		}
		return $erreurs;
	}
	
	//_______________________________________________________________________________________________________
	/*
	*Fonction permettant de vérifier le second formulaire
	*En cas de probleme elle retorunera les erreurs
	*
	*@param	object_mysqli_connect	$BD		il s'agit de l'objet représentant la connection au serveur MySQL
	*
	*@return  array	retourne un tableau avec les erreurs rencontrés, si les données sont valides le tableau est vide
	*/
	function pbl_verif_form2($BD)
	{
		$erreurs=array();
		//Vérification des erreurs
		if (strlen($_POST['txtMail'])==0)
		{
			$erreurs[]="Le mail est obligatoire<br>";
		}
		if (strpos(($_POST['txtMail']), "@")==FALSE || strpos(($_POST['txtMail']), ".")==FALSE)
		{
			$erreurs[]="L'adresse mail n'est pas valide<br>";
		}
		
		if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['txtWeb']))
		{
			$erreurs[]="Site web non valide<br>";
		}
		//Mise a jour des données de l'utilisateur
		if(count($erreurs)==0)
		{
			$req="UPDATE users
				SET usMail='".(mysqli_real_escape_string($BD, ($_POST['txtMail'])))."', usWeb='".(mysqli_real_escape_string($BD, ($_POST['txtWeb'])))."'
				WHERE usID='".$_SESSION['usID']."'";
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		}
		return $erreurs;
	}
	
	//_______________________________________________________________________________________________________
	/*
	*Fonction permettant de vérifier le troisième formulaire
	*En cas de probleme elle retorunera les erreurs
	*ATTENTION, Par manque de temps la fonctions ne gère pas l'upload d'une image, toutes tentatives ayant échoués. 
	*On peux cependant décider d'utiliser la photo ou non si elle est manuellement déposé dans /images/uploads/
	*
	*
	*@param	object_mysqli_connect	$BD		il s'agit de l'objet représentant la connection au serveur MySQL
	*
	*@return  array	retourne un tableau avec les erreurs rencontrés, si les données sont valides le tableau est vide
	*/
	function pbl_verif_form3($BD)
	{
		$erreurs=array();
		//Vérification des données
		if(strlen($_POST['txtPasse'])>0)
		{
			if(strlen($_POST['txtVerif'])==0)
			{
				$erreurs[]="V&eacute;rification du mot de passe requise";
			}
			else if(($_POST['txtPasse'])!=($_POST['txtVerif']))
			{
				$erreurs[]="Le mot de passe est diff&eacute;rent dans les 2 zones";
			}
		}
		
		if(($_POST['radPhoto'])<0 || ($_POST['radPhoto'])>1)
		{
			$erreurs[]="bouton radio invalide";
		}
		//Mise a jour des données de l'utilisateur
		if(count($erreurs)==0)
		{
			$req="UPDATE users
			SET";
			if(strlen($_POST['txtPasse'])>0)
			{
				$req=$req." usPasse='".(mysqli_real_escape_string($BD, ($_POST['txtPasse'])))."' ,";
			}
			$req=$req." usAvecPhoto='".($_POST['radPhoto'])."'
			WHERE usID='".$_SESSION['usID']."'";
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		}
		
		return $erreurs;
	}
	
	$BD=pb_bd_connection(BD_URL, BD_USER, BD_PASS, BD_NAME);
	
	$erreurs1 = array();
	$erreurs2 = array();
	$erreurs3 = array();
	
	//Si le premier formulaire a été validé
	if (($_POST['btnValider1'])=="Valider")
	{
		$erreurs1=pbl_verif_form1($BD);
		if (count($erreurs1)==0)
		{
			header ('location: compte.php');
			exit();
		}
	}
	else
	{
		$_POST['txtNom']="";
		$_POST['selNais_j']="0";
		$_POST['selNais_m']="0";
		$_POST['selNais_a']="0";
		$_POST['txtVille']="0";
		$_POST['txtBio']="";
	}
	
	//Si le second formulaire a été validé
	if (($_POST['btnValider2'])=="Valider")
	{
		$erreurs2=pbl_verif_form2($BD);
		if (count($erreurs2)==0)
		{
			header ('location: compte.php');
			exit();
		}
	}
	else
	{
		$_POST['txtMail']="";
		$_POST['txtWeb']="";
	}
	
	//Si le troisième formulaire a été validé
	if (($_POST['btnValider3'])=="Valider")
	{
		$erreurs3=pbl_verif_form3($BD);
		if (count($erreurs3)==0)
		{
			header ('location: compte.php');
			exit();
		}
	}
	else
	{
		$_POST['txtPass']="";
		$_POST['txtVerif']="";
		$_POST['radPhoto']=1;
	}
	
	//REQUEST FOR CONNECTED USER DATAS
	$req=get_Request_User(mysqli_real_escape_string($BD, ($_SESSION['usID'])));
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabUser=mysqli_fetch_assoc($res);
	pb_htmlProteger($tabUser);
	mysqli_free_result($res);
	
	$req=get_Request_User_Count_Blablas(mysqli_real_escape_string($BD, ($_SESSION['usID'])));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabUserCountBlablas=mysqli_fetch_assoc($res);
	$req=get_Request_User_Count_Mentions(mysqli_real_escape_string($BD, ($_SESSION['usID'])));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabUserCountMentions=mysqli_fetch_assoc($res);
	$req=get_Request_User_Count_Abonnes(mysqli_real_escape_string($BD, ($_SESSION['usID'])));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabUserCountAbonnes=mysqli_fetch_assoc($res);
	$req=get_Request_User_Count_Abonnements(mysqli_real_escape_string($BD, ($_SESSION['usID'])));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabUserCountAbonnements=mysqli_fetch_assoc($res);
	pb_htmlProteger($tabUserCountAbonnes);
	pb_htmlProteger($tabUserCountMentions);
	pb_htmlProteger($tabUserCountAbonnements);
	pb_htmlProteger($tabUserCountBlablas);
	mysqli_free_result($res);
	
	
	//REQUEST F0R TAGS
	$req=get_Request_Aside_Tags();
	$resTags=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	
	//REQUEST FOR SUGGESTIONS
	$req=get_Request_Suggestions_Aside(mysqli_real_escape_string($BD, ($_SESSION['usID'])));
	$resSug=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	
	pb_html_debut("Cuiteur | Compte", CHARSET, "../styles/cuiteur.css");
	
	echo '<body>',
		'<div id="bcPage"><header>';
		
			pb_aff_cuiteur_Header_Liens();
			pb_aff_cuiteur_Header_Titre("Param&egravetres de mon compte");
			
			echo '</header>';
			
			pb_aff_aside($tabUser, $tabUserCountBlablas, $tabUserCountAbonnements, $tabUserCountAbonnes, $resTags, $resSug);
			mysqli_free_result($resTags);
			mysqli_free_result($resSug);
			//FORMULAIRE 1
			echo '<section>',
				'<p id="indications">Cette page vous permet de modifier les informations relatives &agrave votre compte.</p>',

				'<p class="titreSepar">Informations personnelles</p>',
				'<table border="1" cellpadding="4" cellspacing="0">',
				'<form method="POST" action="compte.php">'.
					pb_form_Ligne("Nom", pb_form_input("text", "txtNom", $tabUser['usNom'], 40, 40, ""), "").
					'<tr>','<td class="alignDroit">Date de naissance</td>',
						'<td>',
							pb_form_date("selNais", (int)substr($tabUser['usDateNaissance'], 6, 2), (int)substr($tabUser['usDateNaissance'], 4, 2), (int)substr($tabUser['usDateNaissance'], 0, 4)),
						'</td></tr>'.
					pb_form_Ligne("Ville", pb_form_input("text", "txtVille", $tabUser['usVille'], 40, 40, ""), "").
					'<tr>',
						'<td class="alignHaut">Mini-bio</td>',
						'<td> <textarea cols="51" rows="10" name="txtBio" size="40">',$tabUser['usBio'],'</textarea></td>',
					'</tr>'.
					pb_form_Ligne("", pb_form_input("submit", "btnValider1", "Valider", "", "", "", "button"), "alignDroit").
				'</form>',
				'</table>';
				
			if((count($erreurs1)!=0))
			{
				echo '<h3>Les erreurs suivantes ont &eacute;t&eacute; d&eacute;tect&eacute;s</h3>';
				foreach($erreurs1 as $value)
				{
					echo $value;
				}
			}
			//FORMULAIRE 2
			echo	'<p class="titreSepar">Informations sur votre compte Cuiteur</p>',
				'<table border="1" cellpadding="4" cellspacing="0">',
				'<form method="POST" action="compte.php">'.
				pb_form_Ligne("Adresse email", pb_form_input("text", "txtMail", $tabUser['usMail'], "", 40, ""), "").
				pb_form_Ligne("Site web", pb_form_input("text", "txtWeb", $tabUser['usWeb'], "", 40, ""), "").
				pb_form_Ligne("", pb_form_input("submit", "btnValider2", "Valider", "", "", "", "button"), "alignDroit").
				'</form>',
				'</table>';
			
			if((count($erreurs2)!=0))
			{
				echo '<h3>Les erreurs suivantes ont &eacute;t&eacute; d&eacute;tect&eacute;s</h3>';
				foreach($erreurs2 as $value)
				{
					echo $value;
				}
			}
			//FORMULAIRE 3
			echo	'<p class="titreSepar">Param&egravetres de votre compte Cuiteur</p>',
				'<table border="1" cellpadding="4" cellspacing="0">',
				'<form method="POST" action="compte.php">'.
					pb_form_Ligne("Changer le mot de passe", pb_form_input("password", "txtPasse", $tabUser['txtPasse'], "", 15, ""), "").
					pb_form_Ligne("Retaper le mot de passe", pb_form_input("password", "txtVerif", $tabUser['txtPasse'], "", 15, ""), "").
					'<tr>',
						'<td class="alignHaut">Votre photo actuelle</td>',
						'<td> <img src="../images/upload/'.$tabUser['usID'].'.jpg"> </td>',
					'</tr>',
					'<tr>',						
						'<td class="alignDroit"></td>',
						'<td> <p>Image JPG carr&eacutee (mini 50x50px)</p>',
						'<input type="file" name="imgAvatar" accept="image/*"> </td>',
					'</tr>',
					'<tr>',
						'<td>Utiliser votre photo</td>',
						'<td> <input type="radio" name="radPhoto" value="0" id="radNon" class="alignDroit"';
						if (($tabUser['usAvecPhoto'])==0)
						{
							echo ' checked ';
						}
						echo '>non <input type="radio" name="radPhoto" value="1" id="radOui" ';
						if (($tabUser['usAvecPhoto'])!=0)
						{
							echo ' checked ';
						}
						echo '>oui </td>',
					'</tr>',
					pb_form_Ligne("", pb_form_input("submit", "btnValider3", "Valider", "", "", "", "button"), "alignDroit").
				'</form>',
				'</table>';
			
			if((count($erreurs3)!=0))
			{
				echo '<h3>Les erreurs suivantes ont &eacute;t&eacute; d&eacute;tect&eacute;s</h3>';
				foreach($erreurs3 as $value)
				{
					echo $value;
				}
			}
			
			echo '</section>';
				
			pb_cuiteur_Footer();
			
		echo '</div>',				
	'</body>',
'</html>';
	
	mysqli_close($BD);
	ob_end_flush();
?>