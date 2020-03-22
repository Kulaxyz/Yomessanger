<?php

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

use Nexmo\Laravel\Facade\Nexmo;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'Api\ChatController@index')->name('home');

Route::get('/test', function () {
//    dd(\App\Chat::find(1));
//    $user = \App\User::find(4);
    $user = \App\User::find(5);
    return $user;
});

Route::get('sms', function () {

    try {
        $verification = Nexmo::verify()->check('31feb9c27c414a8085eddc8bd97a65c4', '8568');
        echo "Verification was successful (status: " . $verification['status'] . ")\n";
    } catch (Exception $e) {
        $verification = $e->getEntity();
        echo "Verification failed with status " . $verification['status']
            . " and error text \"" . $verification['error_text'] . "\"\n";
    }
});


