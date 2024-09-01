<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;


use App\Http\Middleware\AdminMiddleware;

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::delete('/replies/{id}', [ReplyController::class, 'destroy'])->name('replies.destroy');
    Route::get('/admin/reported', [AdminController::class, 'reportedItems'])->name('admin.reported');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions/create', [QuestionController::class, 'store'])->name('questions.store');
    
    Route::get('/questions/show/{id}', [QuestionController::class, 'show'])->name('questions.show');

    Route::get('/questions/load-more-tags', [QuestionController::class, 'loadMoreTags'])->name('questions.loadMoreTags');
    Route::get('/questions/filter', [QuestionController::class, 'filterByTag'])->name('questions.filter');
    Route::post('/questions/{question}/replies', [ReplyController::class, 'store'])->name('replies.store');
    Route::post('/questions/{id}/upvote', [QuestionController::class, 'upvote'])->name('questions.upvote');
    Route::post('/questions/{id}/downvote', [QuestionController::class, 'downvote'])->name('questions.downvote');
    Route::post('/report/question/{id}', [ReportController::class, 'reportQuestion'])->name('report.question');
    Route::post('/report/reply/{id}', [ReportController::class, 'reportReply'])->name('report.reply');

    Route::post('/replies/{id}/upvote', [ReplyController::class, 'upvote'])->name('replies.upvote');
    Route::post('/replies/{id}/downvote', [ReplyController::class, 'downvote'])->name('replies.downvote');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/user/{id}', [ProfileController::class, 'show'])->name('profile.show');

    // Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('questions.index');
    });

    // Route::resource('questions', QuestionController::class);
});


Route::get('/email/verify',function(){
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


require __DIR__.'/auth.php';
