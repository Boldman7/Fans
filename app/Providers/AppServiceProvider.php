<?php

namespace App\Providers;

use App\Setting;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Guard $auth)
    {
        Validator::extend('not_contains', function($attribute, $value, $parameters)
        {
            // Banned words
            $words = explode(",",Setting::get('censored_words'));
            foreach ($words as $word)
            {
                if (stripos($value, $word) !== false) return false;
            }
            return true;
        });

        Validator::extend('no_admin', function($attribute, $value, $parameters)
        {
            if (stripos($value, 'admin') !== false) return false;
            return true;
        });
        

        if (env('APP_ENV', 'local') !== 'local') {
            DB::connection()->disableQueryLog();
        }

        // if(Schema::hasTable('settings')) {
        //     App::setLocale(Setting::get('language', 'en'));    
        // }
        // else {
        //     App::setLocale('en');
        // }
                
        if (Schema::hasTable('settings')) {
            // Config::set('app.timezone', Setting::get('timezone', 'UTC'));
            Config::set('app.timezone', date_default_timezone_get());
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
