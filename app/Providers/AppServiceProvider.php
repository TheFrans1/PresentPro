<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon; 

class AppServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
       
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
       
    }
    
}