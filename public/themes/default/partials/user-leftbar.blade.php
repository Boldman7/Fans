<div class="user-profile-buttons">
	<div class="row follow-links pagelike-links">
		<!-- This [if-1] is for checking current user timeline or diff user timeline -->	
		@if(Auth::user()->username != $timeline->username)
		<?php 
					//php code is for checking user's follow_privacy settings
		$user_follow ="";
		$confirm_follow ="";
		$message_privacy ="";						
		$othersSettings = $user->getOthersSettings($timeline->username);
		if($othersSettings)
		{
						//follow_privacy checking
			if ($othersSettings->follow_privacy == "only_follow") {
				$user_follow = "only_follow";
			}elseif ($othersSettings->follow_privacy == "everyone") {
				$user_follow = "everyone";
			}

						//confirm_follow checking
			if ($othersSettings->confirm_follow == "yes") {
				$confirm_follow = "yes";
			}elseif ($othersSettings->confirm_follow == "no") {
				$confirm_follow = "no";
			}

			//message_privacy checking
			if ($othersSettings->message_privacy == "only_follow") {
				$message_privacy = "only_follow";
			}elseif ($othersSettings->message_privacy == "everyone") {
				$message_privacy = "everyone";
			}
		}

		?>
			<!-- This [if-3] is for checking usersettings follow_privacy showing follow/following || message button -->
{{--			@if(($user_follow == "everyone") && $user->payment != NULL && $user->payment->is_active == 1 && $user->payment->price > 0)--}}
			@if($user->price >= 0)

				@if(!$user->followers->contains(Auth::user()->id))

					<div class="col-md-6 col-sm-6 col-xs-6 left-col">
						<a href="javascript:void(0);" class="btn btn-options btn-block follow-user btn-default follow" data-price="{{ $user->price }}"  data-timeline-id="{{ $timeline->id }}">
							<i class="fa fa-heart"></i> {{ trans('common.follow') }}
						</a>
					</div>

					<div class="col-md-6 col-sm-6 col-xs-6 hidden">
						<a href="#" class="btn btn-options btn-block btn-success unfollow" data-price="{{ $user->price }}"  data-timeline-id="{{ $timeline->id }}">
							<i class="fa fa-check"></i> {{ trans('common.following') }}
						</a>
					</div>
				@else

					<div class="col-md-6 col-sm-6 col-xs-6 hidden">
						<a href="#" class="btn btn-options btn-block follow-user btn-default follow " data-price="{{ $user->price }}" data-timeline-id="{{ $timeline->id }}">
							<i class="fa fa-heart"></i> {{ trans('common.follow') }}
						</a>
					</div>

					<div class="col-md-6 col-sm-6 col-xs-6 left-col">
						<a href="#" class="btn btn-options btn-block btn-success unfollow" data-price="{{ $user->price }}"  data-timeline-id="{{ $timeline->id }}">	<i class="fa fa-check"></i> {{ trans('common.following') }}
						</a>
					</div>
				@endif

			@endif	<!-- End of [if-3]-->
{{--			@if(($user->followers->contains(Auth::user()->id) && $message_privacy == "only_follow") || ($message_privacy == "everyone"))--}}
				<div class="col-md-6 col-sm-6 col-xs-6 right-col">
					<a href="#" class="btn btn-options btn-block btn-default" onClick="chatBoxes.sendMessage({{ $timeline->user->id }})">
						<i class="fa fa-inbox"></i> {{ trans('common.message') }}
					</a>
				</div>

			@if($user->price >= 0)

				<div class="col-12">
					&nbsp;
				</div>

				@if($user->followers->contains(Auth::user()->id))

					<div class="col-md-offset-3 col-sm-offset-3 col-xs-offset-3 col-md-6 col-sm-6 col-xs-6 left-col">
						<a href="#" class="btn btn-options btn-block btn-default unfollow" data-price="{{ $user->price }}"  data-timeline-id="{{ $timeline->id }}">	<i class="fa fa-ban"></i> {{ trans('common.restrict') }}
						</a>
					</div>
				@endif

			@endif	<!-- End of [if-3]-->

{{--			@endif--}}
		@else
		<div class="col-md-12"><a href="{{ url('/'.Auth::user()->username.'/settings/profile') }}" class="btn btn-profile"><i class="fa fa-pencil-square-o"></i>{{ trans('common.edit_profile') }}</a></div>
		@endif <!-- End of [if-1]-->

	</div>
</div>

@if (        
        ($timeline->type == 'user' && $timeline->id == Auth::user()->timeline_id) ||
        ($timeline->type == 'page' && $timeline->page->is_admin(Auth::user()->id) == true) ||
        ($timeline->type == 'group' && $timeline->groups->is_admin(Auth::user()->id) == true)
        )
@endif

