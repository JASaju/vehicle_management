<?php # [BlazeVersion]:{v3} ?>
<?php
if (!function_exists('__3f020c0e2d96dfa4780f5517e9146655')):
function __3f020c0e2d96dfa4780f5517e9146655($__blaze, $__data = [], $__attributes = [], $__slots = [], $__this = null) {
$__env = $__blaze->env;

if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = new \Livewire\Blaze\Runtime\BlazeAttributeBag($__attributes);
unset($__data, $__attributes);
ob_start();
?>


<svg
    <?php echo e($attributes); ?>

    fill="none"
    viewBox="0 0 24 24"
    stroke="currentColor"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
    />
</svg>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/icons/download.blade.php ENDPATH**/ ?>