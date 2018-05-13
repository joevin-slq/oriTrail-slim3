<?php
namespace App\PagesControllers;

use App\Models\BaliseCourse;
use App\Models\Course;

class BaliseController {

		/**
		 * Persiste les balises dans la base de données
		 * @param Course
		 * @param nomBalise
		 * @param valeurBalise
		 * @return BaliseCourse : modèle de balise avec le contenu du QR Code en JSON
		 */
		public function createBalises(Course $course, $nomBalise, $valeurBalise) {

			// enregistre la balise d'initisalisation
			BaliseCourse::create([
				"numero" => 0,
				"nom" 	 => "Configuration",
				"qrcode" => BaliseController::getJsonInit($course, $nomBalise, $valeurBalise),
				"fk_course" => $course->id_course
			]);

			// enregistre la balise de début
			BaliseCourse::create([
				"numero" => 1,
				"nom" 	 => "Départ",
				"qrcode" => BaliseController::getJsonTimer($course->id_course, 1, "Start"),
				"fk_course" => $course->id_course
			]);

			// enregistre les balise point de contrôle
			$nbBalise = count($nomBalise);
			for($i=1 ; $i < $nbBalise; $i++) {
				$numero = $i + 1;
				BaliseCourse::create([
					"numero" => $numero,
					"nom" 	 => $nomBalise[$i],
					"valeur" => $valeurBalise[$i],
					"qrcode" => BaliseController::getJsonCheck($course->id_course, $numero, $nomBalise[$i], $valeurBalise[$i]),
					"fk_course" => $course->id_course
				]);
			}

			// enregistre la balise de fin
			BaliseCourse::create([
				"numero" => $nbBalise + 1,
				"nom" 	 => "Arrivée",
				"qrcode" => BaliseController::getJsonTimer($course->id_course, $nbBalise + 1, "Stop"),
				"fk_course" => $course->id_course
			]);

		}

	/**
	 * Retourne le contenu du QR Code d'intialisation
	 * @param Course
	 * @param nom
	 * @param valeur
	 * @return jsonConfig : objet JSON
	 */
	private function getJsonInit(Course $course, $nom, $valeur) {

		$nbBalise = count($nom);

		for($i=1 ; $i < $nbBalise; $i++) {
			$bal = array(
				'nom' => $nom[$i],
				'val' => $valeur[$i]
			);
			$jsonBalise[$i] = $bal;
		}

		if ($course->type == 'S') {
			$jsonConfig = array(
				'nom'  => $course->nom,
				'id'   => $course->id_course,
				'type' => $course->type,
				'deb'  => $course->debut,
				'fin'  => $course->fin,
				'timp' => $course->tempsImparti,
				'bals' => $jsonBalise
			);
		} else {
			$jsonConfig = array(
				'nom'  => $course->nom,
				'id'   => $course->id_course,
				'type' => $course->type,
				'deb'  => $course->debut,
				'fin'  => $course->fin,
				'pnlt' => $course->penalite,
				'bals' => $jsonBalise
			);
		}

		return json_encode($jsonConfig);
	}

	/**
	 * Crée l'objet JSON destiné au QR Code d'une balise checkpoint
	 * @param id id_course
	 * @param numero numéro de la balise
	 * @param nom nom de la balise
	 * @param valeur valeur de la balise
	 * @return tableau : objet JSON
	 */
	private function getJsonCheck($id, $numero, $nom, $valeur) {

		$tableau = array(
			'id_course' => $id,
			'num' => $numero,
			'nom' => $nom,
			'val' => $valeur
		);

		return json_encode($tableau);
	}

	/**
	 * Crée l'objet JSON destiné au QR Code d'une balise de début ou de fin
	 * @param id id_course
	 * @param numero numéro de la balise
	 * @param nom nom de la balise
	 * @return tableau : objet JSON
	 */
	private function getJsonTimer($id, $numero, $nom) {

		$tableau = array(
			'id_course' => $id,
			'num' => $numero,
			'nom' => $nom
		);

		return json_encode($tableau);
	}

}
