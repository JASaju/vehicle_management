<?php # [BlazeVersion]:{v3} ?>
<?php
    $__partial = $__partial ?? $this;
    $setUp = $setUp ?? $__partial->setUp;
    $tableName = $tableName ?? $__partial->tableName;
    $multiSort = $multiSort ?? $__partial->multiSort;
?>
<div>
    <?php if ($__env->exists(data_get($setUp, 'header.includeViewOnTop'), ['__partial' => $__partial])) echo $__env->make(data_get($setUp, 'header.includeViewOnTop'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="<?php echo e(theme('header.layout.container')); ?>">
        
        <div class="<?php echo e(theme('header.layout.actions_container')); ?>">
            <?php echo $__partial->renderHeaderActions(); ?>

            <?php if ($__env->exists(theme_view('header.soft-deletes'), ['__partial' => $__partial])) echo $__env->make(theme_view('header.soft-deletes'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->renderWhen(boolval(data_get($setUp, 'header.wireLoading')),
                theme_view('header.loading'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>

        
        <div class="<?php echo e(theme('header.layout.sub_container')); ?> flex flex-row flex-wrap items-center gap-2 w-full mt-2 md:mt-0 md:max-w-xl <?php echo e($__partial->headerControlsAlignClass()); ?>">
            <?php if ($__env->exists(theme_view('header.toggle-columns'), ['__partial' => $__partial])) echo $__env->make(theme_view('header.toggle-columns'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__partial->renderPluginZone('header'); ?>

            <div class="relative flex flex-1 min-w-0 flex-row items-center gap-2">
                <?php echo $__partial->renderPluginZone('header.filter'); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__partial->usesFilterFlyout() && count($__partial->declaredFilters()) > 0 && ! $__partial->filterBuilderHidesDefaultFilters()): ?>
                    <?php if ($__env->exists(theme_view('header.filters'), ['__partial' => $__partial])) echo $__env->make(theme_view('header.filters'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__partial->usesFilterDropdown() && count($__partial->declaredFilters()) > 0 && ! $__partial->filterBuilderHidesDefaultFilters()): ?>
                    <?php echo $__env->make(theme_view($__partial->filterPanelView()), [
                        '__partial' => $__partial,
                        'tableName' => $tableName,
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="flex-1 min-w-0">
                    <?php echo $__env->make(theme_view('header.search'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($__env->exists(theme_view('header.enabled-filters'), ['__partial' => $__partial])) echo $__env->make(theme_view('header.enabled-filters'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php ($headerBottomZone = $__partial->renderPluginZone('header.bottom')); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(trim($headerBottomZone) !== ''): ?>
        <div class="px-4 pb-3">
            <?php echo $headerBottomZone; ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo $__env->renderWhen($multiSort, theme_view('header.multi-sort'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php if ($__env->exists(data_get($setUp, 'header.includeViewOnBottom'), ['__partial' => $__partial])) echo $__env->make(data_get($setUp, 'header.includeViewOnBottom'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if ($__env->exists(theme_view('header.message-soft-deletes'), ['__partial' => $__partial])) echo $__env->make(theme_view('header.message-soft-deletes'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/themes/tailwind/header.blade.php ENDPATH**/ ?>