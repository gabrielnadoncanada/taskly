<?php

namespace App\Models;

use App\Enums\Currency;
use App\Enums\MeasurementSystem;
use Devlense\FilamentTenant\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getMeasurementSystemSuffix(): string
    {
        return $this->measurement_system === MeasurementSystem::METRIC ? 'kg' : 'lb';
    }

    public function getCurrencySymbol(): string
    {
        return $this->currency === Currency::CAD ? 'CAD$' : ($this->currency === Currency::USD ? 'USD$' : '$');
    }
}
