<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SsoService;
use Illuminate\Http\Request;

class SsoController extends Controller
{
    protected $ssoService;

    public function __construct(SsoService $ssoService)
    {
        $this->ssoService = $ssoService;
    }

    public function redrictToSso(Request $request)
    {
        return $this->ssoService->redirectToSso($request);
    }

    public function callback(Request $request)
    {
        return $this->ssoService->callback($request);
    }

    public function logout(Request $request)
    {
        return $this->ssoService->ssoLogout($request);
    }
}
