<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lieu extends Model
{
  protected $table = 'Lieux';
  protected $primaryKey = 'id_lieu';
  public $timestamps = false;
  protected $fillable = [
    'nom',
    'description',
    'longitude',
    'latitude',
    'adresse',
    'cp',
    'ville'
  ];

  public function getLieux() {
    $lieux = self::All();
    return $lieux;
  }

}
