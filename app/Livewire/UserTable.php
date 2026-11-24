<?php

namespace App\Livewire;

use App\Models\User;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name', fn ($row) => $row->name)
            ->add('email', fn ($row) => $row->email)
            ->add('role_badge', fn ($row) => Ui::badge(ucfirst($row->role), $row->role === 'admin' ? 'blue' : 'gray'))
            ->add('is_active_badge', fn ($row) => $row->is_active
                ? Ui::badge('Active', 'green')
                : Ui::badge('Disabled', 'red'))
            ->add('created_at_label', fn ($row) => Ui::date($row->created_at))
            ->add('row_actions', fn ($row) => Ui::rowActions(
                route('users.edit', $row->id),
                $row->id,
                'Delete user '.$row->name.'?'
            ));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),

            Column::make('Name', 'name')->sortable()->searchable(),

            Column::make('Email', 'email')->sortable()->searchable(),

            Column::make('Role', 'role_badge', 'role')->sortable(),

            Column::make('Status', 'is_active_badge', 'is_active')->sortable(),

            Column::make('Created', 'created_at_label', 'created_at')->sortable(),

            Column::make('Actions', 'row_actions'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::select('role_badge', 'role')
                ->dataSource(Ui::filterOptions(User::ROLES))
                ->optionLabel('label')
                ->optionValue('value'),
            Filter::boolean('is_active_badge', 'is_active')->label('Active', 'Disabled'),
        ];
    }

    /**
     * Livewire methods can be called from the browser, so the checks live here too,
     * not only in the route middleware of the page.
     */
    public function deleteRow(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $user = User::findOrFail($id);

        // Never delete yourself (would lock you out) or the last administrator.
        if ($user->id === auth()->id()) {
            return;
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return;
        }

        $user->delete();
    }
}
