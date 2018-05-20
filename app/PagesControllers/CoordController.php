<?php
namespace App\PagesControllers;

use App\Models\Resultat;

use Ballen\Distical\Calculator as DistanceCalculator;
use Ballen\Distical\Entities\LatLong;

class CoordController {

	/**
	 * Vérifie qu'une course est valide à partir de Resultat
	 * @param Resultat : objet Resultat
	 * @param seuil : distance (entier) à partir du quel une course est invalidée
	 * @return boolean : true si course valide
	 */
	public function validerResultat($resultat, $seuil) {
		$balisesResultat = $resultat->balisesResultat;

    // parcours des balises
    foreach ($balisesResultat as $balise) {
      // on passe la balise de configuration
      if($balise->balisesCourse->numero == 0) { continue; }

			if($balise->distance > $seuil) {
				return false;
			}
    }

		return true;
	}

	/**
	 * Calcule la distance entre des coordonnées à partir de Resultat
	 * @param Resultat : objet Resultat
	 * @return Resultat : Resultat comportant une colonne balisesResultat modifiée
	 */
	public function calculResultat($resultat) {
		$balisesResultat = $resultat->balisesResultat()->get();

		$balisesResultat = CoordController::calculBalises($balisesResultat);

		$resultat->balisesResultat = $balisesResultat;
		return $resultat;
	}


	/**
	 * Calcule la distance entre des coordonnées à partir de BaliseResultat
	 * @param balisesResultat : objet BaliseResultat
	 * @return BaliseResultat : BaliseResultat comportant une colonne "distance"
	 */
	private function calculBalises($balisesResultat) {

    // parcours des balises
    foreach ($balisesResultat as $balise) {
      // on passe la première balise (balise de configuration)
      if($balise->balisesCourse->numero == 0) { continue; }

			// calcule la distance entre les deux coordonnées
			$distance = CoordController::getDistance(
				$balise->latitude,
				$balise->longitude,
				$balise->balisesCourse->latitude,
				$balise->balisesCourse->longitude
			);

			// intègre les coordonnées à l'objet $balise
			$balise->distance = $distance;
    }

		return $balisesResultat;
	}

	/**
	 * Vérifie que les balises ont été scannées au bon endroit
	 * @param latA latitude de l'emplacement A
	 * @param lngA longitude de l'emplacement A
	 * @param latB latitude de l'emplacement B
	 * @param lngB longitude de l'emplacement B
	 * @return distance : distance en mètres
	 */
	private function getDistance($latA, $lngA, $latB, $lngB) {

		// Définit les coordonnées dans des objets LatLong
		$A = new LatLong($latA, $lngA);
		$B = new LatLong($latB, $lngB);

		// Récupère la distance entre ces deux coordonnées
		try {
			$distance = new DistanceCalculator($A, $B);

			// Calcule la distance en kilomètres
			$distance = $distance->get()->asKilometres();

			// Convertit en mètres
			$distance = $distance * 1000;

			// Arrondit à deux décimales
			$distance = round($distance, 2);

		} catch (\InvalidArgumentException $exception) {
			$distance = 0;
		}

		return $distance;
	}
}
