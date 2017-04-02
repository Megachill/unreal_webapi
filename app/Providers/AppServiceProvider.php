<?php
/**
 * @author      Megachill
 * @date        31/03/2017
 * @file        AppServiceProvider.php
 * @copyright   MIT
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
        //$this->app->register(\Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class); // JWTAuth 0.5
    }


}
