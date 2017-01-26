<?php

//___________________________________________________________________
/**
 * Gestion d'une erreur de requête base de données.
 *
 * @param resource	$bd		Connecteur sur la bd ouverte
 * @param string	$sql	requête SQL provoquant l'erreur
 */
function fd_bd_erreur($bd, $sql) {
	$errNum = mysqli_errno($bd);
	$errTxt = mysqli_error($bd);

	// Collecte des informations facilitant le debugage
	$msg = '<h4>Erreur de requête</h4>'
			."<pre><b>Erreur mysql :</b> $errNum"
			."<br> $errTxt"
			."<br><br><b>Requête :</b><br> $sql"
			.'<br><br><b>Pile des appels de fonction</b>';

	// Récupération de la pile des appels de fonction
	$msg .= '<table border="1" cellspacing="0" cellpadding="2">'
			.'<tr><td>Fonction</td><td>Appelée ligne</td>'
			.'<td>Fichier</td></tr>';

	$appels = debug_backtrace();
	for ($i = 0, $iMax = count($appels); $i < $iMax; $i++) {
		$msg .= '<tr align="center"><td>'
				.$appels[$i]['function'].'</td><td>'
				.$appels[$i]['line'].'</td><td>'
				.$appels[$i]['file'].'</td></tr>';
	}

	$msg .= '</table></pre>';

	fd_bd_erreurExit($msg);
}			
//___________________________________________________________________
/**
 * Arrêt du script si erreur base de données.
 * Affichage d'un message d'erreur si on est en phase de
 * développement, sinon stockage dans un fichier log.
 *
 * @param string	$msg	Message affiché ou stocké.
 */
