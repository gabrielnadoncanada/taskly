<?php

namespace App\Providers;

use App\Helpers\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    public function register()
    {
        Date::use(Carbon::class);
    }
}
