<?php

use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\FormSubmitController;
use Illuminate\Support\Facades\Route;
// Redirect root to admin
Route::get('/', fn () => redirect()->route('admin.forms.index'));

// ─── Admin Routes ────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('forms', FormController::class)
         ->except(['show']);

    // Submissions viewer
    Route::get('forms/{id}/submissions', [FormController::class, 'submissions'])
         ->name('forms.submissions');
});

// ─── Public Form Routes ──────────────────────────────────────
Route::get('/form/{slug}',          [FormSubmitController::class, 'show'])->name('form.show');
Route::post('/form/{slug}/submit',  [FormSubmitController::class, 'submit'])->name('form.submit');

