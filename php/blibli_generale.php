<?php

//_______________________________________________________________________________________________________
/*
*Foction permettant de creer une ligne de tableau html compos�e d'une partie gauche et une partie droite
*
*@return	string	la chaine de caract�re codant la ligne de tableau html d�sir�e
*/
function pb_form_Ligne($left, $right, $rightCssClass)
{
	return "<tr><td class=\"alignDroit\">".$left."</td><td class=\"".$rightCssClass."\">".$right."</td></tr>";
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant de creer un input en html
*
*@param	string	$type			type d'input
*@param string	$name			nom de l'input
*@param	string	$default_value		valeur de d�faut d�sir�e
*@param int		$size			taille maximum d�sir�e pour la valeur
*
*@return	string	code html de l'input d�sir�
*/
function pb_form_input($type, $name, $default_value, $maxLength, $size, $cssID, $cssClass)
{
	return "<input type=\"".$type."\" id=\"".$cssID."\" class=\"".$cssClass."\" maxlength=\"".$maxLength."\" size=\"".$size."\" name=\"".$name."\" value=\"".$default_value."\">";
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant d'afficher un select de date
*
*@param	string	$name			nom du select
*@param	string	$default_day		jour pre-selctionn� (si zero on prendra le jour local)
*@param	string	$default_month	mois pre-selctionn� (si zero on prendra le mois local)
*@param	string	$default_year		ann�e pre-selctionn� (si zero on prendra l'ann�e local)
*/
function pb_form_date($name, $default_day, $default_month, $default_year)
{
	$localtime=localtime(time(), true);
	
	if ($default_day==0)
	{
		$default_day=($localtime['tm_mday']);
	}
	if ($default_month==0)
	{
		$default_month=($localtime['tm_mon']+1);
	}
	if ($default_year==0)
	{
		$default_year=($localtime['tm_year']+1900);
	}

	echo '<select name='.$name.'_j>';
	for ($i=1; $i<=31; $i=$i+1)
	{
		echo '<option value='.$i.' ';
		if ($i==$default_day)
		{
			echo 'selected';
		}
		echo '>'.$i.'</option>';
	}
	
	echo '</select><select name='.$name.'_m>';
	for ($i=1; $i<=12; $i=$i+1)
	{
		echo '<option value='.$i.' ';
		if ($i==$default_month)
		{
			echo 'selected';
		}
		echo '>'.$i.'</option>';
	}
	
	echo '</select><select name='.$name.'_a>';
	for ($i=0; $i<=99; $i=$i+1)
	{
		echo '<option value='.(($localtime['tm_year']+1900)-$i).' ';
		if (($localtime['tm_year']+1900)-$i==$default_year)
		{
			echo 'selected';
		}		
		echo '>'.(($localtime['tm_year']+1900)-$i).'</option>';
	}
}

//_______________________________________________________________________________________________________
/*
*Fonction permettant de savoir si la valeur renseign� existe ou non
*
*@param	string	$value			valeur que l'on souhaite v�rifier
*
*@return	retourne la valeur ou "Non renseign�"
*/
function est_renseigne($value)
{
	$return="";
	if(strlen($value)==0)
	{
		$return=$return.'<td>Non renseign&eacute;</td>';
	}
	else
	{
		$return=$return.'<td>'.$value.'</td>';
	}
	return $return;
}

?>