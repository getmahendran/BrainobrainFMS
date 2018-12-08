<?php

use App\BillBook;
use App\Course;
use App\FacultyAccount;
use App\Fee;
use App\FeeType;
use App\Franchisee;
use App\QuestionPaperRequest;
use App\QuestionPaperRequestDetail;
use App\Batch;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('dashboard');
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

//Master Routes
Route::post('register', 'Auth\RegisterController@store');
Route::get('register/{id}/edit','Auth\RegisterController@edit')->name('register.edit');
Route::put('register/{id}', 'Auth\RegisterController@update')->name('register.update');
Route::get('register/all','Auth\RegisterController@index')->name('register.index');
Route::delete('register/{id}','Auth\RegisterController@destroy')->name('register.destroy');
Route::put('register/{id}/reset','Auth\RegisterController@passwordReset')->name('register.reset');

//CourseController Routes
Route::get("/course",'CourseController@create')->name("course.create");
Route::post("/course",'CourseController@store')->name("course.store");
Route::get("/course/{program_id}", "CourseController@index")->name("course.index");
Route::get("/course/{program_id}/{id}", "CourseController@show")->name("course.show");
Route::get("/course/{program_id}/{id}/edit","CourseController@edit")->name("course.edit");
Route::put("/course/{program_id}/{id}", "CourseController@update")->name("course.update");
Route::get('/course/{program_id}/get','CourseController@getCourses')->name('program.courses');

//ProgramController routes
Route::resource('/program', 'ProgramController');

//FeeController Routes
Route::resource('/fee','FeeController');

//FranchiseeController Routes
Route::resource('/franchisee','Auth\FranchiseeController');
Route::get('/franchisee/married/check','Auth\FranchiseeController@married_register')->name('franchiseeMarried.index');
Route::get('/franchisee/married/{id}','Auth\FranchiseeController@married_update')->name('franchiseeMarried.edit');

//Faculty Routes
Route::resource('faculty','Auth\FacultyController');
Route::get('search/faculty','Auth\FacultyController@searchFaculty')->name('faculty.search');
Route::post('existing/faculty/{id}','Auth\FacultyController@registerExistingFaculty')->name('faculty.existing.store');
Route::get('/faculty/married/check','Auth\FacultyController@married_register')->name('facultyMarried.index');
Route::get('faculty/married/{id}','Auth\FacultyController@married_update')->name('facultyMarried.edit');
Route::post('/faculty/updateImage/{id}', 'Auth\FacultyController@updateProfileImage')->name('faculty.updateImage');
Route::put('faculty/{id}/reset','Auth\FacultyController@passwordReset')->name('faculty.reset');

//Student Routes
Route::resource('/student','StudentController');
Route::post('/student/updateImage/{id}', 'StudentController@updateProfileImage')->name('student.updateImage');

//FeeCollection Routes
Route::get('feeCollect', 'FeeCollectionController@pay')->name('fee_collect.pay');
Route::put('feeCollect/{id}', 'FeeCollectionController@update')->name('fee_collect.update');
Route::get('feeCollect/{id}/edit','FeeCollectionController@edit')->name('fee_collect.edit');
Route::get('feeCollect/student_search','FeeCollectionController@search')->name('fee_collect.student_search');
Route::get('feeCollect/{id}','FeeCollectionController@show')->name('fee_collect.show');

//BillBook Routes
Route::get('billBook/create','BillBookController@create')->name('bill_book.create');
Route::post('billBook','BillBookController@store')->name('bill_book.store');
Route::get('billBook','BillBookController@index')->name('bill_book.index');
Route::get('billBook/{id}/edit','BillBookController@edit')->name('bill_book.edit');
Route::put('billBook/{id}','BillBookController@update')->name('bill_book.update');
Route::delete('billBook/{id}','BillBookController@destroy')->name('bill_book.delete');

//Batch Routes
Route::resource('batch','BatchController');


Route::resource("questionPaperRequest","QuestionPaperRequestController");
Route::get("questionPaperRequest/all/{franchisee_id}",'QuestionPaperRequestController@getStudents')->name("questionPaperRequest.get.franchisee");


Route::get('/currentfee/{area_id}/{fee_type_id}',function ($area_id,$fee_type_id){
    Fee::getCurrentFee($area_id,$fee_type_id);
});

Route::get('/test/',function(Illuminate\Http\Request $request) {
    return view('test');
})->name('test');

Route::get('demo',function (){
    return view('layouts.bobclassic');
});