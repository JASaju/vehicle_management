<?php # [BlazeVersion]:{v3} ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'tableName' => null,
    '__partial' => null,
    'loading' => false,
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
    'tableName' => null,
    '__partial' => null,
    'loading' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php use \PowerComponents\LivewirePowerGrid\Support\RowRenderer; ?>

<?php
    $__partial = $__partial ?? $this;
    $tableName = $tableName ?? $__partial->tableName;
    $rowRenderer = RowRenderer::canRenderDirect() ? new RowRenderer($__partial) : null;
    $responsive = isset($__partial->setUp['responsive']);
    $hasDetail = isset($__partial->setUp['detail']);
?>
<tbody
    wire:partial="pg-tbody-<?php echo e($tableName); ?>"
    <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'tbody-'.e($tableName).''; ?>wire:key="tbody-<?php echo e($tableName); ?>"
    class="pg-tbody <?php echo e(theme('table.layout.tbody')); ?>"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loading): ?>
        <?php echo $__env->make(theme_view('table.header'), ['loading' => true, '__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
        <?php echo $__env->renderWhen($__partial->hasColumnFilters && ! $__partial->filterBuilderHidesDefaultFilters(), theme_view('table.inline-filters'), [
            '__partial' => $__partial,
            'tableName' => $tableName,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($__partial->records) === 0): ?>
            <?php echo $__env->make(theme_view('table.th-empty'), [
                '__partial' => $__partial,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <?php echo $__env->renderWhen($__partial->headerTotalColumn, theme_view('table.summarize'), [
                '__partial' => $__partial,
                'isHeader' => true,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $__partial->records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $rowId = data_get($row, $__partial->realPrimaryKey);
                    $class = theme('table.layout.tr');
                    if ($loop->odd) {
                        $class .= ' ' . theme('table.layout.tr_striped');
                    } else {
                        $class .= ' ' . theme('table.layout.tr_not_striped');
                    }
                ?>

                <tr <?php echo e($__partial->rowAttributes($row, new \Illuminate\View\ComponentAttributeBag([
                    'wire:key' => 'row-' . $rowId,
                    'data-pg-row-id' => $rowId,
                    'class' => $class,
                ]))); ?>>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rowRenderer): ?>
                        <?php echo $rowRenderer->render($row, $loop->index + 1, null, null, $rowId); ?>

                    <?php else: ?>
                        <?php echo $__env->make(theme_view('table.row'), [
                            'rowIndex' => $loop->index + 1,
                            '__partial' => $__partial,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tr>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasDetail && $__partial->isDetailOpen($rowId)): ?>
                    <?php
                        $detail = $__partial->detailForRow($row);
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($detail['view'])): ?>
                        <tr
                            <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'powergrid-detail-'.e($rowId).''; ?>wire:key="powergrid-detail-<?php echo e($rowId); ?>"
                            class="<?php echo e($class); ?>"
                        >
                            <td colspan="999">
                                <?php echo $__env->make($detail['view'], [
                                    'id' => $rowId,
                                    'options' => $detail['options'] ?? [],
                                    'row' => $row,
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($responsive): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rowRenderer): ?>
                        <?php echo $rowRenderer->renderExpandRow($rowId); ?>

                    <?php else: ?>
                        <?php echo $__env->make(theme_view('table.responsive-container'), ['__partial' => $__partial], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            <?php echo $__env->renderWhen($__partial->footerTotalColumn, theme_view('table.summarize'), [
                '__partial' => $__partial,
                'isHeader' => false,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
<?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/partials/tbody.blade.php ENDPATH**/ ?>