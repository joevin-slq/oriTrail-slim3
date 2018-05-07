<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaliseCourse extends Model
{
  protected $table = 'BaliseCourses';
  protected $primaryKey = 'id_baliseCourse';
  public $timestamps = false;
  protected $fillable = [
    'nom',
    'numero',
    'valeur',
    'longitude',
    'latitude',
    'qrcode',
    'fk_course'
  ];

  public function getBalises() {
    $balises = self::All();
    return $balises;
  }

  public function course() {
    return $this->belongsTo('App\Models\Course', 'id_course', 'fk_course');
  }

  public function balisesResultat() {
    return $this->hasMany('App\Models\BaliseResultat', 'fk_baliseCourse');
  }
}
