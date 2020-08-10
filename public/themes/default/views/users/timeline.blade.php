<!-- main-section -->

	<div class="container section-container @if($timeline->hide_cover) no-cover @endif">
		<div class="row">
			<div class="col-md-12">
				@if($timeline->type == "user")
					{!! Theme::partial('user-header',compact('user','timeline', 'liked_post','liked_pages','joined_groups','followRequests','following_count','followers_count','follow_confirm','user_post','joined_groups_count','guest_events')) !!}
				@elseif($timeline->type == "page")
					{!! Theme::partial('page-header',compact('page','timeline')) !!}
				@elseif($timeline->type == "group")
					{!! Theme::partial('group-header',compact('timeline','group')) !!}
				@elseif($timeline->type == "event")
					{!! Theme::partial('event-header',compact('event','timeline')) !!}
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col-md-10">

				<div class="row">
					<div class="timeline">
						<div class="col-md-4">
							@if($timeline->type == "user")
							{!! Theme::partial('user-leftbar',compact('timeline','user','follow_user_status','own_pages','own_groups','user_events')) !!}
							@elseif($timeline->type == "page")
							{!! Theme::partial('page-leftbar',compact('timeline','page','page_members')) !!}
							@elseif($timeline->type == "group")
								{!! Theme::partial('group-leftbar',compact('timeline','group','group_members','group_events','ongoing_events','upcoming_events')) !!}
							@elseif($timeline->type == "event")
								{!! Theme::partial('event-leftbar',compact('event','timeline')) !!}
							@endif
						</div>

						<!-- Post box on timeline,page,group -->
{{--						<div class="col-md-8">--}}

{{--							@if($timeline->type == "user" && $timeline_post == true)--}}
{{--								{!! Theme::partial('create-post',compact('timeline','user_post')) !!}--}}

{{--							@elseif($timeline->type == "page")--}}
{{--								@if(($page->timeline_post_privacy == "only_admins" && $page->is_admin(Auth::user()->id)) || ($page->timeline_post_privacy == "everyone"))--}}
{{--									{!! Theme::partial('create-post',compact('timeline','user_post')) !!}--}}
{{--								@elseif($page->timeline_post_privacy == "everyone")--}}
{{--									{!! Theme::partial('create-post',compact('timeline','user_post')) !!}--}}
{{--								@endif--}}

{{--							@elseif($timeline->type == "group")--}}
{{--								@if(($group->post_privacy == "only_admins" && $group->is_admin(Auth::user()->id))|| ($group->post_privacy == "members" && Auth::user()->get_group($group->id) == 'approved') || $group->post_privacy == "everyone")--}}
{{--									{!! Theme::partial('create-post',compact('timeline','user_post','username')) !!}--}}
{{--								@endif--}}

{{--							@elseif($timeline->type == "event")--}}
{{--								@if(($event->timeline_post_privacy == 'only_admins' && $event->is_eventadmin(Auth::user()->id, $event->id)) || ($event->timeline_post_privacy == 'only_guests' && Auth::user()->get_eventuser($event->id)))--}}
{{--									{!! Theme::partial('create-post',compact('timeline','user_post')) !!}--}}
{{--								@endif--}}
{{--							@endif--}}

{{--							<div class="timeline-posts">--}}
{{--								@if($user_post == "user" || $user_post == "page" || $user_post == "group")--}}
{{--									@if(count($posts) > 0)--}}
{{--	 									@foreach($posts as $post)--}}
{{--	 										{!! Theme::partial('post',compact('post','timeline','next_page_url','user')) !!}--}}
{{--	 										{!! Theme::partial('post',compact('post','timeline','next_page_url')) !!}--}}
{{--	 									@endforeach--}}
{{-- 									@else--}}
{{-- 										<div class="no-posts alert alert-warning">{{ trans('messages.no_posts') }}</div>--}}
{{-- 									@endif--}}
{{-- 								@endif--}}

{{-- 								@if($user_post == "event")--}}
{{-- 									@if($event->type == 'private' && Auth::user()->get_eventuser($event->id) || $event->type == 'public')--}}
{{-- 										@if(count($posts) > 0)--}}
{{--		 									@foreach($posts as $post)--}}
{{--		 										{!! Theme::partial('post',compact('post','timeline','next_page_url','user')) !!}--}}
{{--		 									@endforeach--}}
{{--	 									@else--}}
{{--	 										<div class="no-posts alert alert-warning">{{ trans('messages.no_posts') }}</div>--}}
{{--	 									@endif--}}
{{-- 									@else--}}
{{-- 										<div class="no-posts alert alert-warning">{{ trans('messages.private_posts') }}</div>--}}
{{-- 									@endif--}}
{{-- 								@endif--}}
{{--							</div>--}}
{{--						</div>--}}
					</div>
				</div><!-- /row -->
			</div><!-- /col-md-10 -->

			<div class="col-md-2">
				{!! Theme::partial('timeline-rightbar') !!}
			</div>

		</div><!-- /row -->
	</div>
