<?php # [BlazeVersion]:{v3} ?>
<?php
    use PowerComponents\Turbine\DataSource\DataTransformer;
    use PowerComponents\LivewirePowerGrid\PowerGridComponent;

    $dataTransformer = new DataTransformer($this);

    /** @var PowerGridComponent $this */
?>

<div
    class="pg-container <?php echo e(theme('layout.container')); ?>"
    <?php if($deferLoading): ?> wire:init="fetchDatasource" <?php endif; ?>
>
    <?php echo $this->renderPluginAssets(); ?>


    <?php echo $this->renderPluginZone('top'); ?>


    <div
        id="power-grid-table-container-<?php echo e($tableName); ?>"
        class="pg-table-wrapper <?php echo e(theme('layout.card', theme('layout.wrapper'))); ?>"
    >
        <?php echo $__env->make(theme_view('header'), [
            'enabledFilters' => $enabledFilters,
            '__partial' => $this,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->usesFilterFlyout() && ! $this->filterBuilderHidesDefaultFilters()): ?>
            <?php
                $filtersFromColumns = collect($columns)
                    ->filter(fn($column) => filled(data_get($column, 'filters')));
            ?>

            <?php echo $__env->make(theme_view($this->filterPanelView()), [
                '__partial' => $this,
                'tableName' => $tableName,
                'filtersFromColumns' => $filtersFromColumns,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div>
            <div
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'pg-table-responsive',
                    'overflow-auto' => $readyToLoad,
                    'overflow-hidden' => !$readyToLoad,
                    theme('table.layout.container'),
                ]); ?>"
                <?php if(isset($this->setUp['responsive'])): ?> x-data="pgResponsive" <?php endif; ?>
            >
                <div>
                    <table
                        id="table_base_<?php echo e($tableName); ?>"
                        class="pg-table <?php echo e(theme('table.layout.table')); ?>"
                    >
                        <?php echo $__env->make(theme_view('table.thead'), [
                            'loading' => false,
                            '__partial' => $this,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($readyToLoad): ?>
                            <?php echo $__env->make(theme_view('table.tbody'), ['__partial' => $this], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php else: ?>
                            <?php echo $__env->make(theme_view('table.tbody'), ['__partial' => $this, 'loading' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </table>
                </div>
            </div>

            <?php echo $__env->make(theme_view('footer'), ['__partial' => $this], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/structure/table.blade.php ENDPATH**/ ?>