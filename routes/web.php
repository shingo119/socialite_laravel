<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthLoginController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', function() {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function(){
    // dd(Socialite::driver('github'));
    $githubUser = Socialite::driver('github')->user();
    // dd($githubUser);
    $user = User::updateOrCreate([
        'github_id' => $githubUser->id,
    ],[
        'name' => $githubUser->nickname,
        'email' => $githubUser->email,
        'github_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
    ]);


    Auth::login($user);

    return redirect('/dashboard');
});

Route::get('auth_passport/redirect', function() {
    return Socialite::driver('laravelpassport')->redirect();
});

Route::get('auth_passport/callback', function(){
    $passportUser = Socialite::driver('laravelpassport')->stateless()->user('id');
    // dd($passportUser);
    $user = User::updateOrCreate([
        'passport_id' => $passportUser->id,
    ],[
        'name' => $passportUser->name,
        'email' => $passportUser->email,
    ]);

    Auth::login($user);
});

Route::get('auth_line/redirect', function(){
    return Socialite::driver('line')->redirect();
});

Route::get('auth_line/callback', function(){
    $lineUser = Socialite::driver('line')->user();
    dd($lineUser);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
