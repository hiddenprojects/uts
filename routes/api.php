<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PatientsController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
* ------------------------------------------------------------------------
* Routes untuk Login
* ------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

/*
* ------------------------------------------------------------------------
* API Basic Routes untuk Patients
* ------------------------------------------------------------------------
*/

Route::get('/patients', [PatientsController::class, 'index'])->middleware('auth:sanctum');

Route::post('/patients', [PatientsController::class, 'store'])->middleware('auth:sanctum');

Route::get('/patients/{id}', [PatientsController::class, 'show'])->middleware('auth:sanctum');

Route::put('/patients/{id}', [PatientsController::class, 'update'])->middleware('auth:sanctum');

Route::delete('/patients/{id}', [PatientsController::class, 'destroy'])->middleware('auth:sanctum');


/*
* ------------------------------------------------------------------------
* Resource Routes untuk Patients
* ------------------------------------------------------------------------
*/
Route::get('/patients/search/{name}', [PatientsController::class, 'search'])->middleware('auth:sanctum');

Route::get('/patients/status/{name}', [PatientsController::class, 'status'])->middleware('auth:sanctum');

