
<div class="timeline-cover-section">
	<div class="timeline-cover">
	    
		<ul class="list-inline pagelike-links">							

				<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'posts' ? 'active' : ''); ?>"><a href="<?php echo e(url($timeline->username.'/posts')); ?>" ><span class="top-list"><?php echo e(count($timeline->posts()->where('active', 1)->get())); ?> <?php echo e(trans('common.posts')); ?></span></a></li>



			<!-- <li class="<?php echo e(Request::segment(2) == 'following' ? 'active' : ''); ?> smallscreen-report"><a href="<?php echo e(url($timeline->username.'/following')); ?>" ><span class="top-list"><?php echo e($following_count); ?> <?php echo e(trans('common.following')); ?></span></a></li>
			<li class="<?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?> smallscreen-report"><a href="<?php echo e(url($timeline->username.'/followers')); ?>" ><span class="top-list"><?php echo e($followers_count); ?>  <?php echo e(trans('common.followers')); ?></span></a></li>-->

			<?php if(!$user->timeline->albums->isEmpty()): ?>
				<li class=""><a href="<?php echo e(url($timeline->username.'/albums')); ?>" > <?php echo e(trans('common.photos')); ?></span></a></li>
			<?php endif; ?>
			<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?>">
				<a href="<?php echo e(url($timeline->username.'/followers')); ?>" ><span class="top-list"><?php echo e($followers_count); ?>  <?php echo e(trans('common.followers')); ?></span>
				</a>
			</li>
			<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?>">
				<a href="#" ><span class="top-list"><span class="liked-post"><?php echo e(count($liked_post)); ?></span> <?php echo e(trans('common.likes')); ?></span>
				</a>
			</li>

			<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?>">
				<a href="<?php echo e(url($timeline->username.'/following')); ?>" ><span class="top-list"><?php echo e($following_count); ?>  <?php echo e(trans('common.following')); ?></span>
				</a>
			</li>

			<?php if(Auth::user()->username != $timeline->username): ?>
				<?php if(!$timeline->reports->contains(Auth::user()->id)): ?>
				<li class="pull-right">
					<a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i> <?php echo e(trans('common.report')); ?>

					</a>
				</li>
				<li class="hidden pull-right">
					<a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i>	<?php echo e(trans('common.reported')); ?>

					</a>
				</li>
				<?php else: ?>
				<li class="hidden pull-right">
					<a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i> <?php echo e(trans('common.report')); ?>

					</a>
				</li>
				<li class="pull-right">
					<a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i>	<?php echo e(trans('common.reported')); ?>

					</a>
				</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if(Auth::user()->username != $timeline->username): ?>
				<?php if(!$timeline->reports->contains(Auth::user()->id)): ?>
					<li class="smallscreen-report"><a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.report')); ?></a></li>
					<li class="hidden smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.reported')); ?></a></li>
				<?php else: ?>
					<li class="hidden smallscreen-report"><a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.report')); ?></a></li>
					<li class="smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.reported')); ?></a></li>
				<?php endif; ?>
			<?php endif; ?>
		</ul>
	    
		<img src=" <?php if($timeline->cover_id): ?> <?php echo e(url('user/cover/'.$timeline->cover->source)); ?> <?php else: ?> <?php echo e(url('user/cover/default-cover-user.png')); ?> <?php endif; ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>">
		<?php if($timeline->id == Auth::user()->timeline_id): ?>
			<a href="#" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text"><?php echo e(trans('common.change_cover')); ?></span></a>
		<?php endif; ?>
		<div class="user-cover-progress hidden">

		</div>
			<!-- <div class="cover-bottom">
		</div> -->
		<div class="user-timeline-name">
			<a href="<?php echo e(url($timeline->username)); ?>"><?php echo e($timeline->name); ?></a><br><a class="user-timeline-username" href="<?php echo e(url($timeline->username)); ?>"><?php echo e($timeline->username); ?></a>
				<?php echo verifiedBadge($timeline); ?>

		</div>
		</div>
	<div class="timeline-list">

			<div class="status-button">
					<a href="#" class="btn btn-status">Subscription Packages</a>
			</div>
			<div class="timeline-user-avtar">

				<img src="<?php echo e($timeline->user->avatar); ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>">
				<?php if($timeline->id == Auth::user()->timeline_id): ?>
					<div class="chang-user-avatar">
						<a href="#" class="btn btn-camera change-avatar"><i class="fa fa-camera" aria-hidden="true"></i><span class="avatar-text"><?php echo e(trans('common.update_profile')); ?><span><?php echo e(trans('common.picture')); ?></span></span></a>
					</div>
				<?php endif; ?>			
				<div class="user-avatar-progress hidden">
				</div>
			</div><!-- /timeline-user-avatar -->

		</div><!-- /timeline-list -->
	</div><!-- timeline-cover-section -->
<script type="text/javascript">
	<?php if($timeline->background_id != NULL): ?>
		$('body')
			.css('background-image', "url(<?php echo e(url('/wallpaper/'.$timeline->wallpaper->source)); ?>)")
			.css('background-attachment', 'fixed');
	<?php endif; ?>
</script>