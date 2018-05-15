<?php

namespace App\Auth;

use \Firebase\JWT\JWT;
use App\Models\Utilisateur;

class ApiAuth
{
// vérifie le couple user/pass
  public function attempt($login, $password)
  {
    $user = Utilisateur::where('login', $login)->first();
    $mail = Utilisateur::where('mail', $login)->first();

    if(isset($user)) {
      if(password_verify($password, $user->password)) {
        return $user->login;
      }
    }

    if(isset($mail)) {
      if(password_verify($password, $mail->password)) {
        return $user->login;
      }
    }
    return false;
  }

// génère un token
  public function createToken($login)
  {
		$key = getenv('JWT_SECRET');
    $future = new \DateTime("now +60 minutes");
    $payload = [
        "future" => $future->getTimestamp(),
        "user"   => $login
    ];

		$token = JWT::encode($payload, $key);

    return $token;
  }

// vérifie la validité du token
  public function checkToken($token)
  {
    $key = getenv('JWT_SECRET');
    try {
      $payload = JWT::decode($token, $key, array('HS256'));
    } catch (\Exception $e) {
      return false;
    }

    $now    = new \DateTime();
    $future = \DateTime::createFromFormat('U', $payload->future);

    if($now > $future) {
      return false;
    }

    $user = Utilisateur::where('login', $payload->user)->first();

    return $user ? $user : false;
  }

// renouvelle le token
  public function renewToken($token)
  {
    $key = getenv('JWT_SECRET');
    try {
      $payload = JWT::decode($token, $key, array('HS256'));
    } catch (\Exception $e) {
      return false;
    }

    $future = new \DateTime("now +60 minutes");
    $payload->future = $future->getTimestamp();

    return JWT::encode($payload, $key);
  }

// récupère le nom d'utilisateur
  public function getUser($token)
  {
    $key = getenv('JWT_SECRET');
    try {
      $payload = JWT::decode($token, $key, array('HS256'));
    } catch (\Exception $e) {
      return false;
    }

    return Utilisateur::where('login', $payload->user)->first();
  }
}
