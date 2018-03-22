<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Utilisateur;

class LoginAvailable extends AbstractRule
{

  public function validate($input) {

    return Utilisateur::where('login',$input)->count()===0;
  }

}
