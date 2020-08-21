<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">



              
                <div class="col-md-7 col-lg-8">

					<div class="timeline-posts">
						<?php if($mode == 'posts'): ?>
							<?php echo Theme::partial('post',compact('post','timeline')); ?>

						<?php elseif($mode == 'notifications'): ?>
							<?php echo Theme::partial('allnotifications',compact('notifications')); ?>

						<?php endif; ?>							
					</div>
				</div><!-- /col-md-6 -->

				<div class="col-md-5 col-lg-4">
					<?php echo Theme::partial('home-rightbar',compact('suggested_users', 'suggested_groups', 'suggested_pages')); ?>

				</div>
			</div>
		</div>
	<!-- </div> -->
<!-- /main-section -->