<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Utilisateur;

class EmailAvailable extends AbstractRule
{

  public function validate($input) {

    return Utilisateur::where('mail',$input)->count()===0;
  }

}
