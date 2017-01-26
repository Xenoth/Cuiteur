<?php

	include "blibli_generale.php";
	include "blibli_cuiteur.php";
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
	$erreurs=array();
	$utID=pbl_verifUtilisateur($BD);
	
	//Si l'utilisateur supprime un message
	if(strlen($_GET['delete'])>0)
	{
		//On verifie au préalable que l'id du blabla renseigné appartient bien a l'utilisateur avant de supprimer les données
		$req="SELECT blID
			FROM blablas
			WHERE blIDAuteur='".mysqli_real_escape_string ($BD, ($_SESSION['usID']))."'";
		$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		$tab=mysqli_fetch_assoc($res);
		mysqli_free_result($res);
		
		if(count($tab)==1)
		{
			//On supprime les données relatives a ce blabla dans les tables blablas, mentions, et tags
			$req="DELETE FROM blablas
				WHERE blID='".mysqli_real_escape_string ($BD, ($_GET['delete']))."'
				AND blIDAuteur='".mysqli_real_escape_string ($BD, ($_SESSION['usID']))."'";
				mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			$req="DELETE FROM mentions
				WHERE meIDBlabla='".mysqli_real_escape_string ($BD, ($_GET['delete']))."'";
				mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			$req="DELETE FROM tags
				WHERE taIDBlabla='".mysqli_real_escape_string ($BD, ($_GET['delete']))."'";
				mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		}
	}
	
	//Permet de savoir le nombre de blablas a afficher (plus de blablas)
	if($_GET['foisBlablas']=="" || $_GET['foisBlablas']<=0)
	{
		$_GET['foisBlablas']=1;
		$countBlablas=MAX_BLA;
	}
	else
	{
		$countBlablas=MAX_BLA*($_GET['foisBlablas']);
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
	
	pb_html_debut("Cuiteur | Mentions", CHARSET, "../styles/cuiteur.css");
	
		echo '<body>',
			'<div id="bcPage"><header>';
		
			pb_aff_cuiteur_Header_Liens();
			pb_aff_cuiteur_Header_Titre("Les mentions de ".$utilisateur1['usPseudo']);
			pb_aff_cuiteur_Header_Utilisateur($utilisateur1, $tabSeachedCountBlablas, $tabSeachedCountMentions, $tabSeachedCountAbonnes, $tabSeachedCountAbonnements);
			
			echo '</header>';
			
			pb_aff_aside($tabUser, $tabUserCountBlablas, $tabUserCountAbonnements, $tabUserCountAbonnes, $resTags, $resSug);
			mysqli_free_result($resTags);
			mysqli_free_result($resSug);
			
			//Requete récupérant les données necessaire a l'affichage des blablas mentionant l'utilisateur recherché
			$Req="SELECT usPseudo, usID, blDate, blHeure, blTexte, blAvecCible, blIDOriginal, usAvecPhoto, meIDUser, meIDBlabla
			FROM blablas, users, mentions
			WHERE blIDAuteur=usID
			AND blID=meIDBlabla
			AND meIDUser='".mysqli_real_escape_string($BD, ($utID))."'
			ORDER BY blDate DESC, blHeure DESC";
			
			$res=mysqli_query($BD, $Req) OR fd_bd_erreur($BD, $Req);
			echo '<section>',
			'<ul id="bcMessages">';
			if (pb_aff_blablas($res, $countBlablas)>=$countBlablas)
			{
				echo	'<li id="bcPlus">'.
				'<a href="mentions.php?foisBlablas='.($_GET['foisBlablas']+1).'&utPseudo='.$utilisateur1['usPseudo'].'"><strong>Plus de blablas</strong></a>';
			}
			echo '</li>',
				'</ul>',
				'</section>';
			mysqli_free_result($res);
			pb_cuiteur_Footer();
	
	echo '</div>',			
	'</body>',
	'</html>';
	
	mysqli_close($BD);
	ob_end_flush();
?>