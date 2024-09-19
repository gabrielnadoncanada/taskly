<?php

namespace App\Filament\Fields;

use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Squire\Models\Country;

class Subtasks extends Forms\Components\Field
{

    protected string $view = 'filament.fields.subtasks';



    protected function setUp(): void
    {
        parent::setUp();


        $this->columnSpanFull();
    }




}
