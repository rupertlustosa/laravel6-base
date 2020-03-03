<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Socialite;

class LoginSocialController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirect($provider)
    {

        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    private function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            abort(404);
        }
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function callback($provider)
    {

        $this->validateProvider($provider);

        $userSocial = Socialite::driver($provider)->stateless()->user();

        $user = User::where(['email' => $userSocial->getEmail()])->first();

        if ($user) {
            Auth::login($user);
            return redirect('/');
        } else {
            $user = User::create([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'image' => '',#$userSocial->getAvatar(),
                'provider_id' => $userSocial->getId(),
                'provider' => $provider,
            ]);
            return redirect()->route('home');
        }

        // $user->token;

        // OAuth Two Providers
        /*$token = $user->token;
        $refreshToken = $user->refreshToken; // not always provided
        $expiresIn = $user->expiresIn;

        // OAuth One Providers
        $token = $user->token;
        $tokenSecret = $user->tokenSecret;

        // All Providers
        $data = ['tokenSecret' => $tokenSecret,
        'token' => $token,
        'user_getId' => $user->getId(),
        'user_getNickname' => $user->getNickname(),
        'user_getName' => $user->getName(),
        'user_getEmail' => $user->getEmail(),
        'user_getAvatar' => $user->getAvatar(),];
        */
    }
}
