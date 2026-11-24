<?php # [BlazeVersion]:{v3} ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'element' => [],
    'tableName' => null,
    'types' => [],
    'total' => 0,
    'enabledFiltersCount' => 0,
    'checkbox' => false,
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
    'element' => [],
    'tableName' => null,
    'types' => [],
    'total' => 0,
    'enabledFiltersCount' => 0,
    'checkbox' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    class="mt-2 sm:mt-0"
    id="pg-header-export"
    x-data="pgExport"
    x-on:keydown.esc="close()"
    x-on:click.outside="close()"
>
    <button
        @click.prevent="openMenu()"
        title="<?php echo e(data_get($element, 'title')); ?>"
        aria-label="<?php echo e(data_get($element, 'title')); ?>"
        class="<?php echo e(theme('header.export.button', theme('header.layout.actions'))); ?>"
    >
        <div class="flex items-center">
            <?php echo data_get($element, 'iconHtml'); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($element, 'showLabel')): ?>
                <span class="<?php echo e(theme('header.export.label')); ?>"><?php echo e(data_get($element, 'title')); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-10 mt-2 rounded-md dark:bg-zinc-700 bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        tabindex="-1"
        @keydown.tab="close()"
        @keydown.enter.prevent="close()"
        @keyup.space.prevent="close()"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array('xlsx', $types)): ?>
            <div class="flex items-center px-4 py-1 text-zinc-400 dark:text-zinc-300 border-b border-zinc-100 dark:border-zinc-600">
                <span class="w-12"><?php echo app('translator')->get('XLSX'); ?></span>
                <button
                    wire:click.prevent="exportToXLS"
                    x-on:click="close()"
                    href="#"
                    class="px-2 py-1 block text-zinc-800 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800 rounded"
                >
                    <span class="export-count text-xs">(<?php echo e($total); ?>)</span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enabledFiltersCount === 0): ?>
                        <?php echo app('translator')->get('livewire-powergrid::datatable.labels.all'); ?>
                    <?php else: ?>
                        <?php echo app('translator')->get('livewire-powergrid::datatable.labels.filtered'); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($checkbox): ?>
                    <button wire:click.prevent="exportToXLS(true)"
                       x-on:click="close()"
                       x-bind:disabled="isEmpty()"
                       :class="disabledClass()"
                       class="px-2 py-1 block text-zinc-800 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800 rounded"
                    >
                        <span class="export-count text-xs" x-text="countLabel()"></span> <?php echo app('translator')->get('livewire-powergrid::datatable.labels.selected'); ?>
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array('csv', $types)): ?>
            <div class="flex items-center px-4 py-1 text-zinc-400 dark:text-zinc-300">
                <span class="w-12"><?php echo app('translator')->get('Csv'); ?></span>
                <button
                    wire:click.prevent="exportToCsv"
                    x-on:click="close()"
                    class="px-2 py-1 block text-zinc-800 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800 rounded"
                >
                    <span class="export-count text-xs">(<?php echo e($total); ?>)</span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enabledFiltersCount === 0): ?>
                        <?php echo app('translator')->get('livewire-powergrid::datatable.labels.all'); ?>
                    <?php else: ?>
                        <?php echo app('translator')->get('livewire-powergrid::datatable.labels.filtered'); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($checkbox): ?>
                    <button
                        wire:click.prevent="exportToCsv(true)"
                        x-on:click="close()"
                        x-bind:disabled="isEmpty()"
                        :class="disabledClass()"
                        class="px-2 py-1 block text-zinc-800 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800 rounded"
                    >
                        <span class="export-count text-xs" x-text="countLabel()"></span> <?php echo app('translator')->get('livewire-powergrid::datatable.labels.selected'); ?>
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\src\Plugins/Export/themes/index.blade.php ENDPATH**/ ?>