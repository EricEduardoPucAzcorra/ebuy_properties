<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

   public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        $user = User::where('email', $googleUser->email)->first();

        if ($user && !$user->is_active) {
            return redirect('/login')
                ->withErrors(['email' => 'Tu cuenta está desactivada']);
        }

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt(uniqid()),
                'is_active' => true,
                'profile_url' => $googleUser->avatar
            ]);
        }

        Auth::login($user);

        return redirect('/home');
    }
}
