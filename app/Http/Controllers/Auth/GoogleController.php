<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Socialite;
use Auth;
use Exception;
use App\Models\User;

class GoogleController extends Controller
{
  /**
   * Create a new controller instance
   * 
   * @return void
   */
  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    try {
      $user = Socialite::driver('google')->user();

      $findUser = User::where('google_id', $user->id)->first();
      
      if (!$findUser) {
        $findUser = User::create([
          'name' => $user->name,
          'email' => $user->email,
          'google_id' => $user->id,
          'password' => encrypt('google')
        ]);
      }

      Auth::login($findUser);
      return redirect('/home');
    } catch (Exception $e) {
      dd($e->getMessage());
    }
  }
}