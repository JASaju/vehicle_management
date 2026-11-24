<?php # [BlazeVersion]:{v3} ?>
<div
    wire:partial="pg-enabled-filters-<?php echo e($__partial->tableName); ?>"
    class="<?php echo \Illuminate\Support\Arr::toCssClasses(['pg-enabled-filters-base flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700' => count($enabledFilters)]); ?>"
    <?php if(count($enabledFilters)): ?> data-cy="enabled-filters" <?php endif; ?>
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($enabledFilters)): ?>
        <?php
            $element = $__partial->headerElement('clearFilters');
        ?>

        <div class="flex flex-wrap items-center gap-2">
            <span class="<?php echo e(theme('header.enabled_filters.label')); ?>">
                <?php echo e(trans('livewire-powergrid::datatable.labels.active_filters')); ?>

            </span>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $enabledFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($filter['label'])): ?>
                    <?php
                        $isBuilderPill = ($filter['source'] ?? null) === 'filterBuilder';
                        $pillClick = $isBuilderPill
                            ? 'clearFilterBuilderRow('.intval($filter['index'] ?? 0).')'
                            : 'clearFilter('.\Illuminate\Support\Js::from((string) ($filter['field'] ?? '')).')';
                    ?>
                    <span
                        role="button"
                        data-cy="enabled-filters-clear-<?php echo e($isBuilderPill ? 'fb-'.($filter['index'] ?? 0) : $filter['field']); ?>"
                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'enabled-filters-'.e($isBuilderPill ? 'fb-'.($filter['index'] ?? 0) : $filter['field']).''; ?>wire:key="enabled-filters-<?php echo e($isBuilderPill ? 'fb-'.($filter['index'] ?? 0) : $filter['field']); ?>"
                        wire:click.prevent="<?php echo e($pillClick); ?>"
                        class="<?php echo e(theme('header.enabled_filters.pill')); ?>"
                    >
                        <?php echo e($filter['label']); ?><?php echo e(isset($filter['value']) ? ': '.$filter['value'] : ''); ?>

                        <?php echo $element['iconHtml']; ?>

                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        <button
            type="button"
            role="button"
            data-cy="enabled-filters-clear-all"
            wire:click.prevent="clearAllFilters"
            title="<?php echo e($element['title']); ?>"
            aria-label="<?php echo e($element['title']); ?>"
            class="<?php echo e(theme('header.enabled_filters.pill_clear_all')); ?>"
        >
            <?php echo $element['iconHtml']; ?>

        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/themes/tailwind/header/enabled-filters.blade.php ENDPATH**/ ?>