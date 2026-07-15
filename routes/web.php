<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\DptController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\FormateurController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Auth\AlumniRegisterController;
use App\Http\Controllers\Candidate\ProfileCandidateController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $candidates = \App\Models\Candidate::with('alumni')
        ->whereHas('period', fn($q) => $q->where('is_active', true))
        ->where('is_active', true)
        ->orderBy('number')
        ->get();

    $activePeriod = \App\Models\ElectionPeriod::active();
    $activePeriod?->load('schedules');
    $dptSchedule      = $activePeriod?->dptSchedule();
    $electionSchedule = $activePeriod?->electionSchedule();

    $documents = \App\Models\Document::where('is_published', true)
        ->orderBy('sort_order')
        ->orderByDesc('created_at')
        ->get();

    // Konversi URL YouTube ke format embed
    $rawYoutube     = \App\Models\Setting::get('youtube_url', '');
    $youtubeEmbed   = null;
    if ($rawYoutube) {
        if (preg_match('/[?&]v=([^&\s]+)/', $rawYoutube, $m) ||
            preg_match('/youtu\.be\/([^?&\s]+)/', $rawYoutube, $m) ||
            preg_match('/youtube\.com\/embed\/([^?&\s]+)/', $rawYoutube, $m)) {
            $youtubeEmbed = 'https://www.youtube.com/embed/' . $m[1];
        }
    }
    $youtubeTitle = \App\Models\Setting::get('youtube_title', 'Panduan Video');

    $formateurs = \App\Models\Formateur::with('alumni')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();

    return view('welcome', compact(
        'candidates', 'activePeriod', 'dptSchedule', 'electionSchedule',
        'documents', 'youtubeEmbed', 'youtubeTitle', 'formateurs'
    ));
});

Route::get('/kandidat/{candidate}', function (\App\Models\Candidate $candidate) {
    abort_if(!$candidate->is_active, 404);
    return view('public.candidate-profile', compact('candidate'));
})->name('candidate.profile')->where('candidate', '[0-9]+');

