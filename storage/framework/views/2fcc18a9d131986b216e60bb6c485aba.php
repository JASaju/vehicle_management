<?php # [BlazeVersion]:{v3} ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'loading' => false,
    '__partial' => null,
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
    'loading' => false,
    '__partial' => null,
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
    $setUp = $__partial->setUp;
    $tableName = $__partial->tableName;
    $columns = $__partial->columns;
    $enabledFilters = $__partial->enabledFilters;
    $radio = $__partial->radio;
    $checkbox = $__partial->checkbox;
    $actions = $__partial->actions ?? [];
?>
<tr
    class="<?php echo e(theme('table.layout.tr')); ?> <?php echo e(theme('table.layout.thead_tr')); ?>"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loading): ?>
        <td
            class="<?php echo e(theme('table.layout.td')); ?>"
            colspan="999"
        >
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__partial->loadingComponent): ?>
                <?php echo $__env->make($__partial->loadingComponent, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <?php echo e(__('Loading')); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </td>
    <?php else: ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($setUp, 'detail.showCollapseIcon')): ?>
            <th
                scope="col"
                class="<?php echo e(theme('table.layout.th')); ?>"
                <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'show-collapse-'.e($tableName).''; ?>wire:key="show-collapse-<?php echo e($tableName); ?>"
            >
            </th>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($setUp['responsive'])): ?>
            <th
                fixed
                x-show="hasHiddenElements"
                class="<?php echo e(theme('table.layout.th')); ?>"
            >
            </th>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($radio): ?>
            <th
                class="<?php echo e(theme('table.layout.th')); ?>"
            >
            </th>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($checkbox): ?>
            <?php echo $__env->make(theme_view('table.checkbox-all'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php echo $__env->make(theme_view('table.cols'), [
            'columns' => $columns,
            'enabledFilters' => $enabledFilters,
            'setUp' => $setUp,
            'tableName' => $tableName,
            'multiSort' => $__partial->multiSort,
            'sortArray' => $__partial->sortArray,
            'sortField' => $__partial->sortField,
            'sortDirection' => $__partial->sortDirection,
            '__partial' => $__partial,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($actions) && count($actions)): ?>
            <?php
                $responsiveActionsColumnName =
                    \PowerComponents\Turbine\Components\SetUp\Responsive::ACTIONS_COLUMN_NAME;

                $isActionFixedOnResponsive =
                    isset($setUp['responsive']) &&
                    in_array($responsiveActionsColumnName, data_get($setUp, 'responsive.fixedColumns'))
                        ? true
                        : false;
            ?>

            <th
                <?php if($isActionFixedOnResponsive): ?> fixed <?php endif; ?>
                class="<?php echo e(theme('table.layout.th_actions')); ?>"
                scope="col"
                colspan="999"
                <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = ''.e(md5('actions')).''; ?>wire:key="<?php echo e(md5('actions')); ?>"
            >
                <?php echo e(trans('livewire-powergrid::datatable.labels.action')); ?>

            </th>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tr>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/structure/table/header.blade.php ENDPATH**/ ?>