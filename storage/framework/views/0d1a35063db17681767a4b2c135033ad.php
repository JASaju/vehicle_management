<?php # [BlazeVersion]:{v3} ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'tableName' => null,
    '__partial' => null,
    'loading' => false,
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
    'tableName' => null,
    '__partial' => null,
    'loading' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $__partial = $__partial ?? $this;
    $tableName = $tableName ?? $__partial->tableName;
?>

<thead
    wire:partial="pg-thead-<?php echo e($tableName); ?>"
    <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'thead-'.e($tableName).''; ?>wire:key="thead-<?php echo e($tableName); ?>"
    class="pg-thead <?php echo e(theme('table.layout.thead')); ?>"
>
    <?php echo $__env->make(theme_view('table.header'), [
        'loading' => $loading,
        '__partial' => $__partial,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</thead>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/partials/thead.blade.php ENDPATH**/ ?>