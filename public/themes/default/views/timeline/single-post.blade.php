<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">
{{--				<div class="visible-lg col-lg-2">--}}
{{--					{!! Theme::partial('home-leftbar',compact('trending_tags')) !!}--}}
{{--				</div>--}}
              
                <div class="col-md-7 col-lg-8">

					<div class="timeline-posts">
						@if($mode == 'posts')
							{!! Theme::partial('post',compact('post','timeline')) !!}
						@elseif($mode == 'notifications')
							{!! Theme::partial('allnotifications',compact('notifications')) !!}
						@endif							
					</div>
				</div><!-- /col-md-6 -->

				<div class="col-md-5 col-lg-4">
					{!! Theme::partial('home-rightbar',compact('suggested_users', 'suggested_groups', 'suggested_pages')) !!}
				</div>
			</div>
		</div>
	<!-- </div> -->
<!-- /main-section -->