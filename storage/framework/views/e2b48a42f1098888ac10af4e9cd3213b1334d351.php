<!-- main-section -->

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php echo Theme::partial('user-header',compact('timeline', 'liked_post', 'user','followRequests','following_count',
			'followers_count','follow_confirm','user_post','joined_groups_count','guest_events')); ?>				
			
			<div class="row">
				<div class=" timeline">
					<div class="col-md-4">
						
						<?php echo Theme::partial('user-leftbar',compact('timeline','user','follow_user_status','own_pages','own_groups','user_events')); ?>

					</div>
					<div class="col-md-8">
						<div class="panel panel-default">
							<div class="panel-heading no-bg panel-settings">
								<h3 class="panel-title">
									<?php echo e(trans('common.following')); ?>

								</h3>
							</div>
							<div class="panel-body">

								<?php if(count($following) > 0): ?>
								<ul class="list-group page-likes">
									<?php $__currentLoopData = $following; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $follow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<li class="list-group-item holder">
										<div class="connect-list">
											<div class="connect-link side-left">
												<a href="<?php echo e(url('/'.$follow->username)); ?>">													
													<img src="<?php echo e($follow->avatar); ?>" alt="<?php echo e($follow->name); ?>" class="img-icon img-30" title="<?php echo e($follow->name); ?>">
													<?php echo e($follow->name); ?>

												</a>
												<?php if($follow->verified): ?>
									              <span class="verified-badge bg-success">
									                    <i class="fa fa-check"></i>
									                </span>
									            <?php endif; ?>
											</div>
											<?php if($timeline->id == Auth::user()->timeline_id): ?>
												<div class="side-right follow-links">
													<?php if(!$user->following->contains($follow->id)): ?>
													<div class="left-col"><a href="#" class="btn btn-to-follow btn-default follow-user follow" data-price="<?php echo e($follow->price); ?>"  data-timeline-id="<?php echo e($follow->timeline_id); ?>"><i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?> </a></div>

													<div class="left-col hidden"><a href="#" class="btn btn-success unfollow " data-price="<?php echo e($follow->price); ?>" data-timeline-id="<?php echo e($follow->timeline_id); ?>"><i class="fa fa-check"></i><?php echo e(trans('common.following')); ?></a></div>
													<?php else: ?>
													<div class="left-col hidden"><a href="#" class="btn btn-to-follow btn-default follow-user follow " data-price="<?php echo e($follow->price); ?>"  data-timeline-id="<?php echo e($follow->timeline_id); ?>"><i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?></a></div>
													<div class="left-col"><a href="#" class="btn btn-success unfollow" data-price="<?php echo e($follow->price); ?>"  data-timeline-id="<?php echo e($follow->timeline_id); ?>"><i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?></a></div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
											<div class="clearfix"></div>
										</div>
									</li>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</ul>
								<?php else: ?>
								<div class="alert alert-warning"><?php echo e(trans('messages.no_following')); ?></div>
								<?php endif; ?>
							</div><!-- /panel-body -->
						</div>
					</div><!-- /col-md-8 -->
				</div><!-- /main-content -->
			</div><!-- /row -->
		</div><!-- /col-md-10 -->

		<div class="col-md-2">
			<?php echo Theme::partial('timeline-rightbar'); ?>

		</div>

	</div>
</div><!-- /container -->

