<?php # [BlazeVersion]:{v3} ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($setUp, 'header.toggleColumns')): ?>
    <?php
        $__partial = $__partial ?? $this;
        $tableName = $tableName ?? $__partial->tableName;
        $element = $__partial->headerElement('toggleColumns');
        $hiddenCount = $__partial->hiddenColumnsCount();
    ?>
    <div
        x-data="{ open: false }"
        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'pg-toggle-columns-'.e($tableName).''; ?>wire:key="pg-toggle-columns-<?php echo e($tableName); ?>"
        class="relative <?php echo e(theme('filter.dropdown.wrapper')); ?>"
    >
        <button
            type="button"
            data-cy="toggle-columns-<?php echo e($tableName); ?>"
            x-on:click="open = ! open"
            title="<?php echo e($element['title']); ?>"
            aria-label="<?php echo e($element['title']); ?>"
            class="<?php echo e(theme('header.toggle_columns.button', theme('header.layout.actions'))); ?> relative"
        >
            <div class="flex items-center">
                <?php echo $element['iconHtml']; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($element['showLabel']): ?>
                    <span class="<?php echo e(theme('header.toggle_columns.label')); ?>"><?php echo e($element['title']); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hiddenCount): ?>
                <span
                    data-cy="toggle-columns-badge"
                    class="<?php echo e(theme('filter.dropdown.badge')); ?>"
                ><?php echo e($hiddenCount); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </button>

        <div
            x-show="open"
            x-cloak
            style="display: none"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            x-on:click.outside="open = false"
            x-on:keydown.escape.window="open = false"
            role="dialog"
            data-cy="toggle-columns-panel"
            class="<?php echo e(theme('header.toggle_columns.panel', theme('filter.dropdown.panel'))); ?>"
        >
            <div class="<?php echo e(theme('filter.dropdown.header')); ?>">
                <span class="<?php echo e(theme('filter.dropdown.title')); ?>"><?php echo e($element['title']); ?></span>
            </div>

            <div class="<?php echo e(theme('filter.dropdown.body')); ?> space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $__partial->visibleColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php ($field = data_get($column, 'field')); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_string($field) && $field !== '' && ! data_get($column, 'isAction')): ?>
                        <label
                            <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'toggle-column-'.e($field).''; ?>wire:key="toggle-column-<?php echo e($field); ?>"
                            class="flex items-center gap-2.5 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                data-cy="toggle-field-<?php echo e($field); ?>"
                                wire:model="draftColumns.<?php echo e($field); ?>"
                                class="<?php echo e(theme('header.toggle_columns.checkbox', theme('table.checkbox.input'))); ?>"
                            >
                            <span class="<?php echo e(theme('header.toggle_columns.item_label', 'text-sm text-zinc-700 dark:text-zinc-200')); ?>"><?php echo data_get($column, 'title'); ?></span>
                        </label>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <div class="<?php echo e(theme('filter.dropdown.footer')); ?>">
                <button
                    type="button"
                    data-cy="toggle-columns-reset"
                    wire:click.prevent="resetColumns"
                    class="<?php echo e(theme('filter.dropdown.reset')); ?> me-auto"
                >
                    <?php echo e(trans('livewire-powergrid::datatable.buttons.reset_filters')); ?>

                </button>

                <button
                    type="button"
                    data-cy="toggle-columns-apply"
                    wire:click.prevent="applyColumns"
                    x-on:click="open = false"
                    class="<?php echo e(theme('filter.dropdown.apply')); ?>"
                >
                    <?php echo e(trans('livewire-powergrid::datatable.buttons.apply_columns')); ?>

                </button>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/themes/tailwind/header/toggle-columns.blade.php ENDPATH**/ ?>