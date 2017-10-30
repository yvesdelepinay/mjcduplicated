<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
* Class Ferie
*
* Return true if the day is public holiday, saturday, sunday or during holidays
*@Route("/ferie/{timestamp}")
*/
class FerieController extends Controller
{
  public function estFerie( $timestamp, $weekend=false )
{
  // Initialisation de la date de début
  $jour = date("d", $timestamp);
  $mois = date("m", $timestamp);
  $annee = date("Y", $timestamp);
	// echo $jour." ";
	// echo $mois ." ";
	// echo $annee." ";
	// echo $timestamp;
	// echo "<br>";

  // Vérification parmis les jours férié fixes
  if( ($jour == 1 && $mois == 1) ||		//  1er Janvier 	(Jour de l'an)
    ($jour == 1 && $mois == 5) || 		//  1er Mai 		(Fête du travail)
    ($jour == 8 && $mois == 5) || 		//  8   Mai 		(Fête de la victoire - Fin de la seconde guerre mondiale)
    ($jour == 14 && $mois == 7) || 		// 14   Juillet 	(Fête nationale)
    ($jour == 15 && $mois == 8) || 		// 15   Aout 	(Assomption)
    ($jour == 1 && $mois == 11) || 		//  1er Novembre 	(Toussain)
    ($jour == 11 && $mois == 11) || 	// 11   Novembre 	(Armistice 1918)
    ($jour == 25 && $mois == 12) ) 		// 25   Décembre 	(Noël)
  {
    return true;
  }

  // Si le week-end doit être considéré comme jour férié
  if( $weekend !== false )
  {
    // Calul des samedis et dimanches
    $jour_julien 	= unixtojd($timestamp);
    $jour_semaine 	= jddayofweek($jour_julien, 0);
    //Samedi (6) et dimanche (0)
    if($jour_semaine == 0 || $jour_semaine == 6)
    {
      return true;
    }
  }

  // Calcul du jour de pâques
  $date_paques = easter_date($annee);
  $jour_paques = date("d", $date_paques);
  $mois_paques = date("m", $date_paques);

  // Si le jour testé est le jour de paques
  if($jour_paques == $jour && $mois_paques == $mois)
  {
    return true;
  }

  // Calcul du jour du lundi de pâques
  $date_lundi_paques = mktime(	date("H", $date_paques),
          date("i", $date_paques),
          date("s", $date_paques),
          date("m", $date_paques),
          date("d", $date_paques) + 1,
          date("Y", $date_paques));
  $jour_lundi_paques = date("d", $date_lundi_paques);
  $mois_lundi_paques = date("m", $date_lundi_paques);

  // Si le jour testé est le jour de paques
  if($jour_lundi_paques == $jour && $mois_lundi_paques == $mois)
  {
    return true;
  }

  // Calcul du jour de l ascension (39 jours après Paques)
  $date_ascension = mktime(	date("H", $date_paques),
          date("i", $date_paques),
          date("s", $date_paques),
          date("m", $date_paques),
          date("d", $date_paques) + 39,
          date("Y", $date_paques));
  $jour_ascension = date("d", $date_ascension);
  $mois_ascension = date("m", $date_ascension);

  // Si le jour testé est le jour de l'ascension
  if($jour_ascension == $jour && $mois_ascension == $mois)
  {
    return true;
  }

  // Calcul de Pentecôte (7 semaines après Paques)
  $date_pentecote = mktime(	date("H", $date_paques),
          date("i", $date_paques),
          date("s", $date_paques),
          date("m", $date_paques),
          date("d", $date_paques) + 49,
          date("Y", $date_paques));
  $jour_pentecote = date("d", $date_pentecote);
  $mois_pentecote = date("m", $date_pentecote);

  // Si le jour testé est le jour de la pentecote
  if($jour_pentecote == $jour && $mois_pentecote == $mois)
  {
    return true;
  }

  // Calcul du lundi de Pentecôte (Lendemain de paques)
  $date_lundi_pentecote = mktime(	date("H", $date_pentecote),
            date("i", $date_pentecote),
            date("s", $date_pentecote),
            date("m", $date_pentecote),
            date("d", $date_pentecote) + 1,
            date("Y", $date_pentecote));
  $jour_lundi_pentecote = date("d", $date_lundi_pentecote);
  $mois_lundi_pentecote = date("m", $date_lundi_pentecote);

  // Si le jour testé est le jour de la pentecote
  if($jour_lundi_pentecote == $jour && $mois_lundi_pentecote == $mois)
  {
    return true;
  }

	//Toussaint 2017 Zone C du 21 octobre au 6 novembre 2017
	$debutToussaint = 1508536800;
	$finToussain = 1509922800;
	//Si le jour testé est compris entre la date de début et la date de fin de la Toussaint
	if($timestamp > $debutToussaint && $timestamp < $finToussain)
	{
		// echo "Vavances de la Toussaint en Zone A, B et C";
		return true;
	}

//Noël 2017 du 23 décembre 2017 au 8 janvier 2018
	$debutNoel = 1513983600;
	$finNoel = 1515366000;

//Si le jour testé est compris entre la date de début et la date de fin des vacances de Noël
	if($timestamp > $debutNoel && $timestamp < $finNoel)
	{
		echo "Vavances de Noël en Zone A, B et C";
		return true;
	}

//Vacances d'hiver du 17 février au 5 mars 2018 en zone C
	$debutHiver = 1518822000;
	$finHiver = 1520204400;

//Si le jour testé est compris entre la date de début et la date de fin des vacances d'hiver
	if($timestamp > $debutHiver && $timestamp < $finHiver)
	{
		// echo "Vavances d'hiver en Zone C";
		return true;
	}

//Vacances de printemps du 14 au 30 avril 2018 en zone C
	$debutPrintemps = 1523656800;
	$finPrintemps = 1525039200;

//Si le jour testé est compris entre la date de début et la date de fin des vacances d'hiver
	if($timestamp > $debutPrintemps && $timestamp < $finPrintemps)
	{
		// echo "Vavances de printemps en Zone C";
		return true;
	}

  // Si l'execution est parvenue jusque là, c'est que le jour transmis n'est pas férié
  return false;

}
}
