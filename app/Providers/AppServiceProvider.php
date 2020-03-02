<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\CashbackCampaign;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Role;
use App\Models\Sale;
use App\Models\StatusSaleSale;
use App\Models\Type;
use App\Models\User;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if (env('FORCE_HTTPS')) {

            URL::forceScheme('https');
        }

        User::observe(UserObserver::class);
        Role::observe(RoleObserver::class);

        $this->app->singleton(FakerGenerator::class, function () {
            return FakerFactory::create('pt_BR');
        });

    }
}
