<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">

                <div class="col-md-7 col-lg-8">
					<div class="timeline-posts">
						<?php echo Theme::partial('my-lists',compact('user_lists', 'following_count', 'followers_count')); ?>

					</div>
				</div><!-- /col-md-6 -->

				<div class="col-md-5 col-lg-4">
					<?php echo Theme::partial('home-rightbar',compact('suggested_users', 'suggested_groups', 'suggested_pages')); ?>

				</div>
			</div>
		</div>
	<!-- </div> -->
<!-- /main-section -->