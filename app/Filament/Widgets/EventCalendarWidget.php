<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\TaskResource;
use App\Models\Project;
use App\Models\Receipt;
use App\Models\Shipment;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Saade\FilamentFullCalendar\Actions;

use App\Models\Task;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class EventCalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = Task::class;


    public function config(): array
    {
        return [
            'firstDay' => 1,
            'dayMaxEventRows' => true,
            'selectable' => true,
        ];
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            Task::DATE => $arguments['start'] ?? null,
                            Task::ALL_DAY => true,
                        ]);
                    }
                ),
        ];
    }

    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
                ->mountUsing(
                    function ($record, $form, array $arguments) {
                        $form->fill([
                                Task::TITLE => $record->{Task::TITLE},
                                Task::DATE => $arguments['event']['start'] ?? $record->{Task::DATE},
                                Task::ALL_DAY => $arguments['event']['allDay'] ?? $record->{Task::ALL_DAY},
                            ]
                        );
                    }
                ),
            Actions\DeleteAction::make(),
        ];
    }

    protected function viewAction(): \Filament\Actions\Action
    {
        return Actions\ViewAction::make();
    }


    public function fetchEvents(array $info = []): array
    {
        $events = [];

        //        $receipts = Receipt::all();
        //        $shipments = Shipment::all();
        $projects = Project::all();
        $tasks = Task::all();
        //
        //        foreach ($projects as $project) {
        //            $events[] = [
        //                'title' => $project->title,
        //                'start' => $project->{Project::DATE},
        //                'url' => ProjectResource::getUrl('edit', ['record' => $project]),
        //                'color' => 'rgb(var(--primary-700)',
        //            ];
        //        }
        //
        foreach ($tasks as $task) {
            $events[] = [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->{Task::DATE},
                'color' => 'rgb(var(--success-700)',
            ];
        }

        return $events;
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
            ])->columns()
        ];
    }


}
