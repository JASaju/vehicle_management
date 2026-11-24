<?php # [BlazeVersion]:{v3} ?>
<?php
if (!function_exists('__a5673d25ffd2327cb9d9a33183dc974a')):
function __a5673d25ffd2327cb9d9a33183dc974a($__blaze, $__data = [], $__attributes = [], $__slots = [], $__this = null) {
$__env = $__blaze->env;

if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = new \Livewire\Blaze\Runtime\BlazeAttributeBag($__attributes);
unset($__data, $__attributes);
ob_start();
?>


<svg
    <?php echo e($attributes->merge(['class' => 'h-5 w-5'])); ?>

    fill="none"
    stroke="currentColor"
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    viewBox="0 0 24 24"
>
    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
</svg>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH D:\laragon\www\vehicle-app\vendor\power-components\livewire-powergrid\resources\views/components/icons/search.blade.php ENDPATH**/ ?>