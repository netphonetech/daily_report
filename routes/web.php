<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// reports
Route::get('reports/list', 'ReportController@index')->name('list-reports');
Route::post('report/add', 'ReportController@store')->name('store-report');
Route::post('report/update', 'ReportController@update')->name('report-update');
Route::post('report/remove', 'ReportController@destroy')->name('report-destroy');

// users
Route::get('users/list', 'UserContoller@index')->name('list-users');
Route::post('user/add', 'UserContoller@store')->name('store-user');
Route::post('user/update', 'UserContoller@update')->name('update-user');
Route::post('user/remove', 'UserContoller@destroy')->name('destroy-user');

//projects
Route::get('projects/list', 'ProjectController@index')->name('list-projects');
Route::get('project/show', 'ProjectController@show')->name('show-project');
Route::post('project/add', 'ProjectController@store')->name('store-project');
Route::post('project/update', 'ProjectController@update')->name('update-project');
Route::post('project/remove', 'ProjectController@destroy')->name('destroy-project');
Route::post('project/mark-complete', 'ProjectController@complete')->name('mark-complete');


//projects participant
Route::get('project/participants/list', 'ProjectController@index')->name('list-project-participants');
Route::post('project/participant/add', 'ProjectController@storeParticipant')->name('store-project-participant');
Route::post('project/participant/update', 'ProjectController@updateParticipant')->name('update-project-participant');
Route::post('project/participant/remove', 'ProjectController@destroyParticipant')->name('destroy-project-participant');
