<?php # [BlazeVersion]:{v3} ?>
<div
    class="<?php echo e(theme('pagination.wrapper', 'items-center justify-between sm:flex gap-2')); ?>"
    wire:loading.class="blur-[2px]"
    wire:target="loadMore"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->count() > 0): ?>
        <div class="<?php echo e(theme('pagination.count_wrapper', 'items-center justify-between w-full sm:flex-1 sm:flex')); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recordCount === 'full'): ?>
                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['mr-3' => $paginator->hasPages()]); ?>">
                    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'mr-2' => $paginator->hasPages(),
                        theme('pagination.records_text', 'leading-5 text-center text-zinc-700 text-md dark:text-zinc-300 sm:text-right'),
                    ]); ?>">
                        <?php echo e(trans('livewire-powergrid::datatable.pagination.showing')); ?>

                        <span class="font-semibold firstItem"><?php echo e($paginator->firstItem()); ?></span>
                        <?php echo e(trans('livewire-powergrid::datatable.pagination.to')); ?>

                        <span class="font-semibold lastItem"><?php echo e($paginator->lastItem()); ?></span>
                        <?php echo e(trans('livewire-powergrid::datatable.pagination.of')); ?>

                        <span class="font-semibold total"><?php echo e($paginator->total()); ?></span>
                        <?php echo e(trans('livewire-powergrid::datatable.pagination.results')); ?>

                    </div>
                </div>
            <?php elseif($recordCount === 'short'): ?>
                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['mr-3' => $paginator->hasPages()]); ?>">
                    <p class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'mr-2' => $paginator->hasPages(),
                        theme('pagination.records_text', 'leading-5 text-center text-zinc-700 text-md dark:text-zinc-300 sm:text-right'),
                    ]); ?>">
                        <span class="font-semibold firstItem"> <?php echo e($paginator->firstItem()); ?></span>
                        -
                        <span class="font-semibold lastItem"> <?php echo e($paginator->lastItem()); ?></span>
                        |
                        <span class="font-semibold total"> <?php echo e($paginator->total()); ?></span>
                    </p>
                </div>
            <?php elseif($recordCount === 'min'): ?>
                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['mr-3' => $paginator->hasPages()]); ?>">
                    <p class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'mr-2' => $paginator->hasPages(),
                        theme('pagination.records_text', 'leading-5 text-center text-zinc-700 text-md dark:text-zinc-300 sm:text-right'),
                    ]); ?>">
                        <span class="font-semibold firstItem"> <?php echo e($paginator->firstItem()); ?></span>
                        -
                        <span class="font-semibold lastItem"> <?php echo e($paginator->lastItem()); ?></span>
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasPages() && !in_array($recordCount, ['min', 'short'])): ?>
                <nav
                    role="navigation"
                    aria-label="Pagination Navigation"
                    class="items-center justify-between sm:flex"
                >
                    <div class="<?php echo e(theme('pagination.nav_wrapper', 'flex justify-center mt-2 md:flex-none md:justify-end sm:mt-0')); ?>">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$paginator->onFirstPage()): ?>
                            <a
                                class="<?php echo e(theme('pagination.item_first', 'cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-zinc-500 dark:text-zinc-300 bg-white dark:bg-zinc-600 border border-zinc-300 dark:border-transparent rounded-l-md leading-5 hover:text-zinc-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500 transition ease-in-out duration-150')); ?>"
                                wire:click="gotoPage(1, '<?php echo e($paginator->getPageName()); ?>')"
                            >
                                <svg
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"
                                    />
                                </svg>
                            </a>

                            <a
                                class="<?php echo e(theme('pagination.item_prev', 'cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-zinc-500 dark:text-zinc-300 bg-white dark:bg-zinc-600 border border-zinc-300 dark:border-transparent leading-5 hover:text-zinc-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500 transition ease-in-out duration-150')); ?>"
                                wire:click="previousPage('<?php echo e($paginator->getPageName()); ?>')"
                                rel="next"
                            >
                                <svg
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 19.5 8.25 12l7.5-7.5"
                                    />
                                </svg>

                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($element)): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page == $paginator->currentPage()): ?>
                                        <span
                                            class="<?php echo e(theme('pagination.item_active', 'select-none relative z-10 inline-flex items-center px-3 py-2 -ml-px text-sm font-bold text-accent-700 dark:text-zinc-300 bg-accent-100 dark:bg-zinc-700 border border-accent-300 dark:border-transparent cursor-default select-none')); ?>"
                                        ><?php echo e($page); ?></span>
                                    <?php elseif(
                                        $page === $paginator->currentPage() + 1 ||
                                            $page === $paginator->currentPage() + 2 ||
                                            $page === $paginator->currentPage() - 1 ||
                                            $page === $paginator->currentPage() - 2): ?>
                                        <a
                                            class="<?php echo e(theme('pagination.item', 'select-none cursor-pointer relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-zinc-600 dark:text-zinc-400 bg-white dark:bg-zinc-600 border border-zinc-300 dark:border-transparent leading-5 hover:text-zinc-500 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-700 transition ease-in-out duration-150')); ?>"
                                            wire:click="gotoPage(<?php echo e($page); ?>, '<?php echo e($paginator->getPageName()); ?>')"
                                        ><?php echo e($page); ?></a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasMorePages()): ?>
                            <a
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'block' => $paginator->lastPage() - $paginator->currentPage() >= 2,
                                    'hidden' => $paginator->lastPage() - $paginator->currentPage() < 2,
                                    theme('pagination.item_next', 'select-none cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-zinc-500 dark:text-zinc-300 bg-white dark:bg-zinc-600 border border-zinc-300 dark:border-transparent leading-5 hover:text-zinc-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500 transition ease-in-out duration-150'),
                                ]); ?>"
                                wire:click="nextPage('<?php echo e($paginator->getPageName()); ?>')"
                                rel="next"
                            >
                                <svg
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5"
                                    />
                                </svg>
                            </a>
                            <a
                                class="<?php echo e(theme('pagination.item_last', 'select-none cursor-pointer cursor-pointer relative inline-flex items-center px-2 py-2 text-sm font-medium text-zinc-500 dark:text-zinc-300 bg-white dark:bg-zinc-600 border border-zinc-300 dark:border-transparent rounded-r-md leading-5 hover:text-zinc-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-500 transition ease-in-out duration-150')); ?>"
                                wire:click="gotoPage(<?php echo e($paginator->lastPage()); ?>, '<?php echo e($paginator->getPageName()); ?>')"
                            >
                                <svg
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"
                                    />
                                </svg>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasPages() && in_array($recordCount, ['min', 'short'])): ?>
                    <nav
                        role="navigation"
                        aria-label="Pagination Navigation"
                        class="items-center justify-between sm:flex"
                    >
                        <div class="<?php echo e(theme('pagination.min_nav_wrapper', 'flex justify-center gap-2 md:flex-none md:justify-end sm:mt-0')); ?>">
                            <span>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->onFirstPage()): ?>
                                    <button
                                        disabled
                                        class="<?php echo e(theme('pagination.button_disabled', 'focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md font-semibold px-4 py-2 text-zinc-500 bg-zinc-50 ring-0 ring-inset ring-zinc-300 hover:bg-zinc-100 dark:bg-zinc-800 dark:ring-zinc-600 dark:border-zinc-600 dark:hover:bg-zinc-900 dark:text-zinc-300 focus-visible:outline-offset-0 rounded-md')); ?>"
                                    >
                                        <?php echo app('translator')->get('Previous'); ?>
                                    </button>
                                <?php else: ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(method_exists($paginator, 'getCursorName')): ?>
                                        <button
                                            wire:click="setPage('<?php echo e($paginator->previousCursor()->encode()); ?>','<?php echo e($paginator->getCursorName()); ?>')"
                                            wire:loading.attr="disabled"
                                            class="<?php echo e(theme('pagination.button_cursor', 'select-none p-2 m-1 text-center text-white bg-zinc-600 border-zinc-400 rounded cursor-pointer border-1 hover:bg-zinc-600 hover:border-zinc-800 dark:text-zinc-300')); ?>"
                                        >
                                            <svg
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="w-5 h-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"
                                                />
                                            </svg>

                                        </button>
                                    <?php else: ?>
                                        <button
                                            wire:click="previousPage('<?php echo e($paginator->getPageName()); ?>')"
                                            wire:loading.attr="disabled"
                                            class="<?php echo e(theme('pagination.button', 'select-none focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md font-semibold px-4 py-2 text-zinc-500 bg-zinc-50 ring-0 ring-inset ring-zinc-300 hover:bg-zinc-100 dark:bg-zinc-800 dark:ring-zinc-600 dark:border-zinc-600 dark:hover:bg-zinc-900 dark:text-zinc-300 focus-visible:outline-offset-0 rounded-md')); ?>"
                                        >
                                            <?php echo app('translator')->get('Previous'); ?>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>

                            <span>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paginator->hasMorePages()): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(method_exists($paginator, 'getCursorName')): ?>
                                        <button
                                            wire:click="setPage('<?php echo e($paginator->nextCursor()->encode()); ?>','<?php echo e($paginator->getCursorName()); ?>')"
                                            wire:loading.attr="disabled"
                                            class="<?php echo e(theme('pagination.button_cursor', 'select-none p-2 m-1 text-center text-white bg-zinc-600 border-zinc-400 rounded cursor-pointer border-1 hover:bg-zinc-600 hover:border-zinc-800 dark:text-zinc-300')); ?>"
                                        >
                                            <svg
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="w-5 h-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"
                                                />
                                            </svg>

                                        </button>
                                    <?php else: ?>
                                        <button
                                            wire:click="nextPage('<?php echo e($paginator->getPageName()); ?>')"
                                            wire:loading.attr="disabled"
                                            class="<?php echo e(theme('pagination.button', 'select-none focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md font-semibold px-4 py-2 text-zinc-500 bg-zinc-50 ring-0 ring-inset ring-zinc-300 hover:bg-zinc-100 dark:bg-zinc-800 dark:ring-zinc-600 dark:border-zinc-600 dark:hover:bg-zinc-900 dark:text-zinc-300 focus-visible:outline-offset-0 rounded-md')); ?>"
                                        >
                                            <?php echo app('translator')->get('Next'); ?>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    <button
                                        disabled
                                        class="<?php echo e(theme('pagination.button_disabled', 'focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md font-semibold px-4 py-2 text-zinc-500 bg-zinc-50 ring-0 ring-inset ring-zinc-300 hover:bg-zinc-100 dark:bg-zinc-800 dark:ring-zinc-600 dark:border-zinc-600 dark:hover:bg-zinc-900 dark:text-zinc-300 focus-visible:outline-offset-0 rounded-md')); ?>"
                                    >
                                        <?php echo app('translator')->get('Next'); ?>
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        </div>
                    </nav>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/themes/tailwind/pagination.blade.php ENDPATH**/ ?>