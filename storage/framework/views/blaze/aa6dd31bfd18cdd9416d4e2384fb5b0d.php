<?php # [BlazeVersion]:{v3} ?>
<?php
if (!function_exists('__f817a84bdc018e52c90e29d77159c862')):
function __f817a84bdc018e52c90e29d77159c862($__blaze, $__data = [], $__attributes = [], $__slots = [], $__this = null) {
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
    stroke-width="1.5"
    stroke="currentColor"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
    />
</svg>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/icons/chevron-up-down.blade.php ENDPATH**/ ?>