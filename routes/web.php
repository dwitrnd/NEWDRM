<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DesignationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\EmployeeController;

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

// designation route
Route::get('/designation/create',[DesignationController::class, 'create']);
Route::post('/designation',[DesignationController::class, 'store']);
Route::get('/designation',[DesignationController::class, 'index']);
Route::get('/designation/edit/{id}',[DesignationController::class, 'edit']);
Route::put('/designation/{id}',[DesignationController::class, 'update']);
Route::delete('/designation/{id}',[DesignationController::class, 'destroy']);

// organization route
Route::get('/organization/create',[OrganizationController::class, 'create']);
Route::post('/organization',[OrganizationController::class, 'store']);
Route::get('/organization',[OrganizationController::class, 'index']);
Route::get('/organization/edit/{id}',[OrganizationController::class, 'edit']);
Route::put('/organization/{id}',[OrganizationController::class, 'update']);
Route::delete('/organization/{id}',[OrganizationController::class, 'destroy']);

// unit route
Route::get('/unit/create',[UnitController::class, 'create']);
Route::post('/unit',[UnitController::class, 'store']);
Route::get('/unit',[UnitController::class, 'index']);
Route::get('/unit/edit/{id}',[UnitController::class, 'edit']);
Route::put('/unit/{id}',[UnitController::class, 'update']);
Route::delete('/unit/{id}',[UnitController::class, 'destroy']);

// leave-type route
Route::get('/leaveType/create',[LeaveTypeController::class, 'create']);
Route::post('/leaveType',[LeaveTypeController::class, 'store']);
Route::get('/leaveType',[LeaveTypeController::class, 'index']);
Route::get('/leaveType/edit/{id}',[LeaveTypeController::class, 'edit']);
Route::put('/leaveType/{id}',[LeaveTypeController::class, 'update']);
Route::delete('/leaveType/{id}',[LeaveTypeController::class, 'destroy']);

// employee route
Route::get('/employee/create',[EmployeeController::class, 'create']);
Route::post('/employee',[EmployeeController::class, 'store']);
Route::get('/employee',[EmployeeController::class, 'index']);
Route::get('/employee/edit/{id}',[EmployeeController::class, 'edit']);
Route::put('/employee/{id}',[EmployeeController::class, 'update']);
Route::delete('/employee/{id}',[EmployeeController::class, 'destroy']);