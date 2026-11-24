<?php # [BlazeVersion]:{v3} ?>


<?php $__env->startSection('title', 'Parking'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-semibold">Parking</h1>

        <div class="flex gap-2">
            <a href="<?php echo e(route('parking.history')); ?>" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">History</a>
            <a href="<?php echo e(route('parking-slots.index')); ?>" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Manage slots</a>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
            ['Total slots', $stats['total'], 'text-gray-900'],
            ['Free', $stats['free'], 'text-green-600'],
            ['Occupied', $stats['occupied'], 'text-red-600'],
            ['Out of service', $stats['inactive'], 'text-gray-500'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-xs font-medium uppercase tracking-wide text-gray-500"><?php echo e($label); ?></div>
                <div class="mt-1 text-2xl font-semibold <?php echo e($color); ?>"><?php echo e($value); ?></div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $floors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor => $zones): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <section class="mb-8 rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-4 text-lg font-semibold"><?php echo e($floor); ?></h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone => $zoneSlots): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="mb-5 last:mb-0">
                    <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Row <?php echo e($zone); ?></div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $zoneSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $record = $slot->currentRecord; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record): ?>
                                
                                <div class="rounded-lg border border-red-300 bg-red-50 p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-red-700"><?php echo e($slot->code); ?></span>
                                        <?php echo \App\Support\Ui::badge('Occupied', 'red'); ?>

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->vehicle): ?>
                                        <div class="mt-2 text-sm font-semibold"><?php echo e($record->vehicle->registration_no); ?></div>
                                        <div class="text-xs text-gray-600"><?php echo e($record->vehicle->brand); ?> <?php echo e($record->vehicle->model); ?></div>
                                        <div class="text-xs text-gray-600">Driver: <?php echo e($record->vehicle->driver?->name ?? '—'); ?></div>
                                    <?php else: ?>
                                        <div class="mt-2 text-sm font-semibold">Vehicle not identified</div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->detected_plate): ?>
                                            <div class="text-xs text-gray-600">Plate read: <?php echo e($record->detected_plate); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($available)): ?>
                                            <form method="POST" action="<?php echo e(route('parking.identify', $slot)); ?>" class="mt-2 space-y-2">
                                                <?php echo csrf_field(); ?>
                                                <select name="vehicle_id" required
                                                        class="w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">Assign vehicle…</option>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $available; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                        <option value="<?php echo e($id); ?>"><?php echo e($label); ?></option>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                </select>
                                                <button class="w-full rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">Assign vehicle</button>
                                            </form>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Since <?php echo e($record->parked_at->format('d M, H:i')); ?>

                                        (<?php echo e(\App\Support\Ui::duration($record->parked_at)); ?>)
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(strtoupper($slot->code), config('parking_sensors.slots', []), true)): ?>
                                        <div class="mt-3 text-xs text-gray-500">
                                            Sensor: <?php echo e($slot->sensor_occupied === null ? 'Waiting for first reading' : ($slot->sensor_occupied ? 'Occupied' : 'Free')); ?>

                                        </div>
                                    <?php else: ?>
                                        <form method="POST" action="<?php echo e(route('parking.release', $slot)); ?>" class="mt-3"
                                              onsubmit="return confirm('Release this vehicle from the slot?')">
                                            <?php echo csrf_field(); ?>
                                            <button class="w-full rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">Release</button>
                                        </form>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php elseif(! $slot->is_active): ?>
                                
                                <div class="rounded-lg border border-gray-200 bg-gray-100 p-3 text-gray-400">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold"><?php echo e($slot->code); ?></span>
                                        <?php echo \App\Support\Ui::badge('Out of service', 'gray'); ?>

                                    </div>
                                    <div class="mt-2 text-xs">Not available for parking.</div>
                                </div>
                            <?php else: ?>
                                
                                <div class="rounded-lg border border-green-300 bg-green-50 p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-green-700"><?php echo e($slot->code); ?></span>
                                        <?php echo \App\Support\Ui::badge('Free', 'green'); ?>

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(strtoupper($slot->code), config('parking_sensors.slots', []), true)): ?>
                                        <div class="mt-3 text-xs text-gray-500">
                                            Sensor: <?php echo e($slot->sensor_occupied === null ? 'Waiting for first reading' : 'Free'); ?>. Parking is detected automatically.
                                        </div>
                                    <?php elseif(count($available)): ?>
                                        <form method="POST" action="<?php echo e(route('parking.park', $slot)); ?>" class="mt-3 space-y-2">
                                            <?php echo csrf_field(); ?>
                                            <select name="vehicle_id" required
                                                    class="w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Select vehicle…</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $available; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                    <option value="<?php echo e($id); ?>"><?php echo e($label); ?></option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            </select>
                                            <button class="w-full rounded-md bg-green-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-700">Park here</button>
                                        </form>
                                    <?php else: ?>
                                        <div class="mt-3 text-xs text-gray-500">No unparked vehicle available.</div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </section>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500">
            No parking slots yet.
            <a href="<?php echo e(route('parking-slots.index')); ?>" class="font-medium text-indigo-600 hover:underline">Create slots</a>
            (for example A1–A3 and B1–B2 on the Ground Floor).
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count(config('parking_sensors.slots', []))): ?>
    <?php $__env->startPush('scripts'); ?>
        <script>
            window.setInterval(() => {
                if (!document.querySelector('form:focus-within')) {
                    window.location.reload();
                }
            }, 10000);
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\vehicle-app\resources\views/parking/index.blade.php ENDPATH**/ ?>