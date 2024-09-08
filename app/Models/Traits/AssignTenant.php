<?php

namespace App\Models\Traits;

use Filament\Facades\Filament;

trait AssignTenant
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (is_null($model->organization_id)) {
                if (Filament::getTenant() != null) {
                    $model->organization_id = Filament::getTenant()->id;
                }
            }
        });
    }
}
