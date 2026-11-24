<?php # [BlazeVersion]:{v3} ?>
<?php
if (!function_exists('__73252ea3c6904c122fe741877328697b')):
function __73252ea3c6904c122fe741877328697b($__blaze, $__data = [], $__attributes = [], $__slots = [], $__this = null) {
$__env = $__blaze->env;

if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = new \Livewire\Blaze\Runtime\BlazeAttributeBag($__attributes);
unset($__data, $__attributes);
ob_start();
?>


<svg
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    <?php echo e($attributes); ?>

>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M4.5 15.75l7.5-7.5 7.5 7.5"
    />
</svg>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/icons/chevron-up.blade.php ENDPATH**/ ?>