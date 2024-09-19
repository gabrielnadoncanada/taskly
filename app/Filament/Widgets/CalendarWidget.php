<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Widgets\CalendarWidget as BaseCalendarWidget;
use Illuminate\Database\Eloquent\Collection;

class CalendarWidget extends BaseCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = Task::class;

    protected string $calendarView = 'timeGridWeek';

    //    public function getEvents(array $fetchInfo = []): Collection | array
    //    {
    //        return [
    //            // Chainable object-oriented variant
    //            Event::make()
    //                ->title('My first event')
    //                ->start(today())
    //                ->end(today()),
    //
    //            // Array variant
    //            ['title' => 'My second event', 'start' => today()->addDays(3), 'end' => today()->addDays(3)],
    //
    //            // Eloquent model implementing the `Eventable` interface
    //            MyEvent::find(1),
    //        ];
    //    }

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        return Task::all()
            ->map(function ($task) {
                return Event::make()
                    ->title($task->title)
                    ->start($task->date)
                    ->end($task->date)
                    ->action('edit')
                    ->backgroundColor('rgb(var(--'.$task->status->getColor().'-950))')
                    ->textColor('#ffffff');
            })
            ->toArray();
    }

    public function getFormSchema(): array
    {
        return [
            Group::make([
                TextInput::make(Task::TITLE)
                    ->required(),
                Select::make(Task::PROJECT_ID)
                    ->relationship('project')
                    ->options(Project::all()->pluck(Project::TITLE, 'id'))
                    ->required(),
                Select::make('user_id')
                    ->multiple()
                    ->relationship('users')
                    ->label('Assigned User')
                    ->options(User::all()->pluck(User::NAME, 'id'))
                    ->columnSpanFull(),
                DateTimePicker::make(Task::DATE)
                    ->default(now())
                    ->required(),
            ])->columns(),
        ];
    }
}
