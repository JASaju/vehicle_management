<?php # [BlazeVersion]:{v3} ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($setUp, 'header.softDeletes')): ?>
    <?php ($element = ($__partial ?? $this)->headerElement('softDeletes')); ?>
    <div
        x-data="pgDropdown"
        @click.outside="close()"
        class="<?php echo e(theme('header.soft_deletes.wrapper')); ?>"
    >
        <button
            @click.prevent="toggle()"
            title="<?php echo e($element['title']); ?>"
            aria-label="<?php echo e($element['title']); ?>"
            class="<?php echo e(theme('header.soft_deletes.button', theme('header.layout.actions'))); ?>"
        >
            <div class="flex items-center">
                <?php echo $element['iconHtml']; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($element['showLabel']): ?>
                    <span class="<?php echo e(theme('header.soft_deletes.label')); ?>"><?php echo e($element['title']); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </button>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transform duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transform duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="<?php echo e(theme('header.soft_deletes.menu')); ?>"
        >

            <div
                x-on:click="dispatchClose('pg:softDeletes-<?php echo e(js_embed($tableName)); ?>', 'softDeletes', '')"
                class="<?php echo e(theme('header.soft_deletes.menu_item')); ?>"
            >
                <?php echo app('translator')->get('livewire-powergrid::datatable.soft_deletes.without_trashed'); ?>
            </div>
            <div
                x-on:click="dispatchClose('pg:softDeletes-<?php echo e(js_embed($tableName)); ?>', 'softDeletes', 'withTrashed')"
                class="<?php echo e(theme('header.soft_deletes.menu_item')); ?>"
            >
                <?php echo app('translator')->get('livewire-powergrid::datatable.soft_deletes.with_trashed'); ?>
            </div>
            <div
                x-on:click="dispatchClose('pg:softDeletes-<?php echo e(js_embed($tableName)); ?>', 'softDeletes', 'onlyTrashed')"
                class="<?php echo e(theme('header.soft_deletes.menu_item')); ?>"
            >
                <?php echo app('translator')->get('livewire-powergrid::datatable.soft_deletes.only_trashed'); ?>
            </div>

        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/themes/tailwind/header/soft-deletes.blade.php ENDPATH**/ ?>