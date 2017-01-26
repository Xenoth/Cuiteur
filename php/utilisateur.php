<?php

	include "blibli_cuiteur.php";
	include "blibli_generale.php";
	include "config.php";
	include "requests.php";
	
	session_start();
	ob_start() ;
	
	pb_verifie_session();

	//_______________________________________________________________________________________________________
	/*
	*Fonction permettant de vériffier si l'utilisateur renseigné existe dans la BD
	*Si non on redirige la page vers cuiteur.php pour éviter tout problemes
	*
	*@param	object_mysqli_connect	$BD		il s'agit de l'objet représentant la connection au serveur MySQL
	*
	*@return	int	ID de l'utilisateur renseigné pour affichage du profil
	*/
	function pbl_verifUtilisateur($BD)
	{
		//Si il n'y a pas d'utilisateur renseigné, on redirige vers cuiteur.php
		if(strlen($_GET['utPseudo'])==0)
		{
			header ('location: cuiteur.php');
			exit();
		}
		
		$req="SELECT usID
		FROM users
		WHERE usPseudo='".mysqli_real_escape_string($BD, ($_GET['utPseudo']))."'";
		
		$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		$utID=mysqli_fetch_assoc($res);
		pb_htmlProteger($utID);
		mysqli_free_result($res);
		
		//Si il n'y a pas de correspondance pour l'utilisateur recherché, on redirie vers cuiteur.php
		if(count($utID)!=1)
		{
			header ('location: cuiteur.php');
			exit();
		}
		
		return $utID['usID'];
	}
	
	$BD=pb_bd_connection(BD_URL, BD_USER, BD_PASS, BD_NAME);
	$utID=pbl_verifUtilisateur($BD);
	
	//Si on a cliquer sur "S'abonner" on insère les données dans estabonne
	if (($_POST['btnAbonnement'])=='S\'abonner')
	{
		$req='INSERT INTO estabonne (eaIDUser, eaIDAbonne)
		VALUES (\''.$utID.'\', \''.$_SESSION['usID'].'\')';
		mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	}
	//Si on a cliquer sur "Se désabonner" on supprime les données dans estabonne
	if (($_POST['btnDesabonnement'])==("Se&nbsp;d&eacute;sabonner"))
	{
		$req='DELETE FROM estabonne
		WHERE eaIDUser=\''.$utID.'\'
		AND eaIDAbonne=\''.$_SESSION['usID'].'\'';
		mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
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
	
	//REQUEST FOR SEARCHED USER
	$req=get_Request_User(mysqli_real_escape_string($BD, ($utID)));
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$utilisateur1=mysqli_fetch_assoc($res);
	pb_htmlProteger($utilisateur1);
	mysqli_free_result($res);
	
	$req=get_Request_User_Count_Blablas(mysqli_real_escape_string($BD, ($utID)));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabSeachedCountBlablas=mysqli_fetch_assoc($res);
	$req=get_Request_User_Count_Mentions(mysqli_real_escape_string($BD, ($utID)));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabSeachedCountMentions=mysqli_fetch_assoc($res);
	$req=get_Request_User_Count_Abonnes(mysqli_real_escape_string($BD, ($utID)));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabSeachedCountAbonnes=mysqli_fetch_assoc($res);
	$req=get_Request_User_Count_Abonnements(mysqli_real_escape_string($BD, ($utID)));	
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabSeachedCountAbonnements=mysqli_fetch_assoc($res);
	pb_htmlProteger($tabSeachedCountAbonnes);
	pb_htmlProteger($tabSeachedCountMentions);
	pb_htmlProteger($tabSeachedCountAbonnements);
	pb_htmlProteger($tabSeachedCountBlablas);
	mysqli_free_result($res);
	
	$req=get_Request_Watching_User((mysqli_real_escape_string($BD, ($_SESSION['usID']))), (mysqli_real_escape_string($BD, ($utID))));
	$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	$tabUtAbonne=mysqli_fetch_assoc($res);
	pb_htmlProteger($tabUtAbonne);
	mysqli_free_result($res);
	
	pb_html_debut("Cuiteur | Utilisateur", CHARSET, "../styles/cuiteur.css");
	
		echo '<body>',
			'<div id="bcPage"><header>';
		
			pb_aff_cuiteur_Header_Liens();
			pb_aff_cuiteur_Header_Titre("Le profil de ".$utilisateur1['usPseudo']);
			pb_aff_cuiteur_Header_Utilisateur($utilisateur1, $tabSeachedCountBlablas, $tabSeachedCountMentions, $tabSeachedCountAbonnes, $tabSeachedCountAbonnements);
			
			echo '</header>';
			
			pb_aff_aside($tabUser, $tabUserCountBlablas, $tabUserCountAbonnements, $tabUserCountAbonnes, $resTags, $resSug);
			mysqli_free_result($resTags);
			mysqli_free_result($resSug);
			
			echo '<section>',
				'<table id="tabCenter">',
					'<tr>',
						'<td class="alignDroit"><strong>Ville de r&eacute;sidence :</strong></td>',
						'<td>'.est_renseigne($utilisateur1['usVille']).'</td>',
					'</tr>',
					'<tr>',
						'<td class="alignDroit"><strong>Site web :</strong></td>',
						'<td>'.est_renseigne($utilisateur1['usWeb']).'</td>',
					'</tr>',
					'<tr>',
						'<td class="alignDroit"><strong>Date de naissance :</strong></td>',
						'<td>'.est_renseigne(pb_amj_clair($utilisateur1['usDateNaissance'])).'</td>',
					'</tr>',
					'</table>';
				//Si le profil est différent de de celui de l'utilisateur connecté on affiche la possibilité de se désabonner ou non
				if($utilisateur1['usID']!=$_SESSION['usID'])
				{
					//Si l'utilisateur n'est pas abonné a la personne affichée on affiche "S'abonner"
					if (count($tabUtAbonne)==0)
					{
						echo '<form method=POST action="utilisateur.php?utPseudo='.$utilisateur1['usPseudo'].'">',
						'<input type="submit" name="btnAbonnement" value="S\'abonner" class="button">',
						'</form>';
					}
					//Si l'utilisateur est déjà abonné à la personne affichée on affiche "Se désabonner"
					else
					{
						echo '<form method=POST action="utilisateur.php?utPseudo='.$utilisateur1['usPseudo'].'">',
						'<input type="submit" name="btnDesabonnement" value="Se&nbsp;d&eacute;sabonner" class="button">',
						'</form>';
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