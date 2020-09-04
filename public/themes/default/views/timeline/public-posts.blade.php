<!-- main-section -->
<div class="container">
	<div class="row">
		<div class="col-md-12">
			{!! Theme::partial('public-user-header',compact('timeline', 'liked_post', 'liked_pages','user','joined_groups','followRequests','following_count',
			'followers_count','follow_confirm','user_post','joined_groups_count','guest_events')) !!}
			<div class="row">
				<div class=" timeline">
					<div class="col-md-4">
						{!! Theme::partial('public-user-leftbar',compact('timeline','user','follow_user_status','own_groups','own_pages','user_events')) !!}
					</div>
					<div class="col-md-8">
						<div class="timeline-posts">
							@if(count($posts) > 0)
								@foreach($posts as $post)
									{!! Theme::partial('public-post',compact('post','timeline','next_page_url', 'user')) !!}
								@endforeach
							@else
								<p class="no-posts">{{ trans('messages.no_posts') }}</p>
							@endif
						</div>
					</div><!-- /col-md-8 -->
				</div><!-- /main-content -->
			</div><!-- /row -->
		</div><!-- /col-md-10 -->

{{--		<div class="col-md-2">--}}
{{--			{!! Theme::partial('timeline-rightbar') !!}--}}
{{--		</div>--}}

	</div>
</div><!-- /container -->
