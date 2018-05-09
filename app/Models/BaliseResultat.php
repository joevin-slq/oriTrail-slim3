<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaliseResultat extends Model
{
  protected $table = 'BaliseResultats';
  protected $primaryKey = 'id_baliseResultat';
  public $timestamps = false;
  protected $fillable = [
    'tempsInter',
    'fk_resultat',
    'fk_baliseCourse'
  ];

  public function getBalises() {
    $balises = self::All();
    return $balises;
  }

  public function resultat() {
    return $this->belongsTo('App\Models\Resultat', 'id_resultat', 'fk_resultat');
  }

  public function balisesCourse() {
    return $this->belongsTo('App\Models\BaliseCourse', 'fk_baliseCourse', 'id_baliseCourse');
  }
}
