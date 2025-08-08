<?php

use App\Livewire\Admin\CreditExchangeManagement;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\LeagueManagement;
use App\Livewire\Admin\MatchManagement;
use App\Livewire\Admin\RoleManagement;
use App\Livewire\Admin\Setting;
use App\Livewire\Admin\TeamManagement;
use App\Livewire\Admin\UserManagement;
use App\Livewire\ChangePassword;
use App\Livewire\CheckIn;
use App\Livewire\ExchangeCreditHistories;
use App\Livewire\Index;
use App\Livewire\Login;
use App\Livewire\PointHistory;
use App\Livewire\PredictionRank;
use App\Livewire\Register;
use App\Livewire\SelectGame;
use App\Livewire\SelectSingleGame; // ✅ เพิ่มบรรทัดนี้
use App\Livewire\ViewUserPredicted;
use Illuminate\Support\Facades\Route;

Route::get('/', Index::class)->name('index');

Route::get('/register', Register::class)->name('register');

Route::get('/login', Login::class)->name('login');

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('select-game', SelectGame::class)->name('select-game');
    Route::get('select-single-game', SelectSingleGame::class)->name('select-single-game'); // ✅ เพิ่มบรรทัดนี้
    Route::get('point-history', PointHistory::class)->name('point-history');

    // อันดับคะแนนรวม (ของเดิม)
    Route::get('prediction-rank', PredictionRank::class)->name('prediction-rank');

    // ✅ อันดับคะแนนเฉพาะบอลเต็ง
    Route::get('prediction-rank/single', PredictionRank::class)
        ->name('prediction-rank-single')
        ->defaults('type', 'single');

    // ✅ อันดับคะแนนเฉพาะบอลสเต็ป
    Route::get('prediction-rank/step', PredictionRank::class)
        ->name('prediction-rank-step')
        ->defaults('type', 'step');

    Route::get('check-in', CheckIn::class)->name('check-in');
    Route::get('view-prediction/{userId}', ViewUserPredicted::class)->name('view-prediction');
    Route::get('exchange-credit-history', ExchangeCreditHistories::class)->name('exchange-credit-history');
    Route::get('change-password', ChangePassword::class)->name('change-password');
});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'auth.session', 'permission:access-admin'])->group(function () {
        Route::get('dashboard', Dashboard::class)->name('admin.dashboard');
        Route::get('user-management', UserManagement::class)->name('admin.user-management')->middleware('permission:view-manage-user');
        Route::get('league-management', LeagueManagement::class)->name('admin.league-management')->middleware('permission:view-manage-league');
        Route::get('team-management', TeamManagement::class)->name('admin.team-management')->middleware('permission:view-manage-team');
        Route::get('match-management', MatchManagement::class)->name('admin.match-management')->middleware('permission:view-manage-match');
        Route::get('setting', Setting::class)->name('admin.setting')->middleware('permission:view-setting');
        Route::get('credit-exchange-management', CreditExchangeManagement::class)->name('admin.credit-exchange-management')->middleware('permission:view-credit-exchange-management');
        Route::get('roles', RoleManagement::class)->name('admin.roles');
    });
});
