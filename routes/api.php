<?php

use Illuminate\Http\Request;

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

/* ADMIN ROUTE GROUP */
Route::get("/getAllGroups", "Admin\groupsController@getAllGroups");
Route::get("/fetchStudentForGroup", "Admin\groupsController@get_students");
Route::put('/saveUpdateGroup',"Admin\groupsController@saveUpdateGroup");
Route::post('/add_group', 'Admin\groupsController@add_group');


/* ADMIN ROUTE STUDENT */
Route::get("/getAllStudents", "Admin\studentsController@show_students");
Route::post('/addStudent', "Admin\studentsController@add_student");