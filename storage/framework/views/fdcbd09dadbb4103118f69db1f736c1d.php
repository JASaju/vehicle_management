<?php # [BlazeVersion]:{v3} ?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo $__env->yieldContent('title', 'Sign in'); ?> · Vehicle Management</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4 text-gray-900">
    <div class="w-full max-w-sm">
        <div class="mb-6 text-center text-2xl font-semibold text-indigo-600">🚗 Vehicle Manager</div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\laragon\www\vehicle-app\resources\views/layouts/guest.blade.php ENDPATH**/ ?>