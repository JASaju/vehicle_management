<?php

namespace App\Livewire;

use App\Models\Maintenance;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class MaintenanceTable extends PowerGridComponent
{
    public string $tableName = 'maintenance-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'maintenance')
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
        return Maintenance::query()
            ->join('vehicles', 'maintenances.vehicle_id', '=', 'vehicles.id')
            ->select([
                'maintenances.*',
                'vehicles.registration_no as vehicle_reg',
            ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('vehicle_reg', fn ($row) => $row->vehicle_reg)
            ->add('type_label', fn ($row) => Str::headline($row->type))
            ->add('description', fn ($row) => $row->description ?: '—')
            ->add('service_date_label', fn ($row) => Ui::date($row->service_date))
            ->add('next_service_date_label', fn ($row) => Ui::date($row->next_service_date))
            ->add('odometer_label', fn ($row) => $row->odometer !== null ? number_format($row->odometer).' km' : '—')
            ->add('cost_label', fn ($row) => Ui::money($row->cost))
            ->add('workshop', fn ($row) => $row->workshop ?: '—')
            ->add('status_badge', fn ($row) => Ui::status($row->status))
            ->add('row_actions', fn ($row) => Ui::rowActions(
                route('maintenances.edit', $row->id),
                $row->id,
                'Delete this maintenance record?'
            ));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'maintenances.id')->sortable(),

            Column::make('Vehicle', 'vehicle_reg', 'vehicles.registration_no')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type_label', 'maintenances.type')->sortable(),

            Column::make('Description', 'description', 'maintenances.description')
                ->sortable()
                ->searchable(),

            Column::make('Service Date', 'service_date_label', 'maintenances.service_date')->sortable(),

            Column::make('Next Service', 'next_service_date_label', 'maintenances.next_service_date')->sortable(),

            Column::make('Odometer', 'odometer_label', 'maintenances.odometer')->sortable(),

            Column::make('Cost', 'cost_label', 'maintenances.cost')->sortable(),

            Column::make('Workshop', 'workshop', 'maintenances.workshop')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status_badge', 'maintenances.status')->sortable(),

            Column::make('Actions', 'row_actions')->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('vehicle_reg', 'vehicles.registration_no')
                ->operators(['contains']),

            Filter::select('type_label', 'maintenances.type')
                ->dataSource(Ui::filterOptions(Maintenance::TYPES))
                ->optionLabel('label')
                ->optionValue('value'),

            Filter::select('status_badge', 'maintenances.status')
                ->dataSource(Ui::filterOptions(Maintenance::STATUSES))
                ->optionLabel('label')
                ->optionValue('value'),

            Filter::datepicker('service_date_label', 'maintenances.service_date'),
            Filter::datepicker('next_service_date_label', 'maintenances.next_service_date'),
        ];
    }

    public function deleteRow(int $id): void
    {
        abort_unless(auth()->check(), 403);
        $maintenance = Maintenance::with('vehicle')->findOrFail($id);
        $vehicle = $maintenance->vehicle;
        $maintenance->delete();

        // A deleted "in progress" job may have been the only reason the vehicle was in the workshop.
        $vehicle?->syncMaintenanceStatus();
    }
}
