<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class VehicleTable extends PowerGridComponent
{
    public string $tableName = 'vehicle-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'vehicles')
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
        return Vehicle::query()
            ->join('vehicle_types', 'vehicles.vehicle_type_id', '=', 'vehicle_types.id')
            ->leftJoin('drivers', 'vehicles.driver_id', '=', 'drivers.id')
            // ps = the parking slot the vehicle occupies right now (at most one open record per vehicle).
            ->leftJoin('parking_records as pr', function ($join) {
                $join->on('pr.vehicle_id', '=', 'vehicles.id')->whereNull('pr.released_at');
            })
            ->leftJoin('parking_slots as ps', 'ps.id', '=', 'pr.parking_slot_id')
            ->select([
                'vehicles.*',
                'vehicle_types.name as type_name',
                'drivers.name as driver_name',
                'ps.code as slot_code',
            ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('registration_no', fn ($row) => $row->registration_no)
            ->add('brand_model', fn ($row) => $row->brand.' '.$row->model)
            ->add('type_name', fn ($row) => $row->type_name)
            ->add('driver_name', fn ($row) => $row->driver_name ?: '—')
            ->add('slot_label', fn ($row) => $row->slot_code ? Ui::badge($row->slot_code, 'blue') : '—')
            ->add('year')
            ->add('fuel_type_label', fn ($row) => ucfirst($row->fuel_type))
            ->add('odometer_label', fn ($row) => number_format($row->odometer).' km')
            ->add('insurance_expiry_label', fn ($row) => Ui::expiry($row->insurance_expiry))
            ->add('tax_token_expiry_label', fn ($row) => Ui::expiry($row->tax_token_expiry))
            ->add('fitness_expiry_label', fn ($row) => Ui::expiry($row->fitness_expiry))
            ->add('status_badge', fn ($row) => Ui::status($row->status))
            ->add('row_actions', fn ($row) => Ui::rowActions(
                route('vehicles.edit', $row->id),
                $row->id,
                'Delete vehicle '.$row->registration_no.'? Its maintenance and fuel records will be deleted too.',
                [['PDF', route('reports.vehicle', $row->id)]]
            ));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'vehicles.id')->sortable(),

            Column::make('Reg. No', 'registration_no', 'vehicles.registration_no')
                ->sortable()
                ->searchable(),

            Column::make('Vehicle', 'brand_model', 'vehicles.brand')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type_name', 'vehicle_types.name')
                ->sortable()
                ->searchable(),

            Column::make('Driver', 'driver_name', 'drivers.name')
                ->sortable()
                ->searchable(),

            Column::make('Parking', 'slot_label', 'ps.code')
                ->sortable()
                ->searchable(),

            Column::make('Year', 'year', 'vehicles.year')->sortable(),

            Column::make('Fuel', 'fuel_type_label', 'vehicles.fuel_type')->sortable(),

            Column::make('Odometer', 'odometer_label', 'vehicles.odometer')->sortable(),

            Column::make('Insurance', 'insurance_expiry_label', 'vehicles.insurance_expiry')->sortable(),

            Column::make('Tax Token', 'tax_token_expiry_label', 'vehicles.tax_token_expiry')->sortable(),

            Column::make('Fitness', 'fitness_expiry_label', 'vehicles.fitness_expiry')->sortable(),

            Column::make('Status', 'status_badge', 'vehicles.status')->sortable(),

            Column::make('Actions', 'row_actions')->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('registration_no', 'vehicles.registration_no')
                ->operators(['contains']),

            Filter::select('type_name', 'vehicles.vehicle_type_id')
                ->dataSource(VehicleType::orderBy('name')->get())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('status_badge', 'vehicles.status')
                ->dataSource(Ui::filterOptions(Vehicle::STATUSES))
                ->optionLabel('label')
                ->optionValue('value'),

            Filter::select('fuel_type_label', 'vehicles.fuel_type')
                ->dataSource(Ui::filterOptions(Vehicle::FUEL_TYPES))
                ->optionLabel('label')
                ->optionValue('value'),

            Filter::datepicker('insurance_expiry_label', 'vehicles.insurance_expiry'),
            Filter::datepicker('tax_token_expiry_label', 'vehicles.tax_token_expiry'),
            Filter::datepicker('fitness_expiry_label', 'vehicles.fitness_expiry'),
        ];
    }

    /** Called by wire:click="deleteRow(id)" in Ui::rowActions(). */
    public function deleteRow(int $id): void
    {
        abort_unless(auth()->check(), 403);
        Vehicle::findOrFail($id)->delete();
    }
}
