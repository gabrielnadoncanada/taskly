<?php

namespace App\Enums\Traits;

use Str;

trait HasTranslatableLabel
{
    public function getTranslatableLabel(): string
    {
        return __("enums.{$this->getTranslationKey()}");
    }

    protected function getTranslationKey(): string
    {
        return Str::snake(class_basename($this)).'.'.strtolower($this->name);
    }
}
