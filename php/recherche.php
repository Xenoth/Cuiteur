<?php

	session_start();
	ob_start() ;
	
	include "blibli_generale.php";
	include "blibli_cuiteur.php";
	include "config.php";
	include "requests.php";
	
	$BD=pb_bd_connection(BD_URL, BD_USER, BD_PASS, BD_NAME);
	
	//Si l'utilisateur a valid�
	if($_POST['btnValider']=='Valider')
	{
		//On regarde chaque valeur de la variable $_POST (des checkbox renseignant l'id de l'utilisateur coch�)
		foreach($_POST as $value)
		{
			if((strlen($value)>0) && ($value!="Valider"))
			{
				$req="SELECT usID
				FROM users
				WHERE usID='".$value."'";
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				$tab=mysqli_fetch_assoc($res);
				mysqli_free_result($res);
				//Si l'id est bien pr�sent dans la BD
				if (count($tab)==1)
				{
					//On utilise une requete pour v�rifier si l'utilisateur est abonn� ou non a lui
					$req=get_Request_Watching_User(mysqli_real_escape_string($BD, ($_SESSION['usID'])), mysqli_real_escape_string($BD, $value));
					$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
					$tabUtAbonne=mysqli_fetch_assoc($res);
					pb_htmlProteger($tabUtAbonne);
					mysqli_free_result($res);
					//Si il ne l'ai pas, c'est que le checkbox correspondait � "s'abonner" et on insert les donn�es dans la table estabonne
					if (count($tabUtAbonne)==0)
					{
						$req='INSERT INTO estabonne (eaIDUser, eaIDAbonne)
						VALUES (\''.$value.'\', \''.$_SESSION['usID'].'\')';
						mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
					}
					//Sinon l'utilisateur souhaitait se d�sabonner et on supprime les donn�es dans la table estabonne
					else
					{
						$req='DELETE FROM estabonne
						WHERE eaIDUser=\''.$value.'\'
						AND eaIDAbonne=\''.$_SESSION['usID'].'\'';
						mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
					}
				}
			}
		}
	}
	
	//Si le bouton rechercher a �t� entrer, on lance une requete dans la tab users pour r�cup�rer les pseudos correspondant au motif voulu
	if($_GET['btnRecherche']=="rechercher")
	{
		$req="SELECT *
			FROM users
			WHERE usPseudo LIKE '%".mysqli_real_escape_string($BD, ($_GET['txtRecherche']))."%'";
			
		$resRecherche=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
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
	
	pb_html_debut("Cuiteur | Recherche", CHARSET, "../styles/cuiteur.css");
	
	echo '<body>',
		'<div id="bcPage"><header>'	;
			
		pb_aff_cuiteur_Header_Liens();
		pb_aff_cuiteur_Header_Titre("Rechercher des utilisateurs");
	
	//Formulaire de recherche
	echo '<div id="headerBas">',
		'<table border="1" cellpadding="4" cellspacing="0">',
		'<form method="GET" action="recherche.php">',
		'<tr>',
			'<td> <input type="text" name="txtRecherche" size="30"> </td>',
			'<td class="alignDroit"> <input type="submit" name="btnRecherche" value="rechercher" class="button"> </td>',
		'</tr>',
		'</form>',
		'</table>',
		'</div></header>';
		
		pb_aff_aside($tabUser, $tabUserCountBlablas, $tabUserCountAbonnements, $tabUserCountAbonnes, $resTags, $resSug);
			mysqli_free_result($resTags);
			mysqli_free_result($resSug);

		echo '<section>',
			'<ul id="bcMessages"><form method=POST action="recherche.php">';
			//On affiche les utilisateurs correspondant a la recherche
			while(($tab=mysqli_fetch_assoc($resRecherche)))
			{
				//On recup�re toutes les donn�es necessaires pour l'affichage de son profils (blablas, mentions, abonnes, abonnements, et si l'utilisateur connecter est abonn� ou non � lui)
				$req=get_Request_Watching_User(mysqli_real_escape_string($BD, ($_SESSION['usID'])), mysqli_real_escape_string($BD, ($tab['usID'])));
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				$tabUtAbonne=mysqli_fetch_assoc($res);
				pb_htmlProteger($tabUtAbonne);
				mysqli_free_result($res);
				
				$req=get_Request_User_Count_Blablas(mysqli_real_escape_string($BD, ($tab['usID'])));	
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				$tabSeachedCountBlablas=mysqli_fetch_assoc($res);
				$req=get_Request_User_Count_Mentions(mysqli_real_escape_string($BD, ($tab['usID'])));	
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				$tabSeachedCountMentions=mysqli_fetch_assoc($res);
				$req=get_Request_User_Count_Abonnes(mysqli_real_escape_string($BD, ($tab['usID'])));	
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				$tabSeachedCountAbonnes=mysqli_fetch_assoc($res);
				$req=get_Request_User_Count_Abonnements(mysqli_real_escape_string($BD, ($tab['usID'])));	
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				$tabSeachedCountAbonnements=mysqli_fetch_assoc($res);
				pb_htmlProteger($tabSeachedCountAbonnes);
				pb_htmlProteger($tabSeachedCountMentions);
				pb_htmlProteger($tabSeachedCountAbonnements);
				pb_htmlProteger($tabSeachedCountBlablas);
				mysqli_free_result($res);
				
				//On affiche
				pb_aff_utilisateurs($tab, $tabUtAbonne, $tabSeachedCountBlablas, $tabSeachedCountMentions, $tabSeachedCountAbonnes, $tabSeachedCountAbonnements);
			}
			echo '</ul>';
			//Affichage du bouton "Valider" si il y a eu une recherche
			if ($_GET['btnRecherche']=='rechercher')
			{
				echo '<input type="submit" name="btnValider" value="Valider" class="button"></form>';
			}
		echo '</section>';	
		pb_cuiteur_Footer();
			
		echo '</div>',
	'</body>',
	'</html>',
	
	mysqli_close($BD);
	ob_end_flush();
?>