<?php

use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API routes (v1)
|--------------------------------------------------------------------------
|
| Read-only, unauthenticated endpoints for published quizzes. Registered
| through the `api` routing key in bootstrap/app.php, which applies the
| stateless `api` middleware group (no session, no CSRF).
|
*/

Route::prefix('v1')->group(function (): void {
    Route::get('quizzes', [QuizController::class, 'index'])->name('api.v1.quizzes.index');
    Route::get('quizzes/{slug}', [QuizController::class, 'show'])->name('api.v1.quizzes.show');
});
