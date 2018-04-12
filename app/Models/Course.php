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
    'prive',
    'type',
    'debut',
    'fin',
    'tempsImparti',
    'penalite',
    'fk_user',
    'fk_lieu'
  ];

  public function getCourses() {
    $courses = self::All();
    return $courses;
  }

  public function lieu() {
    return $this->hasOne('App\Models\Lieu', 'id_lieu', 'fk_lieu');
  }

}
