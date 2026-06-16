<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider) {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where("{$provider}_id", $socialUser->getId())->first();
      
        if (!$user) {
            
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $user->update(["{$provider}_id" => $socialUser->getId()]);
            } else {      
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    "{$provider}_id" => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'password' => Str::random(12)
                ]);
            }
        }
      
        Auth::login($user);
      
        return redirect('/');          
    }
}