function fd_bd_erreurExit($msg) {
	ob_end_clean();		// Supression de tout ce qui
					// a pu être déja généré

	echo '<!DOCTYPE html><html><head><meta charset="ISO-8859-1"><title>',
			'Erreur base de données</title></head><body>',
			$msg,
			'</body></html>';
	exit();
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant d'afficher une date retourner par une base de données
*
*@param	string	$date	Date retournée par la BD (aaaammjj)
*
@return string 	Concaténation du jour, mois et de l'année dans un format lisible facilement
*/

function pb_amj_clair($date)
{
	$annee=(int)substr($date, 0, 4);
	$mois=(int)substr($date, 4, 2);
	$jour=(int)substr($date, 6, 2);
	$moiss=array('janvier','fevrier','mars','avril','mai','juin','juillet','aout','septembre','octobre','novembre','decembre');
	
	return $jour.' '.$moiss[$mois-1].' '.$annee;
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant de rédiger l'entête de la page HTML générée
*Attention, il ne faut pas oublier de fermer les balises <body> et <html> à la fin de votre code !
*
*@param	string 	$title		nom de la page
*@param string	$charset		système d'écriture désiré
*@param	string	$href_style	url de la feuille de style CSS désirée
*/
function pb_html_debut($title, $charset, $href_style) {
	echo '<!DOCTYPE html> 
	<html lang=fr>
	<head>
	<title>',$title,'</title>
	<meta charset=',$charset,'>
	<link href=',$href_style,' rel=stylesheet>
	<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
	</head>';
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant de se connecter à la BD
*En cas d'échec de connection elle affichera l'erreur
*
*@param	string	$url			l'adresse de la BD
*@param	string	$user		nom de l'utilisateur de la BD
*@param string	$password	mot de passe de cet utilisateur
*@param string	$data_bas	nom de la BD
*
*@return  object	il s'agit de l'objet représentant la connection au serveur MySQL
*/
function pb_bd_connection($url, $user, $password, $data_base)
{
	$BD=mysqli_connect($url, $user, $password, $data_base);
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	return $BD;
}

//_______________________________________________________________________________________________________
/*
*Fonction protegeant le code html des requêtes
*
*@param	array	$tab		tableau que l'on souhaite sécurisé
*/
function pb_htmlProteger ($tab)
{
	foreach ($tab as $cle => $val)
	{
		$tab[$cle]= htmlentities($val, ENT_COMPAQ, "UTF-8");
	}
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant d'afficher les blablas retourner par une requete
*
*@param object_mysqli_query	$res		résultat de la requête que l'on veux afficher
*@param	int	count_max	Nombre de blablas que l'on souhaite afficher
*
*@return	count	Nombre de blablas qui ont été affichés
*/
function pb_aff_blablas($res, $count_max)
{
	$string="";
	$count=0;
	while(($tab=mysqli_fetch_assoc($res)) && ($count<$count_max))
	{
		pb_htmlProteger($tab);
		$count=$count+1;
		echo '<li>';
		if ($tab['usAvecPhoto']==1)
		{
			echo '<img src="../images/upload/'.$tab['usID'].'.jpg" class="imgAuteur">';
		}
		else 
		{
			echo '<img src="../images/anonyme.jpg" class="imgAuteur">';
		}
		echo '<p class=hautMessage><a href="utilisateur.php?utPseudo='.$tab['usPseudo'].'" title="Voir le CV"><strong>'.$tab['usPseudo'].'</strong></a> '.$tab['usNom'].'</p><p>'.pb_aff_txt_blablas($tab['blTexte']).'</p>',
		'<p class="finMessage">',
		pb_aff_date_blablas($tab['blDate'], $tab['blHeure']);
		if ($tab['usID']!=$_SESSION['usID'])
		{
			echo '<a href="cuiteur.php?txtBlabla=@'.$tab['usPseudo'].'&blID='.$tab['blID'].'">R&eacute;pondre</a> <a href="cuiteur.php?recuiter='.$tab['blID'].'">Recuiter</a><p>';
		}
		else
		{
			echo '<a href="cuiteur.php?delete='.$tab['blID'].'">Supprimer</a><p>';
		}
		echo '</li>';
	}
	return $count;
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant d'afficher un utilisateur retourner par une requete
*
*@param	array	$tabUser	Tableau retourner par la requete contenant toute les infos necessaires de la table users
*@param	array	$tabIsAbonne	Tableau contenant le résultat de la requete si la personne connectée est abonnée a l'utilisateur 
*@param	array	$tabBlablas	Tableau retourné par la requete comptant le nombres de blablas de l'utilisateur
*@param	array	$tabMentions	Tableau retourné par la requete comptant le nombres de mentions de l'utilisateur
*@param	array	$tabAbonnes	Tableau retourné par la requete comptant le nombres d'abonnés de l'utilisateur
*@param	array	$tabAbonnements	Tableau retourné par la requete comptant le nombres d'abonnements de l'utilisateur
*/
function pb_aff_utilisateurs($tabUser, $tabIsAbonne, $tabBlablas, $tabMentions, $tabAbonnes, $tabAbonnements)
{
	echo '<li>';
		if ($tabUser['usAvecPhoto']==1)
		{
			echo '<img src="../images/upload/'.$tabUser['usID'].'.jpg" class="imgAuteur">';
		}
		else
		{
			echo '<img src="../images/anonyme.jpg" class="imgAuteur">';
		}
		echo '<p><a href="utilisateur.php?utPseudo='.$tabUser['usPseudo'].'">'.$tabUser['usPseudo'].'</a> '.$tabUser['usNom'].'</p>'.
		'<p><a href="blablas.php?utID='.$tabUser['usID'].'">'.$tabBlablas['blablas'].' blablas</a> - <a href="mentions.php?utPseudo='.$tabUser['usPseudo'].'">'.$tabMentions['mentions'].' mentions</a> - <a href="abonnes.php">'.$tabAbonnes['abonne'].' abonn&eacute;s</a> - <a href="abonnements.php">'.$tabAbonnements['abonnements'].' abonnements</a></p>';
		if($tabUser['usID']!=$_SESSION['usID'])
		{
			if (count($tabIsAbonne)==0)
			{
				echo '<p class="alignDroit"><input type="checkbox" name="choixAbonne_'.$tabUser['usID'].'" value=\''.$tabUser['usID'].'\'>S\'abonner</p>';
			}
			else
			{
				echo '<p class="alignDroit"><input type="checkbox" name="choixAbonne_'.$tabUser['usID'].'" value=\''.$tabUser['usID'].'\'>Se d&eacute;sabonner</p>';
			}
		}
	echo '</li>';
}
//_______________________________________________________________________________________________________
/*
*Fonction permettant de verifier que l'existence des variables de session necessaire à une connection
*/
function pb_verifie_session()
{
	if (!$_SESSION['usID'] || !$_SESSION['usPseudo'])
	{
		header ('location: index.php');
		exit();
	}
}

//_______________________________________________________________________________________________________
/*
*Fonction affichant le pied de page de cuiteur
*/
function pb_cuiteur_Footer()
{
	echo '<footer>',
				'<ul id="bcPied">',
					'<li><a href="">A propos</a></li>',
					'<li><a href="">Publicit&eacute;</a></li>',
					'<li><a href="">Patati</a></li>',
					'<li><a href="">Aide</a></li>',
					'<li><a href="">Patata</a></li>',
					'<li><a href="">Stages</a></li>',
					'<li><a href="">Emplois</a></li>',
					'<li><a href="">Confidentialit&eacute;</a></li>',				
				'</ul>',
			'</footer>';
}

//_______________________________________________________________________________________________________
/*
*Fonction affichant le titre dans le header
*
*@param	string	$txtTitre	Titre désiré
*/

function pb_aff_cuiteur_Header_Titre($txtTitre)
{
	echo '<h2 id="title">',$txtTitre,'</h2><img src="../images/trait.png" id="trait">';
}

//_______________________________________________________________________________________________________
/*
*Fonction affichant les 4 boutons présents dans le header
*/
function pb_aff_cuiteur_Header_Liens()
{
	echo	'<a id="btnDeconnexion" href="deconnexion.php" title="Se d&eacute;connecter de cuiteur"></a>',
		'<a id="btnHome" href="cuiteur.php" title="Ma page d\'accueil"></a>',
		'<a id="btnCherche" href="recherche.php" title="Rechercher des personnes &agrave suivre"></a>',
		'<a id="btnConfig" href="compte.php" title="Modifier mes informations personnelles"></a>';
}

//_______________________________________________________________________________________________________
/*
*Fonction affichant les données d'un utilisateur dans le header
*
*@param	array	utilisateur1	Tableau retourné par la requete comptenant les informations de l'utilisateur dans users
*@param	array	$tabBlablas	Tableau retourné par la requete comptant le nombres de blablas de l'utilisateur
*@param	array	$tabMentions	Tableau retourné par la requete comptant le nombres de mentions de l'utilisateur
*@param	array	$tabAbonnes	Tableau retourné par la requete comptant le nombres d'abonnés de l'utilisateur
*@param	array	$tabAbonnements	Tableau retourné par la requete comptant le nombres d'abonnements de l'utilisateur
*
*/
function pb_aff_cuiteur_Header_Utilisateur($utilisateur1, $tabBlablas, $tabMentions, $tabAbonnes, $tabAbonnements)
{
	echo '<div id="headerBas">';
	if($utilisateur1['usAvecPhoto']==1)
	{
		echo '<img src="../images/upload/'.$utilisateur1['usID'].'.jpg" class="imgUtil">';
	}
	else 
	{
		echo '<img src="../images/anonyme.jpg" class="imgUtil">';
	}
	echo	'<p><a href="utilisateur.php?utPseudo='.$utilisateur1['usPseudo'].'">'.$utilisateur1['usPseudo'].'</a>  '.$utilisateur1['usNom'].'</p>',
		'<p><a href="blablas.php?utPseudo='.$utilisateur1['usPseudo'].'">'.$tabBlablas['blablas'].' blablas</a> - <a href="mentions.php?utPseudo='.$utilisateur1['usPseudo'].'">'.$tabMentions['mentions'].' mentions</a> - <a href="abonnes.php">'.$tabAbonnes['abonne'].' abonn&eacute;s</a> - <a href="abonnements.php">'.$tabAbonnements['abonnements'].' abonnements</a></p>',
		'</div>';
}

//_______________________________________________________________________________________________________
/*
*Foction permettant de creer une ligne de tableau html composée d'une partie gauche et une partie droite pour les formulaires de cuiteur
*
*@param	string	$left		Partie de gauche désirée dans le tableau 
*@param	string	$right	Partie de droite désirée dans le tableau 
*
*@return	string	la chaine de caractère codant la ligne de tableau html désirée
*/
function pb_form_Ligne_cuiteur($left, $right)
{
	return "<tr><td class=\"alignDroit\">".$left."</td><td>".$right."</td></tr>";
}

//_______________________________________________________________________________________________________
/*
*Fonction affichant les données d'un utilisateur dans le header
*
*@param	array				utilisateur1				Tableau retourné par la requete comptenant les informations de l'utilisateur dans users
*@param	array				$tabBlablas				Tableau retourné par la requete comptant le nombres de blablas de l'utilisateur
*@param	array				$tabMentions				Tableau retourné par la requete comptant le nombres de mentions de l'utilisateur
*@param	array				$tabAbonnes				Tableau retourné par la requete comptant le nombres d'abonnés de l'utilisateur
*@param	array				$tabAbonnements			Tableau retourné par la requete comptant le nombres d'abonnements de l'utilisateur
*@param object_mysqli_query		$resTendances				Résultat de la requête contenant les dernieres tendances
*@param object_mysqli_query		$resSuggestion			Résultat de la requête contenant les suggestions trouvés
*/
function pb_aff_aside($user, $blablas, $abonnements, $abonnes, $resTendances, $resSuggestion)
{
	echo '<aside>',
				'<h3>Utilisateur</h3>',
				'<ul>',
					'<li>';
						if($user['usAvecPhoto']!=0)
						{
							echo '<img src="../images/upload/'.$user['usID'].'.jpg">';
						}
						else 
						{
							echo '<img src="../images/anonyme.jpg">';
						}
						echo '<a href="utilisateur.php?utPseudo='.$user['usPseudo'].'" title="Afficher mon CV">',$user['usPseudo'],'</a> <span class="nomMini">',$user['usNom'],'</span>',
					'</li>',
					'<li><a href="blablas.php?utPseudo='.$user['usPseudo'].'" title="Voir la liste des mes messages">',$blablas['blablas'],' blablas</a></li>',
					'<li><a href="abonnements.php" title="Voir les personnes que je suis">',$abonnements['abonnements'],' abonnements</a></li>',
					'<li><a href="abonnes.php" title="Voir les personnes qui me suivent">',$abonnes['abonne'],' abonn&eacute;s</a></li>',				
				'</ul>',
				'<h3>Tendances</h3>',
				'<ul>';
					$count=0;
					while(($tabTendances=mysqli_fetch_assoc($resTendances)) && ($count<MAX_TAG))
					{
						pb_htmlProteger($tabTendances);
						echo '<li><a href="tendances.php?idTag='.$tabTendances['taID'].'" title="Voir les messages">'.$tabTendances['taID'].'</a></li>';
						$count=$count+1;
					}
					echo '<li><a href="tendances.php" title="Voir les messages">Toutes les tendances</a></li>',
				'</ul>',
				'<h3>Suggestions</h3>',
				'<ul>';
					while($tabSuggestion=mysqli_fetch_assoc($resSuggestion))
					{
						if($tabSuggestion['usAvecPhoto']!=0)
						{
							echo '<img src="../images/upload/'.$tabSuggestion['usID'].'.jpg">';
						}
						else 
						{
							echo '<img src="../images/anonyme.jpg">';
						}
						echo '<a href="utilisateur.php?utPseudo='.$tabSuggestion['usPseudo'].'" title="Afficher mon CV">'.$tabSuggestion['usPseudo'].'</a> <span class="nomMini">'.$tabSuggestion['usNom'].'</span><br>';
					}
					echo '<li><a href="suggestions.php" title="Voir les messages">Plus de suggestions</a></li>',
				'</ul>',
			'</aside>';
}

//_______________________________________________________________________________________________________
/*
*Fonction analysant le texte d'un blabla pour y trouver les tags et mentions
*
*@param	string	$txtBlabla	texte que l'on souhaite analyser
*/
function pb_aff_txt_blablas($txtBlabla)
{
	$spaceArray = explode(' ', $txtBlabla);
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
	foreach($tags as $tag)
	{
		$spaceArray=(str_replace(($tag), ("#<a href=\"tendances.php?idTag=".(substr($tag, 1))."\">".(substr($tag, 1))."</a>"),($spaceArray)));
	}
	foreach($mentions as $mention)
	{
		$spaceArray=(str_replace(($mention), ("@<a href=\"utilisateur.php?utPseudo=".(substr($mention, 1))."\">".(substr($mention, 1))."</a>"),($spaceArray)));
	}
	$return="";
	foreach($spaceArray as $value)
	{
		$return=$return.' '.$value;
	}
	return $return;
}


//_______________________________________________________________________________________________________
/*
*Fonction affichant les tendances du jour, mois, semaine, ...
*
*@param object_mysqli_query		$res		Résultat de la requête contenant les tendances et leur occurances
*@param	string				$date	4 valeurs possibles : 'de l\'ann&eacute;e', 'du mois', 'de la semaine', 'du jour'
*/
function pb_aff_tendances($res, $date)
{
	$localtime=localtime(time(), true);
	$count=0;
	echo '<h1>Top 10 '.$date.'</h1><ol>';
	while(($tab=mysqli_fetch_assoc($res)) && $count<10)
	{
		pb_htmlProteger($tab);
		
		if(((($localtime['tm_year'])+1900)==(substr(($tab['blDate']), 0, 4))) && (($date)=='de l\'ann&eacute;e'))
		{			
			$count++;
			echo '<li><a href="tendances.php?idTag='.($tab['taID']).'">'.($tab['taID']).' ('.$tab['taOccurence'].')</a></li>';
		
		}
		else if(((($localtime['tm_year'])+1900)==(substr(($tab['blDate']), 0, 4))) && ((($localtime['tm_mon'])+1)==(substr(($tab['blDate']), 4, 2))) && (($date)=='du mois'))
		{
			$count++;
			echo '<li><a href="tendances.php?idTag='.($tab['taID']).'">'.($tab['taID']).' ('.$tab['taOccurence'].')</a></li>';
		}
		
		else if(((($localtime['tm_year'])+1900)==(substr(($tab['blDate']), 0, 4))) && ((($localtime['tm_mon'])+1)==(substr(($tab['blDate']), 4, 2))) && ((($localtime['tm_mday']))==(substr(($tab['blDate']), 6, 2))) && (($date)=='du jour'))
		{
			$count++;
			echo '<li><a href="tendances.php?idTag='.($tab['taID']).'">'.($tab['taID']).' ('.$tab['taOccurence'].')</a></li>';
		}
		else if ((($date)=='de la semaine') && ((($localtime['tm_year'])+1900)==(substr(($tab['blDate']), 0, 4))) && ((($localtime['tm_mon'])+1)==(substr(($tab['blDate']), 4, 2))) && (((substr(($tab['blDate']), 6, 2))>=((($localtime['tm_mday']))-((($localtime['tm_wday'])+1)))) && ((substr(($tab['blDate']), 6, 2))<=($localtime['tm_mday']))))
		{
			$count++;
			echo '<li><a href="tendances.php?idTag='.($tab['taID']).'">'.($tab['taID']).' ('.$tab['taOccurence'].')</a></li>';
		}
	}
	echo '</ol>';
	if($count==0)
	{
		echo '<p>Aucune tendance...</p>';
	}	
 }
 
 
 //_______________________________________________________________________________________________________
/*
*Fonction affichant quand le blabla a été posté
*
*@param	string	$date	format aaaammjj : la date du post
*@param	string	$hour	format hh:mm:ss : l'heure du post
*/
 function pb_aff_date_blablas($date, $hour)
 {
	$localtime=localtime(time(), true);
	if((($localtime['tm_year'])+1900)!=(substr(($date), 0, 4)))
	{
		echo 'Il y &agrave; '.((($localtime['tm_year'])+1900)-(substr(($date), 0, 4))).' ans';
	}
	else if((($localtime['tm_mon'])+1)!=(substr(($date), 4, 2)))
	{
		echo 'Il y &agrave; '.((($localtime['tm_mon'])+1)-(substr(($date), 4, 2))).' mois';
	}
	else if((($localtime['tm_mday']))!=(substr(($date), 6, 2)))
	{
		echo 'Il y &agrave; '.((($localtime['tm_mday']))-(substr(($date), 6, 2))).' jours';
	}
	else if((($localtime['tm_hour'])+1)!=(substr(($hour), 0, 2)))
	{
		echo 'Il y &agrave; '.((($localtime['tm_hour'])+1)-(substr(($hour), 0, 2))).' heures';
	}
	else if(((($localtime['tm_min'])+1)!=(substr(($hour), 3, 2))))
	{
		echo 'Il y &agrave; '.((($localtime['tm_min'])+1)-(substr(($hour), 3, 2))).' minutes';
	}
	else if(((($localtime['tm_sec'])+1)!=(substr(($hour), 6, 2))))
	{
		echo 'Il y &agrave; '.((($localtime['tm_sec'])+1)-(substr(($hour), 6, 2))).' secondes';
	}
	else
	{
		echo '&Agrave; l\'instant';
	}
 }
 
?>