<?php

namespace App\Livewire;

use App\Models\ParkingSlot;
use App\Support\Ui;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ParkingSlotTable extends PowerGridComponent
{
    public string $tableName = 'parking-slot-table';

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
        // pr = the open parking record (released_at IS NULL), v = its vehicle.
        return ParkingSlot::query()
            ->leftJoin('parking_records as pr', function ($join) {
                $join->on('pr.parking_slot_id', '=', 'parking_slots.id')
                    ->whereNull('pr.released_at');
            })
            ->leftJoin('vehicles as v', 'v.id', '=', 'pr.vehicle_id')
            ->select([
                'parking_slots.*',
                'v.registration_no as vehicle_reg',
                'pr.parked_at as parked_at',
            ])
            ->withCount('records');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('floor', fn ($row) => $row->floor)
            ->add('code', fn ($row) => new HtmlString('<strong>'.e($row->code).'</strong>'))
            ->add('zone', fn ($row) => $row->zone)
            ->add('state_badge', function ($row) {
                if ($row->vehicle_reg) {
                    return Ui::badge('Occupied', 'yellow');
                }

                return $row->is_active ? Ui::badge('Free', 'green') : Ui::badge('Out of service', 'gray');
            })
            ->add('vehicle_label', fn ($row) => $row->vehicle_reg ?: '—')
            ->add('since_label', fn ($row) => $row->parked_at
                ? Carbon::parse($row->parked_at)->format('d M Y H:i')
                : '—')
            ->add('row_actions', fn ($row) => Ui::rowActions(
                route('parking-slots.edit', $row->id),
                $row->id,
                'Delete slot '.$row->code.'?',
                [],
                (int) $row->records_count === 0 && ! $row->vehicle_reg
            ));
    }

    public function columns(): array
    {
        return [
            Column::make('Floor', 'floor', 'parking_slots.floor')->sortable()->searchable(),

            Column::make('Slot', 'code', 'parking_slots.code')->sortable()->searchable(),

            Column::make('Zone', 'zone', 'parking_slots.zone')->sortable(),

            Column::make('State', 'state_badge'),

            Column::make('Vehicle', 'vehicle_label', 'v.registration_no')->searchable(),

            Column::make('Parked since', 'since_label'),

            Column::make('Actions', 'row_actions'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('floor', 'parking_slots.floor')->operators(['contains']),
            Filter::inputText('code', 'parking_slots.code')->operators(['contains']),
        ];
    }

    public function deleteRow(int $id): void
    {
        abort_unless(auth()->check(), 403);

        $slot = ParkingSlot::withCount('records')->findOrFail($id);

        // A slot with any parking history (or a vehicle in it) cannot be deleted: take it out of service instead.
        if (
            $slot->records_count > 0
            || in_array(strtoupper($slot->code), config('parking_sensors.slots', []), true)
        ) {
            return;
        }

        $slot->delete();
    }
}