Route::get('/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard — semua user terautentikasi bisa akses
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Data Alumni — butuh permission users.view
    Route::get('/alumni',                    [AlumniController::class, 'index'])->middleware('permission:users.view')->name('alumni.index');
    Route::post('/alumni',                   [AlumniController::class, 'store'])->middleware('permission:users.create')->name('alumni.store');
    Route::put('/alumni/{alumnus}',          [AlumniController::class, 'update'])->middleware('permission:users.edit')->name('alumni.update');
    Route::delete('/alumni/{alumnus}',       [AlumniController::class, 'destroy'])->middleware('permission:users.delete')->name('alumni.destroy');
    Route::patch('/alumni/{alumnus}/toggle', [AlumniController::class, 'toggleStatus'])->middleware('permission:users.edit')->name('alumni.toggle');
    Route::post('/alumni/import',            [AlumniController::class, 'import'])->middleware('permission:users.create')->name('alumni.import');
    Route::get('/alumni/template',           [AlumniController::class, 'downloadTemplate'])->middleware('permission:users.view')->name('alumni.template');

    // Data Pemilih — butuh permission voters.view / voters.create / dst.
    Route::get('/voters',              [AdminController::class, 'voters'])->middleware('permission:voters.view')->name('voters');
    Route::post('/voters',             [AdminController::class, 'storeVoter'])->middleware('permission:voters.create')->name('voters.store');
    Route::put('/voters/{voter}',      [AdminController::class, 'updateVoter'])->middleware('permission:voters.edit')->name('voters.update');
    Route::delete('/voters/{voter}',   [AdminController::class, 'destroyVoter'])->middleware('permission:voters.delete')->name('voters.destroy');

    // Pendaftaran DPT
    Route::get('/dpt/register',                [DptController::class, 'showRegister'])->name('dpt.register');
    Route::get('/dpt/register/{alumnus}',      [DptController::class, 'showRegisterAlumni'])->middleware('permission:voters.create')->name('dpt.register.alumni');
    Route::post('/dpt/capture',                [DptController::class, 'storeCapture'])->name('dpt.capture');
    Route::delete('/dpt/{voter}/reset-face',   [DptController::class, 'resetFace'])->middleware('permission:voters.edit')->name('dpt.reset');

    // Kandidat
    Route::get('/candidates',                       [CandidateController::class, 'index'])->middleware('permission:candidates.view')->name('candidates');
    Route::get('/candidates/create',                [CandidateController::class, 'create'])->middleware('permission:candidates.create')->name('candidates.create');
    Route::post('/candidates',                      [CandidateController::class, 'store'])->middleware('permission:candidates.create')->name('candidates.store');
    Route::get('/candidates/{candidate}/edit',      [CandidateController::class, 'edit'])->middleware('permission:candidates.edit')->name('candidates.edit');
    Route::put('/candidates/{candidate}',           [CandidateController::class, 'update'])->middleware('permission:candidates.edit')->name('candidates.update');
    Route::delete('/candidates/{candidate}',        [CandidateController::class, 'destroy'])->middleware('permission:candidates.delete')->name('candidates.destroy');
    Route::get('/candidates/search-alumni',         [CandidateController::class, 'searchAlumni'])->middleware('permission:candidates.view')->name('candidates.search-alumni');

    // Hasil Voting — alumni pun bisa akses (election.results)
    Route::get('/results',      [AdminController::class, 'results'])->middleware('permission:election.results')->name('results');
    Route::get('/results/data', [AdminController::class, 'resultsData'])->middleware('permission:election.results')->name('results.data');

    // Jadwal & Periode Pemilihan
    Route::middleware('permission:election.manage')->group(function () {
        Route::get('/schedule',                                [ScheduleController::class, 'index'])->name('schedule');
        // Periode
        Route::post('/schedule/periods',                       [ScheduleController::class, 'storePeriod'])->name('schedule.periods.store');
        Route::put('/schedule/periods/{period}',              [ScheduleController::class, 'updatePeriod'])->name('schedule.periods.update');
        Route::patch('/schedule/periods/{period}/activate',   [ScheduleController::class, 'activatePeriod'])->name('schedule.periods.activate');
        Route::delete('/schedule/periods/{period}',           [ScheduleController::class, 'destroyPeriod'])->name('schedule.periods.destroy');
        // Jadwal
        Route::put('/schedule/schedules/{schedule}',          [ScheduleController::class, 'updateSchedule'])->name('schedule.update');
    });

    // Tim Formatur
    Route::get('/formateurs',                         [FormateurController::class, 'index'])->middleware('permission:settings.view')->name('formateurs.index');
    Route::post('/formateurs',                        [FormateurController::class, 'store'])->middleware('permission:settings.manage')->name('formateurs.store');
    Route::put('/formateurs/{formateur}',             [FormateurController::class, 'update'])->middleware('permission:settings.manage')->name('formateurs.update');
    Route::delete('/formateurs/{formateur}',          [FormateurController::class, 'destroy'])->middleware('permission:settings.manage')->name('formateurs.destroy');
    Route::patch('/formateurs/{formateur}/toggle',    [FormateurController::class, 'toggleStatus'])->middleware('permission:settings.manage')->name('formateurs.toggle');

    // Pengaturan
    Route::get('/settings',  [AdminController::class, 'settings'])->middleware('permission:settings.view')->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->middleware('permission:settings.manage')->name('settings.update');

    // Dokumen Publik
    Route::get('/documents',                        [AdminController::class, 'documents'])->middleware('permission:settings.view')->name('documents');
    Route::post('/documents',                       [AdminController::class, 'storeDocument'])->middleware('permission:settings.manage')->name('documents.store');
    Route::patch('/documents/{document}/toggle',    [AdminController::class, 'toggleDocument'])->middleware('permission:settings.manage')->name('documents.toggle');
    Route::delete('/documents/{document}',          [AdminController::class, 'destroyDocument'])->middleware('permission:settings.manage')->name('documents.destroy');

    // Pengguna
    Route::get('/users',                 [UserController::class, 'index'])->middleware('permission:users.view')->name('users.index');
    Route::post('/users',                [UserController::class, 'store'])->middleware('permission:users.create')->name('users.store');
    Route::put('/users/{user}',          [UserController::class, 'update'])->middleware('permission:users.edit')->name('users.update');
    Route::delete('/users/{user}',       [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('users.destroy');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->middleware('permission:users.edit')->name('users.toggle');

    // Role & Akses
    Route::get('/roles',           [RoleController::class, 'index'])->middleware('permission:roles.view')->name('roles.index');
    Route::post('/roles',          [RoleController::class, 'store'])->middleware('permission:roles.manage')->name('roles.store');
    Route::put('/roles/{role}',    [RoleController::class, 'update'])->middleware('permission:roles.manage')->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.manage')->name('roles.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile',               [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',             [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',            [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/password',      [ProfileController::class, 'password'])->name('profile.password');
    Route::patch('/profile/alumni-data', [ProfileController::class, 'updateAlumniData'])->name('profile.alumni-data');
});

// Kandidat — hanya role kandidat
Route::middleware(['auth', 'verified', 'role:kandidat'])->prefix('kandidat')->name('kandidat.')->group(function () {
    Route::get('/profil',  [ProfileCandidateController::class, 'edit'])->name('profil.edit');
    Route::put('/profil',  [ProfileCandidateController::class, 'update'])->name('profil.update');
});

// Voting — semua alumni terautentikasi bisa akses
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/voting',              [VotingController::class, 'index'])->name('voting');
    Route::post('/voting/verify-face', [VotingController::class, 'verifyFace'])->name('voting.verify-face');
    Route::post('/voting/cast',        [VotingController::class, 'cast'])->name('voting.cast');
});

// Registrasi Alumni (publik, tanpa auth)
Route::middleware('guest')->prefix('register/alumni')->name('alumni.register.')->group(function () {
    Route::get('/',        [AlumniRegisterController::class, 'showStep1'])->name('step1');
    Route::post('/verify', [AlumniRegisterController::class, 'verifyStep1'])->name('verify');
    Route::get('/akun',    [AlumniRegisterController::class, 'showStep2'])->name('step2');
    Route::post('/akun',   [AlumniRegisterController::class, 'register'])->name('submit');
});

require __DIR__.'/auth.php';
