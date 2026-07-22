<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

Route::get('/certificate/{certificateId}/pdf/{logoOption}', [CertificateController::class, 'showPdf'])->name('certificate.pdf');

Route::get('/', function () {
    return redirect()->route('login');
})->middleware(['web', 'guest'])->name('home');

//Before Auth Regis&Login//
Route::get('/dashboard', function () {
    return view('app');
})->middleware('auth')->name('dashboard');

Route::view('/courses', 'page.auth-page.course-index-page')
    ->middleware(['auth'])
    ->name('courses');

Route::get('/courses/{id}', function ($id) {
    return view('app', ['id' => $id]);
})->middleware(['auth'])->name('course.detail');
//Before Auth Regis&Login//


//Student Zone//
Route::view('/student/my-courses', 'page.student-page.my-courses-page')
    ->middleware(['auth'])
    ->name('my-courses');

Route::view('/student/my-certificates', 'page.student-page.my-certificates-page')
    ->middleware(['auth'])
    ->name('my-certificates');

Route::get('/student/certificate/{id}', function ($id) {
    return view('app', ['id' => $id]);
})->middleware(['auth'])->name('view-certificate-page-student');

Route::view('/student/my-lowallet', 'page.student-page.l-o-wallet-page-student')
    ->middleware(['auth'])
    ->name('my-lowallet');
//Student Zone//


//General Zone//
Route::view('/general/my-courses', 'page.general-page.my-courses-page-general')
    ->middleware(['auth'])
    ->name('my-courses-general');

Route::view('/general/my-certificates', 'page.general-page.my-certificates-page-general')
    ->middleware(['auth'])
    ->name('my-certificates-general');

Route::get('/general/certificate/{id}', function ($id) {
    return view('app', ['id' => $id]);
})->middleware(['auth'])->name('view-certificate-page-general');

Route::view('/general/my-lowallet', 'page.general-page.l-o-wallet-page-general')
    ->middleware(['auth'])
    ->name('my-lowallet-general');
//General Zone//


//Insider Zone//
Route::view('/insider/my-courses', 'page.insider-page.my-courses-page-insider')
    ->middleware(['auth'])
    ->name('my-courses-insider');

Route::view('/insider/my-certificates', 'page.insider-page.my-certificates-page-insider')
    ->middleware(['auth'])
    ->name('my-certificates-insider');

Route::get('/insider/certificate/{id}', function ($id) {
    return view('app', ['id' => $id]);
})->middleware(['auth'])->name('view-certificate-page-insider');

Route::view('/insider/my-lowallet', 'page.insider-page.l-o-wallet-page-insider')
    ->middleware(['auth'])
    ->name('my-lowallet-insider');
//Insider Zone//


//Instructor Zone//
Route::view('/instructor/courses/create', 'page.instructor-page.create-course-page')
    ->middleware(['auth'])
    ->name('instructor.courses.create');

Route::get('/instructor/courses/{id}', function ($id) {
    return view('app', ['id' => $id]);
})->middleware(['auth'])->name('instructor.course.detail');

Route::get('/instructor/courses/edit/{id}', function ($id) {
    return view('app', ['id' => $id]);
})->middleware(['auth'])->name('instructor.course.edit');

Route::get('/instructor/courses/course_completions/{id}', function ($id) {
    return view('app', ['id' => $id]);
})->middleware(['auth'])->name('instructor.course_completions');
//Instructor Zone//


//Officer Zone//
Route::view('/officer/certificate-requests', 'page.officer-page.certificate-requests-page-officer')
    ->middleware(['auth'])
    ->name('officer.certificate-requests');
Route::view('/officer/signature-management', 'page.officer-page.signature-management-page-officer')
    ->middleware(['auth'])
    ->name('officer.signature-management');
//Officer Zone//


Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*')->middleware('web')->name('home');



require __DIR__ . '/auth.php';
