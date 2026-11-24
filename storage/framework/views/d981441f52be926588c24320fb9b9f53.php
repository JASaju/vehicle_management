<?php # [BlazeVersion]:{v3} ?>


<?php $__env->startSection('title', 'Fuel Logs'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Fuel Logs</h1>
        <a href="<?php echo e(route('fuel-logs.create')); ?>" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Add fuel entry</a>
    </div>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('fuel-log-table', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1044551398-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\vehicle-app\resources\views/fuel-logs/index.blade.php ENDPATH**/ ?>