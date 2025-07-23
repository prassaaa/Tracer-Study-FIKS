<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\RiwayatPekerjaanController;
use App\Http\Controllers\PendidikanLanjutController;
use App\Http\Controllers\SurveiController;
use App\Http\Controllers\SurveiAlumniController;
use App\Http\Controllers\ClusteringController;
use App\Http\Controllers\Admin\AlumniApprovalController;

// Route publik
Route::get('/', function () {
    return view('welcome');
});

// Route yang memerlukan autentikasi
Route::middleware(['auth', 'status'])->group(function () {
    // Route untuk dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Program Studi
        Route::resource('program-studi', ProgramStudiController::class);

        // Route untuk profil admin
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::get('/alumni/{userId}/create-profile', [AdminController::class, 'createAlumniProfile'])->name('alumni.create-profile');
        Route::post('/alumni/{userId}/store-profile', [AdminController::class, 'storeAlumniProfile'])->name('alumni.store-profile');

        // Alumni
        Route::resource('alumni', AdminController::class);
        Route::get('/alumni-export', [AdminController::class, 'exportExcel'])->name('alumni.export');
        Route::get('/alumni-export-csv', [AdminController::class, 'exportCSV'])->name('alumni.export.csv');



        // Survei
        Route::resource('survei', SurveiController::class);
        Route::get('survei/{survei}/pertanyaan', [SurveiController::class, 'pertanyaan'])->name('survei.pertanyaan');
        Route::get('survei/{survei}/pertanyaan/create', [SurveiController::class, 'createPertanyaan'])->name('survei.pertanyaan.create');
        Route::post('survei/{survei}/pertanyaan', [SurveiController::class, 'storePertanyaan'])->name('survei.pertanyaan.store');
        Route::get('survei/{survei}/pertanyaan/{pertanyaan}/edit', [SurveiController::class, 'editPertanyaan'])->name('survei.pertanyaan.edit');
        Route::put('survei/{survei}/pertanyaan/{pertanyaan}', [SurveiController::class, 'updatePertanyaan'])->name('survei.pertanyaan.update');
        Route::delete('survei/{survei}/pertanyaan/{pertanyaan}', [SurveiController::class, 'destroyPertanyaan'])->name('survei.pertanyaan.destroy');

        // Clustering
        Route::resource('clustering', ClusteringController::class);
        Route::get('clustering-dashboard', [ClusteringController::class, 'dashboard'])->name('clustering.dashboard');

        // Tambahkan route untuk analisis clustering
        Route::get('clustering/{id}/analisis', [ClusteringController::class, 'analisis'])->name('clustering.analisis');
        Route::get('clustering/{id}/export', [ClusteringController::class, 'export'])->name('clustering.export');

        // Alumni Approval Routes
        Route::get('/alumni-approval', [AlumniApprovalController::class, 'index'])->name('alumni-approval.index');
        Route::get('/alumni-approval/approved', [AlumniApprovalController::class, 'approved'])->name('alumni-approval.approved');
        Route::get('/alumni-approval/rejected', [AlumniApprovalController::class, 'rejected'])->name('alumni-approval.rejected');
        Route::get('/alumni-approval/{id}/approve', [AlumniApprovalController::class, 'approve'])->name('alumni-approval.approve');
        Route::post('/alumni-approval/{id}/store-approve', [AlumniApprovalController::class, 'storeApprove'])->name('alumni-approval.storeApprove');
        Route::post('/alumni-approval/{id}/reject', [AlumniApprovalController::class, 'reject'])->name('alumni-approval.reject');
    });

    // Route untuk alumni
    Route::middleware(['role:alumni'])->prefix('alumni')->name('alumni.')->group(function () {
        // Profil
        Route::get('/profile', [AlumniController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [AlumniController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile/create', [AlumniController::class, 'createProfile'])->name('profile.create');
        Route::post('/profile/store', [AlumniController::class, 'storeProfile'])->name('profile.store');

        // Riwayat Pekerjaan
        Route::resource('riwayat-pekerjaan', RiwayatPekerjaanController::class);

        // Pendidikan Lanjut
        Route::resource('pendidikan', PendidikanLanjutController::class);

        // Survei
        Route::get('survei', [SurveiAlumniController::class, 'index'])->name('survei.index');
        Route::get('survei/{survei}', [SurveiAlumniController::class, 'show'])->name('survei.show');
        Route::post('survei/{survei}', [SurveiAlumniController::class, 'store'])->name('survei.store');
    });
});

require __DIR__.'/auth.php';
