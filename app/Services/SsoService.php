<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SsoService 
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function redirectToSso($request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $state = Str::random(40);
        // $request->session()->put('sso_state', $state); //for production
        cache()->put('sso_state_' . $state, $state, now()->addMinutes(5)); // for local testing
        $query = http_build_query([
            'client_id' => config('services.sso.client_id'),
            'redirect_uri' => config('app.url').'/callback',
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,

        ]);
        
        return redirect(config('services.sso.base_uri').'/oauth/authorize?' . $query);
    }

    public function callback($request)
    {
        $state = $request->state;
        $sessionState = cache()->pull('sso_state_' . $state); //for local only
        if (!$state || !$sessionState || $state !== $sessionState) {
            abort(403, 'Invalid state parameter.');
        }
        // $request->session()->put('sso_state', $state); //For production

        $response = Http::asForm()->post(config('services.sso.base_uri') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),  
            'redirect_uri' => config('app.url') . '/callback',
            'redirect_uri' => config('app.url'). '/callback',
            'code' => $request->code,
        ]);

        if (!$response->successful()) {
            abort(401, 'Failed to get access token from SSO');
        }

        $request->session()->put([
            'access_token' => $response->json()['access_token'],
            'refresh_token' => $response->json()['refresh_token'],
        ]);

        $userResponse = Http::withToken($response->json()['access_token'])->get(config('services.sso.base_uri') . '/api/user');

        if (!$userResponse->successful()) {
            abort(401, 'Unable to fetch user info');
        }

        $user = $this->userRepo->updateOrCreateFromSso($userResponse->json());  

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function ssoLogout($request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(config('services.sso.base_uri') . '/logout?redirect_uri=' . urlencode(config('app.url')));
    }
}