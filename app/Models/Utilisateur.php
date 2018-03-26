<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
  protected $table = 'Utilisateurs';
  protected $primaryKey = 'id_user';
  public $timestamps = false;
  protected $fillable = [
    'login',
    'password',
    'nom',
    'prenom',
    'mail',
    'dateNaissance',
    'sexe'
  ];

  public function getUtilisateurs() {
    $users = self::All();
    return $users;
  }

}
