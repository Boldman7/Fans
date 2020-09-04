<!-- main-section -->
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php echo Theme::partial('public-user-header',compact('timeline', 'liked_post', 'liked_pages','user','joined_groups','followRequests','following_count',
			'followers_count','follow_confirm','user_post','joined_groups_count','guest_events')); ?>

			<div class="row">
				<div class=" timeline">
					<div class="col-md-4">
						<?php echo Theme::partial('public-user-leftbar',compact('timeline','user','follow_user_status','own_groups','own_pages','user_events')); ?>

					</div>
					<div class="col-md-8">
						<div class="timeline-posts">
							<?php if(count($posts) > 0): ?>
								<?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php echo Theme::partial('public-post',compact('post','timeline','next_page_url', 'user')); ?>

								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php else: ?>
								<p class="no-posts"><?php echo e(trans('messages.no_posts')); ?></p>
							<?php endif; ?>
						</div>
					</div><!-- /col-md-8 -->
				</div><!-- /main-content -->
			</div><!-- /row -->
		</div><!-- /col-md-10 -->





	</div>
</div><!-- /container -->
