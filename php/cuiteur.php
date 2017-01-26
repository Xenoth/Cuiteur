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
	*Fonction permettant de poster un blabla
	*En cas de problèmes elle retournera l'erreur (message trop court)
	*
	*@param	object_mysqli_connect	$BD		il s'agit de l'objet représentant la connection au serveur MySQL
	*
	*@return  array	retourne un tableau avec les erreurs rencontrés, si les données sont valides le tableau est vide
	*/
	function pbl_postBlabla($BD)
	{
		$erreurs=array();
	
		if(strlen($_POST['txtMessage'])<=0)
		{
			$erreurs[]="Message trop court";
		}
		
		//Si pas derreur
		if(count($erreurs)==0)
		{
			//On insère le cuiteur
			$localtime=localtime(time(), true);
			$req="INSERT INTO blablas (blIDAuteur, blDate, blHeure, blTexte, blAvecCible)
			VALUES
			('".($_SESSION['usID']).
			"','".($localtime['tm_year']+1900).(str_pad((($localtime['tm_mon'])+1), 2, '0', STR_PAD_LEFT)).(str_pad(($localtime['tm_mday']), 2, '0', STR_PAD_LEFT)).
			"','".(str_pad((($localtime['tm_hour'])+1), 2, '0', STR_PAD_LEFT)).":".(str_pad((($localtime['tm_min'])+1), 2, '0', STR_PAD_LEFT)).":".(str_pad(($localtime['tm_sec']), 2, '0', STR_PAD_LEFT)).
			"','".mysqli_real_escape_string ($BD, ($_POST['txtMessage']))."', ". 0 .");";
			
			//On analyse le texte pour retrouvé les mentions et tags
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			$last_id=mysqli_insert_id($BD);
			$spaceArray = explode(' ', ($_POST['txtMessage']));
			$tags=array();
			$mentions=array();
			foreach($spaceArray as $value)
			{
				if ($value[0]=='#')
				{
					$tags[]=$value;
				}
				else if($value[0]=='@')
				{
					$mentions[]=$value;
				}
			}
			//insertion des tags
			foreach($tags as $tag)
			{
				$req="INSERT INTO tags (taID, taIDBlabla)
				VALUES
				('".mysqli_real_escape_string($BD, (substr($tag, 1))).
				"','".$last_id."');";
				mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			}
			
			//insertion des mentions après vérification que le pseudo correspond a quelqu'un présent dans la base de donnée
			foreach($mentions as $mention)
			{
				$req="SELECT usID
					FROM users
					WHERE usPseudo='".mysqli_real_escape_string ($BD, (substr($mention, 1)))."'";
				$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				$tab=mysqli_fetch_assoc($res);
				pb_htmlProteger($tab);
				if(count($tab)==1)
				{
					$req="INSERT INTO mentions (meIDUser, meIDBlabla)
						VALUES
						('".mysqli_real_escape_string ($BD, ($tab['usID'])).
						"','".$last_id.
						"');";
					mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
				}
			}
		}
		return $erreurs;
	}
	
	$erreurs=array();
	
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
	
	//Si l'utilisateur a publier un message
	if (($_POST['btnPublier'])=="btnPublier")
	{
		$erreurs=pbl_postBlabla($BD);
		if(count($erreurs)==0)
		{
			header ('location: cuiteur.php');
			exit();
		}
	}
	else
	{
		$_POST['txtMessage']="";
	}
	
	//Si l'utilisateur recuite un message
	if((strlen($_GET['recuiter']))>0)
	{
		//On verifie au préalable que l'id du blabla renseigné existe bien
		$req="SELECT blID, blTexte
			FROM blablas
			WHERE blID='".mysqli_real_escape_string ($BD, ($_GET['recuiter']))."'";
		$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
		$tab=mysqli_fetch_assoc($res);
		mysqli_free_result($res);
		//Si oui
		if(count($tab)==2)
		{
			//On recuite le message
			$localtime=localtime(time(), true);
			$req="INSERT INTO blablas (blIDAuteur, blDate, blHeure, blTexte, blAvecCible, blIDOriginal)
			VALUES
			('".($_SESSION['usID']).
			"','".($localtime['tm_year']+1900).(str_pad((($localtime['tm_mon'])+1), 2, '0', STR_PAD_LEFT)).(str_pad(($localtime['tm_mday']), 2, '0', STR_PAD_LEFT)).
			"','".(str_pad((($localtime['tm_hour'])+1), 2, '0', STR_PAD_LEFT)).":".(str_pad((($localtime['tm_min'])+1), 2, '0', STR_PAD_LEFT)).":".(str_pad(($localtime['tm_sec']), 2, '0', STR_PAD_LEFT)).
			"','".mysqli_real_escape_string ($BD, ($tab['blTexte']))."', '". 0 ."', '".mysqli_real_escape_string ($BD, ($tab['blID']))."');";
			mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			$last_id=mysqli_insert_id($BD);
			//On insere les meme tags contenus
			$req="SELECT *
			FROM tags
			WHERE taIDBlabla='".mysqli_real_escape_string ($BD, ($_GET['recuiter']))."'";
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			while($tags=mysqli_fetch_assoc($res))
			{
				$req="INSERT INTO tags (taID, taIDBlabla)
				VALUES
				('".mysqli_real_escape_string($BD, $tags['taID']).
				"','".$last_id."');";
				mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			}
			//On insere les meme mentions contenus
			$req="SELECT *
			FROM mentions
			WHERE meIDBlabla='".mysqli_real_escape_string ($BD, ($_GET['recuiter']))."'";
			$res=mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			while($mentions=mysqli_fetch_assoc($res))
			{
				$req="INSERT INTO tags (meIDUser, meIDBLabla)
				VALUES
				('".mysqli_real_escape_string($BD, $mentions['meIDUser']).
				"','".$last_id."');";
				mysqli_query($BD, $req) OR fd_bd_erreur($BD, $req);
			}
			
		}
		//Rechargement de la page sans $_GET['recuiter']
		header ('location: cuiteur.php');
		exit();
	}
	
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
		//Si oui
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
		//Rechargement de la page sans $_GET['delete']
		header ('location: cuiteur.php');
		exit();
	}
	
	pb_html_debut("Cuiteur", CHARSET, "../styles/cuiteur.css");
		
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
	
	echo '<body>',
		'<div id="bcPage">',
		'<header>';
		
		pb_aff_cuiteur_Header_Liens();
		
		echo '<form id="frmPublier" action="cuiteur.php" method="POST">',
		'<textarea id="txtMessage" name="txtMessage">'.$_GET['txtBlabla'].'</textarea>',
		'<input id="btnPublier" type="submit" name="btnPublier" value="btnPublier" title="Publier mon message">',
		'</form></header>';
			
		pb_aff_aside($tabUser, $tabUserCountBlablas, $tabUserCountAbonnements, $tabUserCountAbonnes, $resTags, $resSug);
		mysqli_free_result($resTags);
		mysqli_free_result($resSug);
		
			if((count($erreurs)!=0))
			{
				echo '<h3>Les erreurs suivantes ont &eacute;t&eacute; d&eacute;tect&eacute;s</h3>';
				foreach($erreurs as $value)
				{
					echo $value;
				}
			}
			//Requete pour trouvé les blasblas de l'utilisateur et de ses abonnés
			$Req="SELECT usPseudo, usID, usNom, blID, blDate, blHeure, blTexte, blAvecCible, blIDOriginal, usAvecPhoto
			FROM blablas, users
			WHERE usID=blIDAuteur 
			AND (usID='".$_SESSION['usID']."'
				OR usID IN   ( 	SELECT eaIDUser
							FROM estabonne
							WHERE eaIDAbonne='".$_SESSION['usID']."'))
			ORDER BY blDate DESC, blHeure DESC";
			
			$res=mysqli_query($BD, $Req) OR fd_bd_erreur($BD, $Req);
			echo '<section>',
			'<ul id="bcMessages">';
			//Plus de blablas
			if (pb_aff_blablas($res, $countBlablas)>=$countBlablas)
			{
				echo	'<li id="bcPlus">'.
				'<a href="cuiteur.php?foisBlablas='.($_GET['foisBlablas']+1).'"><strong>Plus de blablas</strong></a>';
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
