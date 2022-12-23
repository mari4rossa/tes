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

Route::get('/', function () {
    return view('welcome');
});

// HR
//export-import
Route::get('/department/export','HR\departmentController@exportDepartmentToExcel')->name('department.export');
Route::post('/department/import','HR\departmentController@importDepartmentFromExcel')->name('department.import');

Route::post('/position/import','HR\positionController@importPositionFromExcel')->name('position.import');
Route::get('/position/export','HR\positionController@exportPositionToExcel')->name('position.export');

Route::post('/employee/import','HR\employeeController@importEmployeeFromExcel')->name('employee.import');
Route::get('/employee/export','HR\employeeController@exportEmployeeToExcel')->name('employee.export');

Route::get('/mutation/export','HR\mutationController@exportMutationToExcel')->name('mutation.export');
//datatable
Route::get('/department/department-table','HR\departmentController@departmentTable')->name('department.department-table');
Route::get('/position/position-table','HR\positionController@positionTable')->name('position.position-table');
Route::get('/employee/employee-table','HR\employeeController@employeeTable')->name('employee.employee-table');
Route::get('/mutation/mutation-table','HR\mutationController@mutationTable')->name('mutation.mutation-table');
//resource
Route::resource('/department', 'HR\departmentController');
Route::resource('/position', 'HR\positionController');
Route::resource('/employee', 'HR\employeeController');
Route::resource('/mutation', 'HR\mutationController');

// Training
//export-import
Route::get('/trainer/export','Training\trainerController@exportTrainerToExcel')->name('trainer.export');
Route::post('/trainer/import','Training\trainerController@importTrainerFromExcel')->name('trainer.import');
Route::get('/training/export','Training\trainingController@exportTrainingToExcel')->name('training.export');
Route::post('/training/import','Training\trainingController@importTrainingFromExcel')->name('training.import');
Route::get('/training-history/export','Training\trainingHistoryController@exportTrainingHistoryToExcel')->name('training-history.export');
//datatable
Route::get('/trainer/trainer-table','Training\trainerController@trainerTable')->name('trainer.trainer-table');
Route::get('/training/training-table','Training\trainingController@trainingTable')->name('training.training-table');
Route::get('/training-history/training-history-table','Training\trainingHistoryController@trainingHistoryTable')->name('training-history.training-history-table');
//resource
Route::resource('/trainer', 'Training\trainerController');
Route::resource('/training', 'Training\trainingController');
Route::resource('/training-history', 'Training\trainingHistoryController');

//employee profile datatable
Route::get('/employee-profile/employee-table/','HR\employeeProfileController@employeeTable')->name('employee-profile.employee-table');
Route::get('/employee-profile/mutation-table/','HR\employeeProfileController@mutationTable')->name('employee-profile.mutation-table');
Route::get('/employee-profile/training-history-table/','HR\employeeProfileController@trainingHistoryTable')->name('employee-profile.training-history-table');
//employee profile
Route::get('employee-profile/{nik}', 'HR\employeeProfileController@tampilan');
Route::resource('/employee-profile', 'HR\employeeProfileController');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
