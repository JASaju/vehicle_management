<?php # [BlazeVersion]:{v3} ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'checkbox' => null,
    'columns' => null,
    'actions' => null,
    'enabledFilters' => null,
    'inputTextOptions' => [],
    'tableName' => null,
    'filters' => [],
    'setUp' => null,
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
    'checkbox' => null,
    'columns' => null,
    'actions' => null,
    'enabledFilters' => null,
    'inputTextOptions' => [],
    'tableName' => null,
    'filters' => [],
    'setUp' => null,
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
    $checkbox = $checkbox ?? $__partial->checkbox;
    $columns = $columns ?? $__partial->columns;
    $actions = $actions ?? [];
    $tableName = $tableName ?? $__partial->tableName;
    $filters = $filters ?? $__partial->filters;
    $setUp = $setUp ?? $__partial->setUp;

    $trClasses = Arr::toCssClasses([theme('table.layout.tr'), theme('table.body.tr.filters')]);
    $tdClasses = Arr::toCssClasses([theme('table.layout.td'), theme('table.body.td.filters')]);
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__partial->usesFilterInline()): ?>
    <tr
        class="<?php echo e($trClasses); ?>"
        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'pg-inline-filters-'.e($tableName).''; ?>wire:key="pg-inline-filters-<?php echo e($tableName); ?>"
        wire:partial.ignore="pg-inline-filters-<?php echo e($tableName); ?>"
        data-pg-inline-filters
    >

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($setUp, 'detail.showCollapseIcon')): ?>
            <td
                class="<?php echo e($tdClasses); ?>"
            ></td>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($setUp['responsive'])): ?>
            <td
                class="<?php echo e($tdClasses); ?>"
            ></td>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($checkbox): ?>
            <td
                class="<?php echo e($tdClasses); ?>"
            ></td>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $filterClass = str(data_get($column, 'filters.className'));
            ?>
            <td
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    theme('table.layout.td'),
                    theme('table.body.td.filters'),
                ]); ?>"
                <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'column-filter-'.e(data_get($column, 'field')).''; ?>wire:key="column-filter-<?php echo e(data_get($column, 'field')); ?>"
                style="<?php echo \Illuminate\Support\Arr::toCssStyles([
                    'display:none' => data_get($column, 'hidden') === true,
                ]) ?>"
            >
                <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'filter-'.e(data_get($column, 'field')).'-'.e($loop->index).''; ?>wire:key="filter-<?php echo e(data_get($column, 'field')); ?>-<?php echo e($loop->index); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterClass->contains('FilterMultiSelect')): ?>
                        <?php if (isset($component)) { $__componentOriginal6b5037bea931bbd774061236a74bcc3d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b5037bea931bbd774061236a74bcc3d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'livewire-powergrid::components.inputs.select','data' => ['tableName' => $tableName,'title' => data_get($column, 'title'),'filter' => (array) data_get($column, 'filters'),'initialValues' => data_get($filters, data_get($column, 'filters.field') . '.value'),'partial' => $__partial]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('livewire-powergrid::inputs.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['table-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tableName),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(data_get($column, 'title')),'filter' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((array) data_get($column, 'filters')),'initial-values' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(data_get($filters, data_get($column, 'filters.field') . '.value')),'__partial' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($__partial)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6b5037bea931bbd774061236a74bcc3d)): ?>
<?php $attributes = $__attributesOriginal6b5037bea931bbd774061236a74bcc3d; ?>
<?php unset($__attributesOriginal6b5037bea931bbd774061236a74bcc3d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6b5037bea931bbd774061236a74bcc3d)): ?>
<?php $component = $__componentOriginal6b5037bea931bbd774061236a74bcc3d; ?>
<?php unset($__componentOriginal6b5037bea931bbd774061236a74bcc3d); ?>
<?php endif; ?>
                    <?php elseif($filterClass->contains(['FilterSelect', 'FilterEnumSelect'])): ?>
                        <?php if ($__env->exists(theme_view('filter.select'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ])) echo $__env->make(theme_view('filter.select'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($filterClass->contains('FilterInputText')): ?>
                        <?php if ($__env->exists(theme_view('filter.input_text'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ])) echo $__env->make(theme_view('filter.input_text'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($filterClass->contains('FilterNumber')): ?>
                        <?php if ($__env->exists(theme_view('filter.number'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ])) echo $__env->make(theme_view('filter.number'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($filterClass->contains('FilterDateTimePicker')): ?>
                        <?php if ($__env->exists(theme_view('filter.date_picker'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            'type' => 'datetime',
                            'tableName' => $tableName,
                            'classAttr' => 'w-full',
                            '__partial' => $__partial,
                        ])) echo $__env->make(theme_view('filter.date_picker'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            'type' => 'datetime',
                            'tableName' => $tableName,
                            'classAttr' => 'w-full',
                            '__partial' => $__partial,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($filterClass->contains('FilterDatePicker')): ?>
                        <?php if ($__env->exists(theme_view('filter.date_picker'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            'type' => 'date',
                            'tableName' => $tableName,
                            'classAttr' => 'w-full',
                            '__partial' => $__partial,
                        ])) echo $__env->make(theme_view('filter.date_picker'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            'type' => 'date',
                            'tableName' => $tableName,
                            'classAttr' => 'w-full',
                            '__partial' => $__partial,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($filterClass->contains('FilterBoolean')): ?>
                        <?php if ($__env->exists(theme_view('filter.boolean'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ])) echo $__env->make(theme_view('filter.boolean'), [
                            'inline' => true,
                            'filter' => (array) data_get($column, 'filters'),
                            '__partial' => $__partial,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($filterClass->contains('FilterDynamic')): ?>
                        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => data_get($column, 'filters.component')] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withBoundAttributes(['attributes']); ?>
<?php $component->withAttributes(['attributes' => new \Illuminate\View\ComponentAttributeBag(
                                array_merge(
                                    data_get($column, 'filters.attributes', []),
                                    ['__partial' => $__partial]
                                ),
                            )]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </td>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($actions) && count($actions)): ?>
            <td colspan="<?php echo e(count($actions)); ?>"></td>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tr>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/themes/tailwind/table/inline-filters.blade.php ENDPATH**/ ?>