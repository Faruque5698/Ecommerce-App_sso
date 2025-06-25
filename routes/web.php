<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => '0197a60c-11df-70a7-85e9-c608dbe1113e', // যেটা তুমি তৈরি করেছিলে
        'redirect_uri' => 'http://127.0.0.1:8080/callback',
        'response_type' => 'code', // Authorization Code flow
        'scope' => '', // অনুমতি চাই
        'state' => $state,
        // 'prompt' => 'consent', // ঐচ্ছিক: ব্যাখ্যা নিচে
    ]);

    return redirect('http://127.0.0.1:8000/oauth/authorize?' . $query);
})->name('login');

Route::get('/callback', function (Illuminate\Http\Request $request) {
    // Handle the callback logic here
    return response()->json($request->all());
});
