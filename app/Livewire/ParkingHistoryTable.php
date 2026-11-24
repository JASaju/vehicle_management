<?php

namespace App\Livewire;

use App\Models\ParkingRecord;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ParkingHistoryTable extends PowerGridComponent
{
    public string $tableName = 'parking-history-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'parking-history')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return ParkingRecord::query()
            ->join('parking_slots', 'parking_records.parking_slot_id', '=', 'parking_slots.id')
            ->leftJoin('vehicles', 'parking_records.vehicle_id', '=', 'vehicles.id')
            ->select([
                'parking_records.*',
                'parking_slots.code as slot_code',
                'parking_slots.floor as floor_name',
                'vehicles.registration_no as vehicle_reg',
                'parking_records.detected_plate',
            ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('vehicle_reg', fn ($row) => $row->vehicle_reg ?: $row->detected_plate ?: 'Unknown vehicle')
            ->add('floor_name', fn ($row) => $row->floor_name)
            ->add('slot_code', fn ($row) => new HtmlString('<strong>'.e($row->slot_code).'</strong>'))
            ->add('parked_at_label', fn ($row) => $row->parked_at->format('d M Y H:i'))
            ->add('released_at_label', fn ($row) => $row->released_at
                ? $row->released_at->format('d M Y H:i')
                : Ui::badge('Still parked', 'yellow'))
            ->add('duration_label', fn ($row) => Ui::duration($row->parked_at, $row->released_at));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'parking_records.id')->sortable(),

            Column::make('Vehicle', 'vehicle_reg', 'vehicles.registration_no')
                ->sortable()
                ->searchable(),

            Column::make('Floor', 'floor_name', 'parking_slots.floor')->sortable()->searchable(),

            Column::make('Slot', 'slot_code', 'parking_slots.code')->sortable()->searchable(),

            Column::make('Parked at', 'parked_at_label', 'parking_records.parked_at')->sortable(),

            Column::make('Released at', 'released_at_label', 'parking_records.released_at')->sortable(),

            Column::make('Duration', 'duration_label'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('vehicle_reg', 'vehicles.registration_no')->operators(['contains']),
            Filter::inputText('slot_code', 'parking_slots.code')->operators(['contains']),
        ];
    }
}
