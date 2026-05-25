<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\CrisisController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ModesController;
use Illuminate\Support\Facades\Route;

// ═══ Wellness Modes (Public) ═══
Route::prefix('modes')->name('modes.')->group(function () {
    Route::get('/', [ModesController::class, 'index'])->name('index');
    Route::get('/breathe', [ModesController::class, 'breathe'])->name('breathe');
    Route::get('/meditate', [ModesController::class, 'meditate'])->name('meditate');
    Route::get('/focus', [ModesController::class, 'focus'])->name('focus');
    Route::get('/music', [ModesController::class, 'music'])->name('music');
    Route::get('/sleep', [ModesController::class, 'sleep'])->name('sleep');
});

// ═══ Public Pages ═══
Route::get('/', function () {
    $assessments = \App\Models\Assessment::all();
    return view('welcome', compact('assessments'));
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/crisis', [CrisisController::class, 'index'])->name('crisis.index');

// ═══ Assessment Routes (Public) ═══
Route::prefix('assessments')->name('assessments.')->group(function () {
    Route::get('/', [AssessmentController::class, 'index'])->name('index');
    Route::get('/{assessment}', [AssessmentController::class, 'show'])->name('show');
    Route::post('/{assessment}', [AssessmentController::class, 'store'])->name('store');
    Route::get('/result/{result}', [AssessmentController::class, 'result'])->name('result');
});

// ═══ Resource Library (Public) ═══
Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');

// ═══ AI Chatbot (Works for both guests and authenticated users) ═══
Route::post('/chatbot', [ChatbotController::class, 'respond'])->name('chatbot.respond');

// ═══ Onboarding ═══
Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

// ═══ Authenticated Routes ═══
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mood Tracking
    Route::post('/mood', [MoodController::class, 'store'])->name('mood.store');
    Route::get('/mood', [MoodController::class, 'index'])->name('mood.index');

    // Journal
    Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
    Route::get('/journal/create', [JournalController::class, 'create'])->name('journal.create');
    Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');
    Route::get('/journal/{entry}', [JournalController::class, 'show'])->name('journal.show');
    Route::delete('/journal/{entry}', [JournalController::class, 'destroy'])->name('journal.destroy');
});

require __DIR__.'/auth.php';
