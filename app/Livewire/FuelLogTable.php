<?php

namespace App\Livewire;

use App\Models\FuelLog;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class FuelLogTable extends PowerGridComponent
{
    public string $tableName = 'fuel-log-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'fuel-logs')
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
        return FuelLog::query()
            ->join('vehicles', 'fuel_logs.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('drivers', 'fuel_logs.driver_id', '=', 'drivers.id')
            ->select([
                'fuel_logs.*',
                'vehicles.registration_no as vehicle_reg',
                'drivers.name as driver_name',
            ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('vehicle_reg', fn ($row) => $row->vehicle_reg)
            ->add('driver_name', fn ($row) => $row->driver_name ?: '—')
            ->add('fuel_date_label', fn ($row) => Ui::date($row->fuel_date))
            ->add('liters_label', fn ($row) => number_format((float) $row->liters, 2).' L')
            ->add('price_label', fn ($row) => Ui::money($row->price_per_liter))
            ->add('total_label', fn ($row) => Ui::money($row->total_cost))
            ->add('odometer_label', fn ($row) => $row->odometer !== null ? number_format($row->odometer).' km' : '—')
            ->add('station', fn ($row) => $row->station ?: '—')
            ->add('row_actions', fn ($row) => Ui::rowActions(
                route('fuel-logs.edit', $row->id),
                $row->id,
                'Delete this fuel record?'
            ));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'fuel_logs.id')->sortable(),

            Column::make('Vehicle', 'vehicle_reg', 'vehicles.registration_no')
                ->sortable()
                ->searchable(),

            Column::make('Driver', 'driver_name', 'drivers.name')
                ->sortable()
                ->searchable(),

            Column::make('Date', 'fuel_date_label', 'fuel_logs.fuel_date')->sortable(),

            Column::make('Liters', 'liters_label', 'fuel_logs.liters')->sortable(),

            Column::make('Price / L', 'price_label', 'fuel_logs.price_per_liter')->sortable(),

            Column::make('Total', 'total_label', 'fuel_logs.total_cost')->sortable(),

            Column::make('Odometer', 'odometer_label', 'fuel_logs.odometer')->sortable(),

            Column::make('Station', 'station', 'fuel_logs.station')
                ->sortable()
                ->searchable(),

            Column::make('Actions', 'row_actions')->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('vehicle_reg', 'vehicles.registration_no')
                ->operators(['contains']),

            Filter::inputText('driver_name', 'drivers.name')
                ->operators(['contains']),

            Filter::datepicker('fuel_date_label', 'fuel_logs.fuel_date'),
        ];
    }

    public function deleteRow(int $id): void
    {
        abort_unless(auth()->check(), 403);
        FuelLog::findOrFail($id)->delete();
    }
}
