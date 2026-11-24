<?php # [BlazeVersion]:{v3} ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'inline' => null,
    'date' => null,
    'column' => null,
    'tableName' => null,
    'type' => 'datetime',
    'filter' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'inline' => null,
    'date' => null,
    'column' => null,
    'tableName' => null,
    'type' => 'datetime',
    'filter' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $deferred = (bool) data_get($filter, 'deferred');
    $filtersProperty = data_get($filter, 'filtersProperty');
    $params = data_get($filter, 'params');
    $field = data_get($filter, 'field');
    $keyField = data_get($filter, 'modelKey');
    $title = data_get($column, 'title');

    $customConfig = [];
    if ($params) {
        foreach ($params as $key => $value) {
            $customConfig[$key] = $value;
        }
    }

    $params = [
        'type' => $type,
        'dataField' => $field,
        'keyField' => $keyField,
        'tableName' => $tableName,
        'deferred' => $deferred,
        'filtersProperty' => $filtersProperty,
        'filterKey' => 'enabledFilters.datetime.' . $field,
        'label' => $title,
        'locale' => config('livewire-powergrid.plugins.flatpickr.locales.' . app()->getLocale()),
        'onlyFuture' => data_get($customConfig, 'only_future', false),
        'noWeekEnds' => data_get($customConfig, 'no_weekends', false),
        'customConfig' => $customConfig,
    ];
?>
<div
    wire:ignore
    x-data="pgFlatpickr"
    data-pg-params="<?php echo e(json_encode($params)); ?>"
>
    <div
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([theme('filter.date_picker.base'), 'space-y-1' => !$inline]); ?>"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$inline): ?>
            <label class="<?php echo e(theme('filter.label', 'block text-sm font-semibold text-zinc-700 dark:text-zinc-300')); ?>">
                <?php echo e($title); ?>

            </label>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <form autocomplete="off">
            <input
                id="input_<?php echo e($field); ?>"
                x-ref="rangeInput"
                <?php echo e(data_get($filter, 'wire.formatted')); ?>

                autocomplete="off"
                data-field="<?php echo e($field); ?>"
                class="<?php echo e(theme('filter.date_picker.input')); ?> <?php echo e(data_get($column, 'headerClass')); ?>"
                type="text"
                readonly
                placeholder="<?php echo e(trans('livewire-powergrid::datatable.placeholders.select')); ?>"
            >
        </form>
    </div>
</div>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\src\Plugins/Flatpickr/index.blade.php ENDPATH**/ ?>