<div class="user-bio-block">
	<div class="bio-header">{{ trans('common.bio') }}</div>
	<div class="bio-description">
		{{ ($user->about != NULL) ? $user->about : trans('messages.no_description') }}
	</div>
	<a target="_blank" href="{{ $user->wishlist }}">{{ $user->wishlist }}</a><br>
	<a target="_blank" href="{{ $user->website }}">{{ $user->website }}</a><br>
	<a target="_blank" href="{{ $user->instagram }}">{{ $user->instagram }}</a>
	
	<ul class="list-unstyled list-details">
		@if($user->hobbies != NULL)
			<li><i class="fa fa-chain" aria-hidden="true"></i> {{ $user->hobbies }}</li>
		@endif
		@if($user->interests != NULL)
			<li><i class="fa fa-instagram" aria-hidden="true"></i> {{ $user->interests }}</li>
		@endif
		@if($user->custom_option1 != NULL && Setting::get('custom_option1') != NULL)
			<li>{!! '<b>'.Setting::get('custom_option1').': </b>'!!} {{ $user->custom_option1 }}</li>
		@endif
		@if($user->custom_option2 != NULL && Setting::get('custom_option2') != NULL)
			<li>{!! '<b>'.Setting::get('custom_option2').': </b>'!!} {{ $user->custom_option2 }}</li>
		@endif
		@if($user->custom_option3 != NULL && Setting::get('custom_option3') != NULL)
			<li>{!! '<b>'.Setting::get('custom_option3').': </b>'!!} {{ $user->custom_option3 }}</li>
		@endif
		@if($user->custom_option4 != NULL && Setting::get('custom_option4') != NULL)
			<li>{!! '<b>'.Setting::get('custom_option4').': </b>'!!} {{ $user->custom_option4 }}</li>
		@endif
	</ul>
	
	<ul class="list-unstyled list-details">
		@if($user->designation != NULL)
			<li><i class="fa fa-thumb-tack"></i> <span>{{ $user->designation }}</span></li>
		@endif
		@if($user->country != NULL)
		<li>
			<i class="fa fa-map-marker" aria-hidden="true" style="margin-right: 3px;"></i><span>{{ trans('common.lives_in').' '.$user->country }}</span>
		</li>
		@endif

		@if($user->city != NULL)
		<li><i class="fa fa-building-o"></i><span>{{ trans('common.from').' '.$user->city }}</span></li>
		@endif

{{--		@if($user->birthday != '1970-01-01')--}}
{{--		<li><i class="fa fa-calendar"></i><span>--}}
{{--			{{ trans('common.born_on').' '.date('F d', strtotime($user->birthday)) }}--}}
{{--		</span></li>--}}
{{--		@endif--}}
	</ul>
	<ul class="list-inline list-unstyled social-links-list">
		@if($user->facebook_link != NULL)
			<li>
				<a target="_blank" href="{{ $user->facebook_link }}" class="btn btn-facebook"><i class="fa fa-facebook"></i></a>
			</li>
		@endif
		@if($user->twitter_link != NULL)
			<li>
				<a target="_blank" href="{{ $user->twitter_link }}" class="btn btn-twitter"><i class="fa fa-twitter"></i></a>
			</li>
		@endif
		@if($user->dribbble_link != NULL)
			<li>
				<a target="_blank" href="{{ $user->dribbble_link }}" class="btn btn-dribbble"><i class="fa fa-dribbble"></i></a>
			</li>
		@endif
		@if($user->youtube_link != NULL)
			<li>
				<a target="_blank" href="{{ $user->youtube_link }}" class="btn btn-youtube"><i class="fa fa-youtube"></i></a>
			</li>
		@endif
		@if($user->instagram_link != NULL)
			<li>
				<a target="_blank" href="{{ $user->instagram_link }}" class="btn btn-instagram"><i class="fa fa-instagram"></i></a>
			</li>
		@endif
		@if($user->linkedin_link != NULL)
			<li>
				<a target="_blank" href="{{ $user->linkedin_link }}" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
			</li>
		@endif
	</ul>
</div>


<!-- /Albums Widget -->

<!-- Change avatar form -->
<form class="change-avatar-form hidden" action="{{ url('ajax/change-avatar') }}" method="post" enctype="multipart/form-data">
	<input name="timeline_id" value="{{ $timeline->id }}" type="hidden">
	<input name="timeline_type" value="{{ $timeline->type }}" type="hidden">
	<input class="change-avatar-input hidden" accept="image/jpeg,image/png" type="file" name="change_avatar" >
</form>

<!-- Change cover form -->
<form class="change-cover-form hidden" action="{{ url('ajax/change-cover') }}" method="post" enctype="multipart/form-data">
	<input name="timeline_id" value="{{ $timeline->id }}" type="hidden">
	<input name="timeline_type" value="{{ $timeline->type }}" type="hidden">
	<input class="change-cover-input hidden" accept="image/jpeg,image/png" type="file" name="change_cover" >
</form>


	@if(Setting::get('timeline_ad') != NULL)
	<div id="link_other" class="post-filters">
		{!! htmlspecialchars_decode(Setting::get('timeline_ad')) !!} 
	</div>	
	@endif
















