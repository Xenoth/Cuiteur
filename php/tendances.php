<?php

	include "blibli_generale.php";
	include "blibli_cuiteur.php";
	include "config.php";
	include "requests.php";
	
	session_start();
	ob_start() ;
	pb_verifie_session();
	
	$BD=pb_bd_connection(BD_URL, BD_USER, BD_PASS, BD_NAME);
	
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
	
	pb_html_debut("Cuiteur | Tendances", CHARSET, "../styles/cuiteur.css");
	
		echo '<body>',
			'<div id="bcPage"><header>';
			pb_aff_cuiteur_Header_Liens();
			
			//Si on ne recherche pas de tag en particulier
			if(strlen($_GET['idTag'])==0)
			{
				pb_aff_cuiteur_Header_Titre('Tendances');
			}
			//Sinon on recherche pour un tag on affiche ce dernier en titre
			else
			{
				pb_aff_cuiteur_Header_Titre($_GET['idTag']);
			}
			echo '</header>';
			
			pb_aff_aside($tabUser, $tabUserCountBlablas, $tabUserCountAbonnements, $tabUserCountAbonnes, $resTags, $resSug);
			mysqli_free_result($resTags);
			mysqli_free_result($resSug);
			
			echo '<section>';
			//Si on ne recherche pas de tag en particulier, on affichera les tops
			if(strlen($_GET['idTag'])==0)
			{
				//Requete récupérant les tags trié par occurence et date
				$req="SELECT blDate, taID, COUNT(taID) AS taOccurence
						FROM (SELECT *
							FROM blablas
							ORDER BY blDate DESC) AS blablasDesc, tags
						WHERE taIDBlabla=blID
						GROUP BY taID
						ORDER BY taOccurence DESC, taID DESC";
						
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				//affichage des tags du jour
				pb_aff_tendances($res, 'du jour');
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				//affichage des tags de la semaine
				pb_aff_tendances($res, 'de la semaine');
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				//affichage des tags du mois
				pb_aff_tendances($res, 'du mois');
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				//affichage des tags de l'année
				pb_aff_tendances($res, 'de l\'ann&eacute;e');
				mysqli_free_result($res);
			}
			//Sinon on affiche les blablas contenant le tags recherché
			else
			{
				//Requete récupérant les blablas possédant le tags
				$req="SELECT usID, usPseudo, usNom, usAvecPhoto, blID, blDate, blHeure, blTexte, blIDAuteur, blAvecCible, blIDOriginal 
					FROM blablas, users, tags
					WHERE blID=taIDBlabla
					AND usID=blIDAuteur
					AND taID='".mysqli_real_escape_string($BD, ($_GET['idTag']))."'
					ORDER BY blDate DESC, blHeure DESC";
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				echo '<ul id="bcMessages">';
				//Plus de blablas
				if (pb_aff_blablas($res, $countBlablas)>=$countBlablas)
				{
					echo	'<li id="bcPlus">'.
					'<a href="tendances.php?foisBlablas='.($_GET['foisBlablas']+1).'&idTag='.($_GET['idTag']).'"><strong>Plus de blablas</strong></a>';
				}
				mysqli_free_result($res);
				echo '</li></ul>';
			}
			echo '</section>';
			mysqli_free_result($res);
			pb_cuiteur_Footer();
	
	echo '</div>',			
	'</body>',
	'</html>';
	
	mysqli_close($BD);
	ob_end_flush();
?>