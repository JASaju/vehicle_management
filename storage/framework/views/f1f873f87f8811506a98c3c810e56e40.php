<?php # [BlazeVersion]:{v3} ?>


<?php $__env->startSection('title', 'Security Logs'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Security Logs</h1>
        <p class="mt-1 text-sm text-gray-600">Read-only view of the latest 200 SIEM-ready security events.</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($malformed_lines > 0): ?>
        <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            <?php echo e($malformed_lines); ?> malformed or truncated log line(s) were skipped.
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($events === []): ?>
        <div class="rounded-lg border border-gray-200 bg-white p-6 text-sm text-gray-600">
            No security events have been recorded yet.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Timestamp</th>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Event</th>
                        <th class="px-4 py-3">Outcome</th>
                        <th class="px-4 py-3">User ID</th>
                        <th class="px-4 py-3">Source IP</th>
                        <th class="px-4 py-3">Request</th>
                        <th class="px-4 py-3">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="align-top text-gray-700">
                            <td class="whitespace-nowrap px-4 py-3"><?php echo e($event['timestamp']); ?></td>
                            <td class="px-4 py-3"><?php echo e($event['level'] ?? '—'); ?></td>
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900"><?php echo e($event['action'] ?? '—'); ?></td>
                            <td class="px-4 py-3"><?php echo e($event['outcome'] ?? '—'); ?></td>
                            <td class="px-4 py-3"><?php echo e($event['user_id'] ?? '—'); ?></td>
                            <td class="px-4 py-3"><?php echo e($event['ip'] ?? '—'); ?></td>
                            <td class="px-4 py-3"><?php echo e($event['method'] ?? '—'); ?> <?php echo e($event['path'] ?? ''); ?></td>
                            <td class="px-4 py-3"><?php echo e($event['reason'] ?? '—'); ?></td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\vehicle-app\resources\views/security-logs/index.blade.php ENDPATH**/ ?>