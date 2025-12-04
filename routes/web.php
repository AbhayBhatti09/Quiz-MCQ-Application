<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\McqController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AdminQuizController;
use App\Http\Controllers\QuizSettingController;
use App\Http\Controllers\QuizCategoryController;
use App\Http\Controllers\UserWiseQuizController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
     return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Admin 
     Route::middleware(['admin'])->group(function () {
        // MCQ Module 
        Route::resource('mcq', McqController::class);
        //MCQ category Module
        Route::resource('category',QuizCategoryController::class);
        //Quiz Setting Module
       Route::prefix('quiz-setting')->group(function(){
                Route::get('/', [QuizSettingController::class,'index'])->name('quiz.setting.index');
                Route::get('/create', [QuizSettingController::class, 'create'])->name('quiz.setting.create');
                Route::post('/store', [QuizSettingController::class, 'store'])->name('quiz.setting.store');

                // Dynamic edit/view using {quiz} ID
                Route::get('/edit/{quiz}', [QuizSettingController::class, 'edit'])->name('quiz.setting.edit');
                Route::put('/update/{quiz}', [QuizSettingController::class, 'update'])->name('quiz.setting.update');

                Route::get('/view/{quiz}', [QuizSettingController::class, 'view'])->name('quiz.setting.view');
                Route::get('/listing', [QuizSettingController::class,'listing'])->name('quiz.setting.listing');
                Route::delete('/quiz/{quiz}', [QuizSettingController::class, 'destroy'])->name('quiz.setting.destroy');
                Route::post('/quiz/toggle', [QuizSettingController::class, 'toggleActive'])->name('quiz.setting.toggle');

            });

            //quiz-attempt delete only admin 
            Route::delete('/admin/attempts/{id}', [AdminQuizController::class, 'destroy'])->name('admin.attempts.destroy');

            // User data here show admin 
            Route::resource('/users',UserWiseQuizController::class);
            Route::get('/schedule',[UserWiseQuizController::class,'userwiseQuizlist'])->name('schedule.index');
            Route::get('/schedule/create',[UserWiseQuizController::class,'createSchedule'])->name('schedule.create');
            Route::post('/schedule/store',[UserWiseQuizController::class,'scheduleStore'])->name('schedule.store');
            Route::delete('/schedule/{id}', [UserWiseQuizController::class, 'destroySchedule'])->name('schedule.delete');
            Route::post('/schedule/toggle', [UserWiseQuizController::class, 'toggleProcess'])->name('schedule.toggle');
            Route::get('/schedule/edit/{id}', [UserWiseQuizController::class, 'editschedule'])->name('schedule.edit');
            Route::put('/schedule/{id}', [UserWiseQuizController::class, 'updateschedule'])->name('schedule.update');



        


        

     });
    
     // Quiz for user 
      Route::middleware(['user'])->group(function () {
        Route::get('quiz',[QuizController::class,'index'])->name('quiz.index');
    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{quiz}/score', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{id}/review', [QuizController::class, 'review'])->name('quiz.review');
    Route::get('/quiz/{quiz}/score', [QuizController::class, 'showScore'])->name('quiz.score');
    Route::get('/my-schedule', [UserWiseQuizController::class, 'userSchedule'])->name('quiz.schedule');
      });
    




});
Route::get('/test-table', function () {
    return view('test');
});
 // Quiz Attempt Module
        Route::get('/quiz-attempts', [AdminQuizController::class, 'index'])->name('admin.attempts.index');
        Route::get('/quiz-attempts/{id}', [AdminQuizController::class, 'show'])->name('admin.attempts.show');
require __DIR__.'/auth.php';
