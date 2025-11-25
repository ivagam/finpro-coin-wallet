<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AiapplicationController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ComponentpageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RoleandaccessController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\MintController;
use App\Http\Controllers\BurnController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\SessionAuth;



Route::controller(AuthenticationController::class)->group(function () {
        // Show login page
        Route::get('/login', 'signin')->name('loginForm');  

        // Process login
        Route::post('/login', 'login')->name('login');

        // Other routes
        Route::get('/', 'signin')->name('signin');

        Route::post('/register', 'register')->name('register');
        Route::post('/sendPassword', 'sendPassword')->name('sendPassword');

        Route::get('/forgotpassword', 'forgotPassword')->name('forgotPassword');        
        Route::get('/signup', 'signup')->name('signup');
         Route::get('/verifyEmail', 'verifyEmail')->name('verifyEmail');
        Route::post('/logout', 'logout')->name('logout');
    });

Route::middleware([SessionAuth::class])->group(function () {

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard.index');
            Route::get('/userList', 'userList')->name('dashboard.userList');
            Route::post('/withdraw', 'withdraw')->name('dashboard.withdraw');
        });
    });

    // Mint
    Route::prefix('mint')->group(function () {
        Route::controller(MintController::class)->group(function () {
            Route::get('/', 'mint')->name('mint');
            Route::post('/', 'storeMint')->name('mint.store');
            Route::get('/mint-report', 'mintReport')->name('mintReport');
        });
    });

    // Burn
    Route::prefix('burn')->group(function () {
        Route::controller(BurnController::class)->group(function () {
            Route::get('/', 'burn')->name('burn');
            Route::post('/', 'storeBurn')->name('burn.store');
            Route::get('/burn-report', 'burnReport')->name('burnReport');
            Route::post('/ajaxBurn', 'ajaxBurn')->name('ajaxBurn');
        });
    });

    // Transfer
    Route::prefix('transfer')->group(function () {
        Route::controller(TransactionController::class)->group(function () {
            Route::get('/', 'transfer')->name('transfer');
            Route::post('/', 'storeTransfer')->name('transfer.store');
            Route::get('/transfer-history', 'transferHistory')->name('transferHistory');
            Route::get('/withdrawalReport', 'withdrawalReport')->name('withdrawalReport');
            Route::post('/ajaxWithdrawal', 'ajaxWithdrawal')->name('ajaxWithdrawal');
            Route::get('/depositReport', 'depositReport')->name('depositReport');
            Route::post('/approveWithdraw', 'approveWithdraw')->name('transfer.approve');
            Route::post('/approveDeposit', 'approveDeposit')->name('deposit.approve');
            Route::post('/saveDeposit', 'saveDeposit')->name('saveDeposit');
            Route::post('/ajaxSend', 'ajaxSend')->name('ajaxSend');
        });
    });

    // Profile actions
    Route::post('/profile/update', [UsersController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/changePassword', [UsersController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/profile/updateBankAccount', [UsersController::class, 'updateBankAccount'])->name('profile.updateBankAccount');

    // Users pages
    Route::prefix('users')->group(function () {
        Route::controller(UsersController::class)->group(function () {
            Route::get('/add-user', 'addUser')->name('users.addUser');
            Route::get('/users-grid', 'usersGrid')->name('users.usersGrid');
            Route::get('/users-list', 'usersList')->name('users.usersList');
            Route::get('/view-profile', 'viewProfile')->name('users.viewProfile');
        });
    });

});

