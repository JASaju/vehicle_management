<?php # [BlazeVersion]:{v3} ?>


<?php $__env->startSection('title', 'Reports'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $input = 'w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
        $label = 'mb-1 block text-xs font-medium text-gray-600';
    ?>

    <h1 class="mb-1 text-2xl font-semibold">Reports</h1>
    <p class="mb-6 text-sm text-gray-500">Every report opens as a PDF in a new tab. Use "Download" to save the file instead. Leave a filter empty to include everything.</p>

    <div class="grid gap-6 md:grid-cols-2">

        
        <form method="GET" action="<?php echo e(route('reports.vehicles')); ?>" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Vehicle list</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="<?php echo e($label); ?>">Status</label>
                    <select name="status" class="<?php echo e($input); ?>">
                        <option value="">All</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vehicleStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?> <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="<?php echo e($label); ?>">Type</label>
                    <select name="vehicle_type_id" class="<?php echo e($input); ?>">
                        <option value="">All</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?> <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>

        
        <form method="GET" action="<?php echo e(route('reports.drivers')); ?>" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Driver list</h2>
            <div>
                <label class="<?php echo e($label); ?>">Status</label>
                <select name="active" class="<?php echo e($input); ?>">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>

        
        <form method="GET" action="<?php echo e(route('reports.maintenance')); ?>" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Maintenance report</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="<?php echo e($label); ?>">From</label><input type="date" name="from" class="<?php echo e($input); ?>"></div>
                <div><label class="<?php echo e($label); ?>">To</label><input type="date" name="to" class="<?php echo e($input); ?>"></div>
                <div>
                    <label class="<?php echo e($label); ?>">Vehicle</label>
                    <select name="vehicle_id" class="<?php echo e($input); ?>">
                        <option value="">All</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?> <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="<?php echo e($label); ?>">Status</label>
                    <select name="status" class="<?php echo e($input); ?>">
                        <option value="">All</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $maintenanceStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?> <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>

        
        <form method="GET" action="<?php echo e(route('reports.fuel')); ?>" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Fuel report</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="<?php echo e($label); ?>">From</label><input type="date" name="from" class="<?php echo e($input); ?>"></div>
                <div><label class="<?php echo e($label); ?>">To</label><input type="date" name="to" class="<?php echo e($input); ?>"></div>
                <div class="sm:col-span-2">
                    <label class="<?php echo e($label); ?>">Vehicle</label>
                    <select name="vehicle_id" class="<?php echo e($input); ?>">
                        <option value="">All</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?> <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>

        
        <form method="GET" action="<?php echo e(route('reports.expiry')); ?>" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Expiry report</h2>
            <p class="mb-3 text-xs text-gray-500">Insurance, tax token, fitness certificate and driving licence dates that are expired or about to expire.</p>
            <div>
                <label class="<?php echo e($label); ?>">Expiring within (days)</label>
                <input type="number" name="days" value="30" min="0" max="365" class="<?php echo e($input); ?>">
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>

        
        <form method="GET" action="<?php echo e(route('reports.costs')); ?>" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Cost summary by vehicle</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="<?php echo e($label); ?>">From</label><input type="date" name="from" class="<?php echo e($input); ?>"></div>
                <div><label class="<?php echo e($label); ?>">To</label><input type="date" name="to" class="<?php echo e($input); ?>"></div>
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>

        
        <form method="GET" action="<?php echo e(route('reports.parking')); ?>" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Parking report</h2>
            <p class="mb-3 text-xs text-gray-500">Who is parked in which slot right now, plus the parking history for the dates below.</p>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="<?php echo e($label); ?>">History from</label><input type="date" name="from" class="<?php echo e($input); ?>"></div>
                <div><label class="<?php echo e($label); ?>">History to</label><input type="date" name="to" class="<?php echo e($input); ?>"></div>
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>

        
        <form method="GET" action="" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5 md:col-span-2"
              onsubmit="this.action = '<?php echo e(url('reports/vehicle')); ?>/' + this.vehicle.value; return this.vehicle.value !== '';">
            <h2 class="mb-3 font-semibold">Single vehicle history</h2>
            <div>
                <label class="<?php echo e($label); ?>">Vehicle</label>
                <select name="vehicle" class="<?php echo e($input); ?>" required>
                    <option value="">Select vehicle</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?> <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>
            <?php echo $__env->make('reports._buttons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\vehicle-app\resources\views/reports/index.blade.php ENDPATH**/ ?>