<?php

namespace App\Providers;

use App\Firebase\FirebaseAuth;
use Illuminate\Support\ServiceProvider;

class FirebaseAuthProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FirebaseAuth::class, function() {
            $sa = new FirebaseAuth();
            return $sa;
        });
    }
}
