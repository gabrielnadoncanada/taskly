<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait CanGetNamesStatically
{
    public static function tableName()
    {
        return (new static())->getTable();
    }

    public static function baseTableName()
    {
        return (new static())->getBaseTableName();
    }

    public function getBaseTableName()
    {
        return $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));
    }

    public static function className(): string
    {
        return (new \ReflectionClass(new static()))->getShortName();
    }

    public static function primaryKey(): string
    {
        return (new static())->primaryKey;
    }
}
