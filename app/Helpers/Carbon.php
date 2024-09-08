<?php

namespace App\Helpers;

use Carbon\CarbonImmutable;

class Carbon extends CarbonImmutable
{
    public function formatTime()
    {
        return $this
            ->tz(config('app.local_timezone'))
            ->locale(config('app.locale'))
            ->format('H:i');
    }

    public function formatDateTime()
    {
        return $this
            ->tz(config('app.local_timezone'))
            ->locale(config('app.locale'))
            ->format('Y-m-d H:i');
    }

    public function formatDate()
    {
        return $this
            ->tz(config('app.local_timezone'))
            ->locale(config('app.locale'))
            ->format('Y-m-d');
    }

    public static function createFromTimeLocal(string $time, ?Carbon $date = null)
    {
        if ($date == null) {
            $date = self::today(config('app.local_timezone'));
        } else {
            $date = $date->tz(config('app.local_timezone'));
        }

        return $date->hour(str($time)->before(':')->toInteger())
            ->minute(str($time)->after(':')->toInteger())
            ->seconds(0);
    }
}
