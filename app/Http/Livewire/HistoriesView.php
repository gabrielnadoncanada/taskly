<?php

namespace App\Http\Livewire;

use App\Models\Client;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class HistoriesView extends Component implements HasTable
{
    use InteractsWithTable;

    public $record;

    public function mount($record)
    {
        $this->record = $record;
    }

    protected function getTableQuery(): Builder
    {
        return Client::query()
            ->where('client_id', $this->record->id);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('status'),
        ];
    }

    public function render(): View
    {
        return view('livewire.histories-view');
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        // TODO: Implement makeFilamentTranslatableContentDriver() method.
    }
}
