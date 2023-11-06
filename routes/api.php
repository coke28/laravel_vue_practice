<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\CrmLogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
| - Dex
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'form'], function () {
    Route::post('add', [FormController::class, 'formAdd'])->name('form.add');
    Route::get('formTB', [FormController::class, 'formTB'])->name('form.tb');
});
Route::group(['prefix' => 'crmLog'], function () {
    //Form Routes
    Route::get('table', [CrmLogController::class, 'crmLogTB'])->name('crmLog.table');
    Route::post('delete/{crmLog}', [CrmLogController::class, 'crmLogDelete'])->name('crmLog.delete');
  });
