<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="post-filters">
				<?php echo Theme::partial('usermenu-settings'); ?>

			</div>
		</div>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading no-bg panel-settings">
					<h3 class="panel-title">
						<?php echo e(trans('common.login_session')); ?>

					</h3>
				</div>
	<div class="panel-body timeline">




		<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		
		<?php if(count($users) > 0): ?>
			<div class="table-responsive manage-table">
				<table class="table existing-products-table fans">
					<thead>
					<tr>
						<th>&nbsp;</th>
						<th><?php echo e(trans('admin.id')); ?></th>
						<th><?php echo e(trans('common.name')); ?></th>
						<th><?php echo e(trans('common.browser')); ?></th>
						<th><?php echo e(trans('common.os')); ?></th>
						<th><?php echo e(trans('common.machine_name')); ?></th>
						<!--<th>Location</th>-->
						<th><?php echo e(trans('common.date')); ?></th>
						<th>&nbsp;</th>
					</tr>
					</thead>
					<tbody>
					<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo e($user->id); ?></td>
							<td><?php echo e($user->user_name); ?></td>
							<td><?php echo e($user->browser); ?></td>
							<td><?php echo e($user->os); ?></td>
							<td><?php echo e($user->machine_name); ?></td>
							<!--<td><?php echo e($user->location); ?></td>-->
							<td><?php echo e($user->created_at); ?>+00:00</td>
							<td>&nbsp;</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
				</table>
			</div>
			<div class="pagination-holder userpage">
				<?php echo e($users->render()); ?>

			</div>
		<?php else: ?>
			<div class="alert alert-warning"><?php echo e(trans('messages.no_users')); ?></div>
		<?php endif; ?>
	</div>
</div>
	</div>
</div>
