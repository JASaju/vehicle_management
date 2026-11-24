<?php # [BlazeVersion]:{v3} ?>


<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="mb-6 text-2xl font-semibold">Dashboard</h1>

    <?php
        $cards = [
            ['Total vehicles', number_format($stats['total']), 'text-gray-900'],
            ['Active', number_format($stats['active']), 'text-green-600'],
            ['In maintenance', number_format($stats['in_maintenance']), 'text-yellow-600'],
            ['Active drivers', number_format($stats['drivers']), 'text-gray-900'],
            ['Parking free', $stats['slots_free'] . ' / ' . $stats['slots_total'], $stats['slots_free'] > 0 ? 'text-green-600' : 'text-red-600'],
            ['Fuel cost (30 days)', number_format($stats['fuel_cost'], 2), 'text-gray-900'],
            ['Maintenance cost (30 days)', number_format($stats['maintenance_cost'], 2), 'text-gray-900'],
        ];
    ?>

    <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-7">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-xs font-medium uppercase tracking-wide text-gray-500"><?php echo e($label); ?></div>
                <div class="mt-1 text-2xl font-semibold <?php echo e($color); ?>"><?php echo e($value); ?></div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg border border-gray-200 bg-white">
            <h2 class="border-b border-gray-200 px-4 py-3 font-semibold">Expired / expiring within 30 days</h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 text-sm last:border-0">
                    <div>
                        <div class="font-medium"><?php echo e($alert['subject']); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($alert['what']); ?></div>
                    </div>
                    <div class="text-right">
                        <?php echo \App\Support\Ui::expiry($alert['date']); ?>

                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="px-4 py-6 text-sm text-gray-500">Nothing expiring soon. 🎉</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>

        <section class="rounded-lg border border-gray-200 bg-white">
            <h2 class="border-b border-gray-200 px-4 py-3 font-semibold">Scheduled services</h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $upcomingServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 text-sm last:border-0">
                    <div>
                        <div class="font-medium"><?php echo e($service->vehicle->registration_no); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e(\Illuminate\Support\Str::headline($service->type)); ?> · <?php echo e($service->workshop ?? '—'); ?></div>
                    </div>
                    <div><?php echo e($service->service_date->format('d M Y')); ?></div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="px-4 py-6 text-sm text-gray-500">No scheduled services.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>

        <section class="rounded-lg border border-gray-200 bg-white lg:col-span-2">
            <h2 class="border-b border-gray-200 px-4 py-3 font-semibold">Recent fuel entries</h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentFuel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 text-sm last:border-0">
                    <div>
                        <div class="font-medium"><?php echo e($log->vehicle->registration_no); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($log->station ?? '—'); ?></div>
                    </div>
                    <div class="text-right">
                        <div><?php echo e(number_format($log->liters, 2)); ?> L · <?php echo e(number_format($log->total_cost, 2)); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($log->fuel_date->format('d M Y')); ?></div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="px-4 py-6 text-sm text-gray-500">No fuel entries yet.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\vehicle-app\resources\views/dashboard.blade.php ENDPATH**/ ?>