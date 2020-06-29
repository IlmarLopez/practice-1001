<?php
class Security
{
  public static function generateToken()
  {
    $today = date_create(); // today's date
    $token = ''; // token
    // 0 arguments : token is date only
    if (func_num_args() == 0)
      $token = sha1(date_format($today, 'Ymd'));
    // 1 argument : token us userid and date
    if (func_num_args() == 1) {
      $user = func_get_arg(0); // user
      $token = sha1($user . (date_format($today, 'Ymd')));
    }
    return $token; // return token
  }

  public static function userBelongsToRole($username, $roles)
  {
    return true;
  }
}
