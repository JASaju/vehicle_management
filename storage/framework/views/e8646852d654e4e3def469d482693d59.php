<?php # [BlazeVersion]:{v3} ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($setUp, 'header.searchInput')): ?>
    <?php ($element = ($__partial ?? $this)->headerElement('search')); ?>
    <?php ($clearElement = ($__partial ?? $this)->headerElement('searchClear')); ?>
    <div class="<?php echo e(theme('header.search_box.container')); ?>">
        <label class="<?php echo e(theme('header.search_box.relative_main')); ?>">
            <span class="<?php echo e(theme('header.search_box.icon_search_wrapper')); ?>">
                <?php echo $element['iconHtml']; ?>

            </span>
            <input
                wire:model.live.debounce.700ms="search"
                wire:partial.ignore="pg-search-<?php echo e($tableName); ?>"
                type="text"
                class="<?php echo e(theme('header.search_box.input')); ?>"
                placeholder="<?php echo e($element['title']); ?>"
            >
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
                <span class="<?php echo e(theme('header.search_box.icon_close_wrapper')); ?>">
                    <a wire:click.prevent="$set('search','')" class="p-1 cursor-pointer rounded-full focus:outline-none">
                        <?php echo $clearElement['iconHtml']; ?>

                    </a>
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/themes/tailwind/header/search.blade.php ENDPATH**/ ?>