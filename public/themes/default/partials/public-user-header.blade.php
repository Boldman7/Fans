
<div class="timeline-cover-section">
	<div class="timeline-cover">
	    
		<ul class="list-inline pagelike-links">							
{{--			@if($user_post == true)--}}
				<li class="timeline-cover-status {{ Request::segment(2) == 'posts' ? 'active' : '' }}"><a href="" ><span class="top-list">{{ count($timeline->posts()->where('active', 1)->get()) }} {{ trans('common.posts') }}</span></a></li>
{{--			@else--}}
{{--				<li class="timeline-cover-status {{ Request::segment(2) == 'posts' ? 'active' : '' }}"><a href="#"><span class="top-list">{{ count($timeline->posts()->where('active', 1)->get()) }} {{ trans('common.posts') }}</span></a></li>--}}
{{--			@endif--}}
			<!-- <li class="{{ Request::segment(2) == 'following' ? 'active' : '' }} smallscreen-report"><a href="{{ url($timeline->username.'/following') }}" ><span class="top-list">{{ $following_count }} {{ trans('common.following') }}</span></a></li>
			<li class="{{ Request::segment(2) == 'followers' ? 'active' : '' }} smallscreen-report"><a href="{{ url($timeline->username.'/followers') }}" ><span class="top-list">{{ $followers_count }}  {{ trans('common.followers') }}</span></a></li>-->

			@if(!$user->timeline->albums->isEmpty())
				<li class=""><a href="" > {{ trans('common.photos') }}</span></a></li>
			@endif
			<li class="timeline-cover-status {{ Request::segment(2) == 'followers' ? 'active' : '' }}">
				<a href="" ><span class="top-list">{{ $followers_count }}  {{ trans('common.followers') }}</span>
				</a>
			</li>
			<li class="timeline-cover-status {{ Request::segment(2) == 'followers' ? 'active' : '' }}">
				<a href="#" ><span class="top-list"><span class="liked-post">{{count($liked_post)}}</span> {{ trans('common.likes') }}</span>
				</a>
			</li>

			<li class="timeline-cover-status {{ Request::segment(2) == 'followers' ? 'active' : '' }}">
				<a href="" ><span class="top-list">{{ $following_count }}  {{ trans('common.following') }}</span>
				</a>
			</li>

			@if($user->username != $timeline->username)
				@if(!$timeline->reports->contains($user->id))
				<li class="pull-right">
					<a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i> {{ trans('common.report') }}
					</a>
				</li>
				<li class="hidden pull-right">
					<a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i>	{{ trans('common.reported') }}
					</a>
				</li>
				@else
				<li class="hidden pull-right">
					<a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i> {{ trans('common.report') }}
					</a>
				</li>
				<li class="pull-right">
					<a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i>	{{ trans('common.reported') }}
					</a>
				</li>
				@endif
			@endif
			@if($user->username != $timeline->username)
				@if(!$timeline->reports->contains($user->id))
					<li class="smallscreen-report"><a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}">{{ trans('common.report') }}</a></li>
					<li class="hidden smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}">{{ trans('common.reported') }}</a></li>
				@else
					<li class="hidden smallscreen-report"><a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}">{{ trans('common.report') }}</a></li>
					<li class="smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}">{{ trans('common.reported') }}</a></li>
				@endif
			@endif
		</ul>
	    
		<img src=" @if($timeline->cover_id) {{ url('user/cover/'.$timeline->cover->source) }} @else {{ url('user/cover/default-cover-user.png') }} @endif" alt="{{ $timeline->name }}" title="{{ $timeline->name }}">
{{--		@if($timeline->id == $user->timeline_id)--}}
{{--			<a href="#" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text">{{ trans('common.change_cover') }}</span></a>--}}
{{--		@endif--}}
		<div class="user-cover-progress hidden">

		</div>
			<!-- <div class="cover-bottom">
		</div> -->
		<div class="user-timeline-name">
			<a href="">{{ $timeline->name }}</a><br><a class="user-timeline-username" href="{{ url($timeline->username) }}">{{ $timeline->username }}</a>
				{!! verifiedBadge($timeline) !!}
		</div>
		</div>
	<div class="timeline-list">

			<div class="status-button">
					<a href="#" class="btn btn-status">Subscription Packages</a>
			</div>
			<div class="timeline-user-avtar">

				<img src="{{ $timeline->user->avatar }}" alt="{{ $timeline->name }}" title="{{ $timeline->name }}">
{{--				@if($timeline->id == $user->timeline_id)--}}
{{--					<div class="chang-user-avatar">--}}
{{--						<a href="#" class="btn btn-camera change-avatar"><i class="fa fa-camera" aria-hidden="true"></i><span class="avatar-text">{{ trans('common.update_profile') }}<span>{{ trans('common.picture') }}</span></span></a>--}}
{{--					</div>--}}
{{--				@endif			--}}
				<div class="user-avatar-progress hidden">
				</div>
			</div><!-- /timeline-user-avatar -->

		</div><!-- /timeline-list -->
	</div><!-- timeline-cover-section -->
<script type="text/javascript">
	@if($timeline->background_id != NULL)
		$('body')
			.css('background-image', "url({{ url('/wallpaper/'.$timeline->wallpaper->source) }})")
			.css('background-attachment', 'fixed');
	@endif
</script>