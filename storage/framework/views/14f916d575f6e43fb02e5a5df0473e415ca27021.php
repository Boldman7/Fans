<div class="right-side-section">

	<div class="panel panel-default">
		<div class="panel-body nopadding">
			<div class="mini-profile fans">
				<div class="background">
					<div class="widget-bg">
						<img src=" <?php if(Auth::user()->cover): ?> <?php echo e(url('user/cover/'.Auth::user()->cover)); ?> <?php else: ?> <?php echo e(url('user/cover/default-cover-user.png')); ?> <?php endif; ?>" alt="<?php echo e(Auth::user()->name); ?>" title="<?php echo e(Auth::user()->name); ?>">
					</div>
					<div class="avatar-img">
						<img src="<?php echo e(Auth::user()->avatar); ?>" alt="<?php echo e(Auth::user()->name); ?>" title="<?php echo e(Auth::user()->name); ?>">
					</div>
				</div>
				<div class="avatar-profile">
					<div class="avatar-details">
						<h2 class="avatar-name">
							<a href="<?php echo e(url(Auth::user()->username)); ?>">
								<?php echo e(Auth::user()->name); ?>

							</a>
						</h2>
						<h4 class="avatar-mail">
							<a href="<?php echo e(url(Auth::user()->username)); ?>">
								<?php echo e('@'.Auth::user()->username); ?>

							</a>
						</h4>
					</div>      
				</div>
				<ul class="activity-list list-inline">
					<li>
						<a href="<?php echo e(url(Auth::user()->username.'/posts')); ?>">
							<div class="activity-name">
								<?php echo e(trans('common.posts')); ?>

							</div>
							<div class="activity-count">
								<?php echo e(count(Auth::user()->posts()->where('active', 1)->get())); ?>

							</div>
						</a>
					</li>
					<li>
						<a href="<?php echo e(url(Auth::user()->username.'/followers')); ?>">
							<div class="activity-name">
								<?php echo e(trans('common.followers')); ?> 
							</div>
							<div class="activity-count">
								<?php echo e(Auth::user()->followers->count()); ?>

							</div>
						</a>
					</li>
					<li>
						<a href="<?php echo e(url(Auth::user()->username.'/following')); ?>">
							<div class="activity-name">
								<?php echo e(trans('common.following')); ?>

							</div>
							<div class="activity-count">
								<?php echo e(Auth::user()->following->count()); ?>

							</div>
						</a>
					</li>
				</ul>
			</div><!-- /mini-profile -->							
		</div>
	</div><!-- /panel -->
	
	<div class="panel panel-default">
		<div class="panel-heading no-bg">
			<h3 class="panel-title">
				<?php echo e(trans('common.suggested_people')); ?>

			</h3>
		</div>
		<div class="panel-body">
			<!-- widget holder starts here -->
			<div class="user-follow fans">
				<!-- Each user is represented with media block -->
				<?php if($suggested_users != ""): ?>

				<?php $__currentLoopData = $suggested_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suggested_user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

				<div class="media">
					<div class="media-left badge-verification">
						<a href="<?php echo e(url($suggested_user->username)); ?>">
							<img src="<?php echo e($suggested_user->avatar); ?>" class="img-icon" alt="<?php echo e($suggested_user->name); ?>" title="<?php echo e($suggested_user->name); ?>">
							<?php if($suggested_user->verified): ?>
							<span class="verified-badge bg-success verified-medium">
								<i class="fa fa-check"></i>
							</span>
							<?php endif; ?>
						</a>
					</div>
					<div class="media-body socialte-timeline follow-links">
						<h4 class="media-heading"><a href="<?php echo e(url($suggested_user->username)); ?>"><?php echo e($suggested_user->name); ?> </a>
							<span class="text-muted"><?php echo e('@'.$suggested_user->username); ?></span>
						</h4>

						<?php if($suggested_user->price >= 0): ?>
							<div class="btn-follow">
								<a href="#" class="btn btn-default follow-user follow" data-price="<?php echo e($suggested_user->price); ?>" data-timeline-id="<?php echo e($suggested_user->timeline->id); ?>"> <i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?></a>
							</div>
							<div class="btn-follow hidden">
								<a href="#" class="btn btn-success follow-user unfollow" data-price="<?php echo e($suggested_user->price); ?>" data-timeline-id="<?php echo e($suggested_user->timeline->id); ?>"><i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?></a>
							</div>
						<?php endif; ?>
						</div>

					</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php else: ?>
					<div class="alert alert-warning">
						<?php echo e(trans('messages.no_suggested_users')); ?>

					</div>
					<?php endif; ?>

				</div>
				<!-- widget holder ends here -->
			</div>
		</div>
		</div>