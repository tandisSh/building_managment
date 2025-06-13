<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
 use Illuminate\Support\Facades\View;
use App\Models\BuildingUser;
use App\Models\BuildingRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

     View::composer('*', function ($view) {
    if (auth()->check()) {
        $data = $view->getData();
        if (!array_key_exists('building', $data)) {
            $user = auth()->user();
            $building = optional($user->buildingUser)->building;
            $buildingRequestStatus = optional($user->buildingRequest)->status;

            $view->with(compact('building', 'buildingRequestStatus'));
        }
    }
});


    }

}
