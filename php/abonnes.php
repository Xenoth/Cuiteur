<?php

	include "blibli_cuiteur.php";
	include "blibli_generale.php";
	include "config.php";
	include "requests.php";
	
	session_start();
	ob_start() ;
	
	pb_verifie_session();
		
	$BD=pb_bd_connection(BD_URL, BD_USER, BD_PASS, BD_NAME);
	
	//Si l'utilisateur a validé
	if($_POST['btnValider']=='Valider')
	{
		//On regarde chaque valeur de la variable $_POST (des checkbox renseignant l'id de l'utilisateur coché)
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
				//Si l'id est bien présent dans la BD
				if(count($tab)==1)
				{
					//On utilise une requete pour vérifier si l'utilisateur est abonné ou non a lui
					$req=get_Request_Watching_User(mysqli_real_escape_string($BD, ($_SESSION['usID'])), mysqli_real_escape_string($BD, $value));
					$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
					$tabUtAbonne=mysqli_fetch_assoc($res);
					pb_htmlProteger($tabUtAbonne);
					mysqli_free_result($res);
					//Si il ne l'ai pas, c'est que le checkbox correspondait à "s'abonner" et on insert les données dans la table estabonne
					if (count($tabUtAbonne)==0)
					{
						$req='INSERT INTO estabonne (eaIDUser, eaIDAbonne)
						VALUES (\''.$value.'\', \''.$_SESSION['usID'].'\')';
						mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
					}
					//Sinon l'utilisateur souhaitait se désabonner et on supprime les données dans la table estabonne
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
	
	//Requete récupérant les utilisateurs abonnés a l'utilisateur connecté
	$req="SELECT *
		FROM users, estabonne
		WHERE usID=eaIDAbonne
		AND eaIDUser=".($_SESSION['usID'])."";
	$resRecherche=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
	mysqli_free_result($res);
	
	pb_html_debut("Cuiteur | Abonn&eacute;s", CHARSET, "../styles/cuiteur.css");
	
		echo '<body>',
			'<div id="bcPage"><header>';
		
			pb_aff_cuiteur_Header_Liens();
			pb_aff_cuiteur_Header_Titre("Vos Abonn&eacute;s");
			pb_aff_cuiteur_Header_Utilisateur($tabUser, $tabUserCountBlablas, $tabUserCountMentions, $tabUserCountAbonnes, $tabUserCountAbonnements);
			
			echo '</header>';
			
			pb_aff_aside($tabUser, $tabUserCountBlablas, $tabUserCountAbonnements, $tabUserCountAbonnes, $resTags, $resSug);
			mysqli_free_result($resTags);
			mysqli_free_result($resSug);
			
			echo '<section>',
			'<ul id="bcMessages"><form method=POST action="abonnes.php?utID='.$_SESSION['usID'].'">';
			
			//On affiche tout les abonnements
			while(($tab=mysqli_fetch_assoc($resRecherche)))
			{
				//Requetes pour afficher les données relatives a l'utilisateur auquel on est abonné (blablas, mentions, abonnées, abonnements)
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
			echo '</ul>',
			'<input type="submit" name="btnValider" value="Valider" class="button"></form></section>';

			pb_cuiteur_Footer();
			
			echo '</div>',
	'</body>',
	'</html>',
	
	mysqli_close($BD);
	ob_end_flush();

?>