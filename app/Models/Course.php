<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
  protected $table = 'Courses';
  protected $primaryKey = 'id_course';
  public $timestamps = false;
  protected $fillable = [
    'nom',
    'description',
    'prive',
    'type',
    'debut',
    'fin',
    'tempsImparti',
    'penalite',
    'fk_user'
  ];

  public function getCourses() {
    $courses = self::All();
    return $courses;
  }

  public function balisesCourse() {
    return $this->hasMany('App\Models\BaliseCourse', 'fk_course');
  }

  public function resultats() {
    return $this->hasMany('App\Models\Resultat', 'fk_resultat');
  }

  public function estInstalle() {
    $balisesCourse = $this->balisesCourse()->get();

    // parcours des balises
    foreach ($balisesCourse as $balise) {
      // on passe la première balise
      if($balise['numero'] == 0) { continue; }

      if(!isset($balise['longitude'])) { return false; }
      if(!isset($balise['latitude']))  { return false; }
    }

    return true;
  }
}
