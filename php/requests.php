<?php

/*
*
*	/!\ Il est necessaire de s�curiser les donn�es envoy�s a ces fonctions pour �viter toutes attaques SQL /!\
*
*/

//___________________________________________________________________
/**
 *Requete donnant les tedances dans le bloc aside
 *
 */
function get_Request_Aside_Tags()
{
	return 'SELECT taID,	COUNT(taID) AS taOccurence  
	    FROM tags
	    GROUP BY taID
	    ORDER BY taOccurence DESC, taID DESC';
}

//___________________________________________________________________
/**
 *Requete donnant les information de l'utilisateur d�sir� dans user
 *
 *@param		int		$usID	ID de l'utilisateur d�sir�
 */
function get_Request_User($usID)
{
	return 'SELECT *
		FROM users
		WHERE usID='.$usID;
}

//___________________________________________________________________
/**
 *Requete donnant le nombre de blablas post�s de l'utilisateur d�sir�
 *
 *@param		int		$usID	ID de l'utilisateur d�sir�
 */
function get_Request_User_Count_Blablas($usID)
{
	return 'SELECT COUNT(blIDAuteur) AS blablas
		FROM blablas
		WHERE blIDAuteur=\''.($usID).'\'';
}

//___________________________________________________________________
/**
 *Requete donnant le nombre de mentions de l'utilisateur d�sir�
 *
 *@param		int		$usID	ID de l'utilisateur d�sir�
 */
function get_Request_User_Count_Mentions($usID)
{
	return 'SELECT COUNT(meIDUser) AS mentions
		FROM mentions
		WHERE meIDUser=\''.($usID).'\'';
}

//___________________________________________________________________
/**
 *Requete donnant le nombre d'abonn�s de l'utilisateur d�sir�
 *
 *@param		int		$usID	ID de l'utilisateur d�sir�
 */
function get_Request_User_Count_Abonnes($usID)
{
	return 'SELECT COUNT(eaIDUser) AS abonne
		FROM estabonne
		WHERE eaIDUser=\''.($usID).'\'';
}

//___________________________________________________________________
/**
 *Requete donnant le nombre d'abonnements de l'utilisateur d�sir�
 *
 *@param		int		$usID	ID de l'utilisateur d�sir�
 */
function get_Request_User_Count_Abonnements($usID)
{
	return 'SELECT COUNT(eaIDAbonne) AS abonnements
		FROM estabonne
		WHERE eaIDAbonne=\''.($usID).'\'';
}

//___________________________________________________________________
/**
 *Requete v�rifiant si un utilisateur est abonn� a un autre
 *
 *@param		int		$usID	ID de l'utilisateur potentiellement abonn�
 *@param		int		$utID	ID de l'utilisateur auquel on est potentiellement abonn�
 */
function get_Request_Watching_User($usID, $utID)
{
	return 'SELECT eaIDAbonne 
		FROM estabonne
		WHERE eaIDUser=\''.$utID.'\'
		AND eaIDAbonne=\''.$usID.'\'';
}

//___________________________________________________________________
/**
 *Requete donnant les suggestions pour le aside
 *
 *@param		int		$usID	ID de l'utilisateur d�sir�
 */
function get_Request_Suggestions_Aside($usID)
{
	return "SELECT *
			FROM users
			WHERE usID IN (SELECT eaIDUser
							FROM estabonne
							WHERE eaIDAbonne IN (SELECT eaIDAbonne
											FROM estabonne
											WHERE eaIDUser='".$usID."'
											)
							)
			AND usID!='".$usID."'
			ORDER BY RAND()
			LIMIT 2";
}

?>