<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

require base_path('routes/auth.php');
require base_path('routes/creator.php');
// require base_path('routes/user.php');
// require base_path('routes/admin.php');
// require base_path('routes/api.php');
// require base_path('routes/public.php');