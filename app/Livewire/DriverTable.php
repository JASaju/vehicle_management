<?php

namespace App\Livewire;

use App\Models\Driver;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class DriverTable extends PowerGridComponent
{
    public string $tableName = 'driver-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'drivers')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Driver::query()->withCount('vehicles');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name', fn ($row) => $row->name)
            ->add('phone', fn ($row) => $row->phone)
            ->add('email', fn ($row) => $row->email ?: '—')
            ->add('license_number', fn ($row) => $row->license_number)
            ->add('license_expiry_label', fn ($row) => Ui::expiry($row->license_expiry))
            ->add('vehicles_count')
            ->add('is_active_badge', fn ($row) => $row->is_active
                ? Ui::badge('Active', 'green')
                : Ui::badge('Inactive', 'gray'))
            ->add('row_actions', fn ($row) => Ui::rowActions(
                route('drivers.edit', $row->id),
                $row->id,
                'Delete driver '.$row->name.'? Assigned vehicles will become unassigned.'
            ));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),

            Column::make('Name', 'name')->sortable()->searchable(),

            Column::make('Phone', 'phone')->sortable()->searchable(),

            Column::make('Email', 'email')->sortable()->searchable(),

            Column::make('License No', 'license_number')->sortable()->searchable(),

            Column::make('License Expiry', 'license_expiry_label', 'license_expiry')->sortable(),

            Column::make('Vehicles', 'vehicles_count')->sortable(),

            Column::make('Status', 'is_active_badge', 'is_active')->sortable(),

            Column::make('Actions', 'row_actions')->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('license_number')->operators(['contains']),
            Filter::datepicker('license_expiry_label', 'license_expiry'),
            Filter::boolean('is_active_badge', 'is_active')->label('Active', 'Inactive'),
        ];
    }

    public function deleteRow(int $id): void
    {
        abort_unless(auth()->check(), 403);
        Driver::findOrFail($id)->delete();
    }
}
