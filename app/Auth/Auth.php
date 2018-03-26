<?php

namespace App\Auth;
use App\Models\Utilisateur;

class Auth
{
  public function user() {
    return ($this->check()) ? Utilisateur::find($_SESSION['user']) : null;
  }

  public function check() {
    return isset($_SESSION['user']);
  }

  public function attempt($login, $password)
  {
    $user = Utilisateur::where('login', $login)->first();
    $mail = Utilisateur::where('mail', $login)->first();

    if(isset($user)) {
      if(password_verify($password, $user->password)) {
        $_SESSION['user'] = $user->id_user;
        return true;
      }
    }

    if(isset($mail)) {
      if(password_verify($password, $mail->password)) {
        $_SESSION['user'] = $mail->id_user;
        return true;
      }
    }
    return false;
  }

  public function logout()
  {
    unset($_SESSION['user']);
  }
}
