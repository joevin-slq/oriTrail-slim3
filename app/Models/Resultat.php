<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
  protected $table = 'Resultats';
  protected $primaryKey = 'id_resultat';
  public $timestamps = false;
  protected $fillable = [
    'debut',
    'fin',
    'score',
    'fk_user',
    'fk_course'
  ];

  public function getResultats() {
    $resultat = self::All();
    return $resultat;
  }

  public function balisesResultat() {
    return $this->hasMany('App\Models\BaliseResultat', 'fk_resultat');
  }

  public function course() {
    return $this->belongsTo('App\Models\Course', 'fk_course', 'id_course');
  }

  public function user() {
    return $this->belongsTo('App\Models\Utilisateur', 'fk_user', 'id_user');
  }
}
