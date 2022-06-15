<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\SettingsController;
use App\Models\State;

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

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/login', [AuthenticatedSessionController::class, 'create']);
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::group(['prefix' => 'dashboard'], function (){
	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
	
	// PERSONNEL
	Route::group(['prefix' => 'personnel'], function () {
		Route::get('/', [PersonnelController::class, 'index'])->name('personnel_all');
		Route::get('/new', [PersonnelController::class, 'create'])->name('file_create');
		Route::get('/all', [PersonnelController::class, 'index'])->name('personnel_all');
		Route::get('/get_all', [PersonnelController::class, 'get_all'])->name('personnel_get_all');
		Route::get('/get_outofservice', [PersonnelController::class, 'get_outofservice'])->name('get_outofservice');
		Route::get('/{user}', [PersonnelController::class, 'show'])->name('personnel_show');
		Route::get('/{user}/ros', [PersonnelController::class, 'ros'])->name('personnel_ros');
		Route::post('/store', [PersonnelController::class, 'store'])->name('store_file');
		Route::get('{user}/edit/', [PersonnelController::class, 'edit'])->name('personnel_edit');
		
		Route::post('{user}/change_password/', [PersonnelController::class, 'change_password'])->name('personnel_change_password');
		// Route::post('{user}/delete/', [PersonnelController::class, 'change_password'])->name('personnel_delete');
		
		Route::put('/{user}/update', [PersonnelController::class, 'update'])->name('personnel_update');
		Route::post('/delete', [PersonnelController::class, 'destroy'])->name('personnel_delete');
		
		Route::group(['prefix' => 'import'], function () {
			Route::get('/data', [PersonnelController::class, 'import_data'])->name('import_data');
			Route::post('/users/store', [PersonnelController::class, 'store_imported_users'])->name('store_imported_users');
		});



		Route::group(['prefix' => 'file'], function () {
			Route::post('/upload/{user}', [PersonnelController::class, 'upload_file'])->name('personnel_upload_file');
			Route::delete('/document/{document}/delete', [PersonnelController::class, 'destroyDocument'])->name('deletePersonnelDocument');
		});
		


	});
	
	// FILE
	Route::group(['prefix' => 'files'], function () {
		Route::get('/', [FileController::class, 'index'])->name('file_personnel');
		Route::get('/new', [FileController::class, 'create'])->name('file_create');
		Route::get('/personnel', [FileController::class, 'index'])->name('file_personnel');
		Route::get('/get_personnel', [FileController::class, 'get_personnel'])->name('file_get_personnel');
		Route::get('/{user}', [FileController::class, 'show'])->name('file_show');
		Route::post('/store', [FileController::class, 'store'])->name('store_file');
		Route::get('{user}/edit/', [FileController::class, 'edit'])->name('file_edit');
		
		
		Route::put('/{user}/update', [FileController::class, 'update'])->name('file_update');
		Route::post('/delete', [FileController::class, 'destroy'])->name('file_delete');

		Route::group(['prefix' => 'file'], function () {
			Route::post('/upload/{user}', [FileController::class, 'upload_file'])->name('file_upload_file');
			Route::delete('/document/{document}/delete', [FileController::class, 'destroyDocument'])->name('deleteFileDocument');
		});

	});

	// CORRESPONDENCE
	Route::group(['prefix' => 'correspondence'], function () {
		Route::get('/new', [CorrespondenceController::class, 'create'])->name('correspondence_create');
		Route::post('/store', [CorrespondenceController::class, 'store'])->name('correspondence_store');
		Route::get('/edit/{correspondence}', [CorrespondenceController::class, 'edit'])->name('correspondence_edit');
		Route::post('/update/{correspondence}', [CorrespondenceController::class, 'update'])->name('correspondence_update');
		Route::get('/delete_incoming/{correspondence}', [CorrespondenceController::class, 'destroy'])->name('correspondence_delete');
		
		Route::get('/incoming', [CorrespondenceController::class, 'incoming'])->name('correspondence_incoming');
		Route::get('/get_incoming', [CorrespondenceController::class, 'get_incoming'])->name('correspondence_get_incoming');
		
		Route::get('/outgoing', [CorrespondenceController::class, 'outgoing'])->name('correspondence_outgoing');
		Route::get('/get_outgoing', [CorrespondenceController::class, 'get_outgoing'])->name('correspondence_get_outgoing');
	});

	// SETTINGS
	Route::group(['prefix' => 'settings'], function () {
		Route::get('/', [SettingsController::class, 'index'])->name('app_settings');
		Route::post('/roles/add/', [SettingsController::class, 'add_role'])->name('app_settings_add_role');
		Route::post('/roles/get/permissions', [SettingsController::class, 'get_permissions'])->name('permissions_get_from_role');
		Route::put('/privilage/{user}/update', [SettingsController::class, 'update_privilage'])->name('app_settings_update_privilage');
		Route::post('/privilage/update/', [SettingsController::class, 'asign_privilage'])->name('app_settings_assign_privilage');
	});
});


// require __DIR__.'/auth.php';

