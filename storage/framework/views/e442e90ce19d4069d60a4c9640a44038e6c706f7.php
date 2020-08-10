<div class="user-profile-buttons">
	<div class="row follow-links pagelike-links">
		<!-- This [if-1] is for checking current user timeline or diff user timeline -->	
		<?php if(Auth::user()->username != $timeline->username): ?>
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

			<?php if($user->price >= 0): ?>

				<?php if(!$user->followers->contains(Auth::user()->id)): ?>

						<div class="col-md-6 col-sm-6 col-xs-6 left-col">
							<a href="javascript:void(0);" class="btn btn-options btn-block follow-user btn-default follow" data-price="<?php echo e($user->price); ?>"  data-timeline-id="<?php echo e($timeline->id); ?>">
								<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

							</a>
						</div>

						<div class="col-md-6 col-sm-6 col-xs-6 hidden">
							<a href="#" class="btn btn-options btn-block btn-success unfollow" data-price="<?php echo e($user->price); ?>"  data-timeline-id="<?php echo e($timeline->id); ?>">
								<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

							</a>
						</div>
				<?php else: ?>

					<div class="col-md-6 col-sm-6 col-xs-6 hidden">
						<a href="#" class="btn btn-options btn-block follow-user btn-default follow " data-price="<?php echo e($user->price); ?>" data-timeline-id="<?php echo e($timeline->id); ?>">
							<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

						</a>
					</div>

					<div class="col-md-6 col-sm-6 col-xs-6 left-col">
						<a href="#" class="btn btn-options btn-block btn-success unfollow" data-price="<?php echo e($user->price); ?>"  data-timeline-id="<?php echo e($timeline->id); ?>">	<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

						</a>
					</div>
				<?php endif; ?>

			<?php endif; ?>	<!-- End of [if-3]-->

				<div class="col-md-6 col-sm-6 col-xs-6 right-col">
					<a href="#" class="btn btn-options btn-block btn-default" onClick="chatBoxes.sendMessage(<?php echo e($timeline->user->id); ?>)">
						<i class="fa fa-inbox"></i> <?php echo e(trans('common.message')); ?>

					</a>
				</div>

		<?php else: ?>
		<div class="col-md-12"><a href="<?php echo e(url('/'.Auth::user()->username.'/settings/profile')); ?>" class="btn btn-profile"><i class="fa fa-pencil-square-o"></i><?php echo e(trans('common.edit_profile')); ?></a></div>
		<?php endif; ?> <!-- End of [if-1]-->

	</div>
</div>

<?php if(        
        ($timeline->type == 'user' && $timeline->id == Auth::user()->timeline_id) ||
        ($timeline->type == 'page' && $timeline->page->is_admin(Auth::user()->id) == true) ||
        ($timeline->type == 'group' && $timeline->groups->is_admin(Auth::user()->id) == true)
        ): ?>
<?php endif; ?>

<div class="user-bio-block">
	<div class="bio-header"><?php echo e(trans('common.bio')); ?></div>
	<div class="bio-description">
		<?php echo e(($user->about != NULL) ? $user->about : trans('messages.no_description')); ?>

	</div>
	<a target="_blank" href="<?php echo e($user->wishlist); ?>"><?php echo e($user->wishlist); ?></a><br>
	<a target="_blank" href="<?php echo e($user->website); ?>"><?php echo e($user->website); ?></a><br>
	<a target="_blank" href="<?php echo e($user->instagram); ?>"><?php echo e($user->instagram); ?></a>
	
	<ul class="list-unstyled list-details">
		<?php if($user->hobbies != NULL): ?>
			<li><i class="fa fa-chain" aria-hidden="true"></i> <?php echo e($user->hobbies); ?></li>
		<?php endif; ?>
		<?php if($user->interests != NULL): ?>
			<li><i class="fa fa-instagram" aria-hidden="true"></i> <?php echo e($user->interests); ?></li>
		<?php endif; ?>
		<?php if($user->custom_option1 != NULL && Setting::get('custom_option1') != NULL): ?>
			<li><?php echo '<b>'.Setting::get('custom_option1').': </b>'; ?> <?php echo e($user->custom_option1); ?></li>
		<?php endif; ?>
		<?php if($user->custom_option2 != NULL && Setting::get('custom_option2') != NULL): ?>
			<li><?php echo '<b>'.Setting::get('custom_option2').': </b>'; ?> <?php echo e($user->custom_option2); ?></li>
		<?php endif; ?>
		<?php if($user->custom_option3 != NULL && Setting::get('custom_option3') != NULL): ?>
			<li><?php echo '<b>'.Setting::get('custom_option3').': </b>'; ?> <?php echo e($user->custom_option3); ?></li>
		<?php endif; ?>
		<?php if($user->custom_option4 != NULL && Setting::get('custom_option4') != NULL): ?>
			<li><?php echo '<b>'.Setting::get('custom_option4').': </b>'; ?> <?php echo e($user->custom_option4); ?></li>
		<?php endif; ?>
	</ul>
	
	<ul class="list-unstyled list-details">
		<?php if($user->designation != NULL): ?>
			<li><i class="fa fa-thumb-tack"></i> <span><?php echo e($user->designation); ?></span></li>
		<?php endif; ?>
		<?php if($user->country != NULL): ?>
		<li>
			<i class="fa fa-map-marker" aria-hidden="true" style="margin-right: 3px;"></i><span><?php echo e(trans('common.lives_in').' '.$user->country); ?></span>
		</li>
		<?php endif; ?>

		<?php if($user->city != NULL): ?>
		<li><i class="fa fa-building-o"></i><span><?php echo e(trans('common.from').' '.$user->city); ?></span></li>
		<?php endif; ?>






	</ul>
	<ul class="list-inline list-unstyled social-links-list">
		<?php if($user->facebook_link != NULL): ?>
			<li>
				<a target="_blank" href="<?php echo e($user->facebook_link); ?>" class="btn btn-facebook"><i class="fa fa-facebook"></i></a>
			</li>
		<?php endif; ?>
		<?php if($user->twitter_link != NULL): ?>
			<li>
				<a target="_blank" href="<?php echo e($user->twitter_link); ?>" class="btn btn-twitter"><i class="fa fa-twitter"></i></a>
			</li>
		<?php endif; ?>
		<?php if($user->dribbble_link != NULL): ?>
			<li>
				<a target="_blank" href="<?php echo e($user->dribbble_link); ?>" class="btn btn-dribbble"><i class="fa fa-dribbble"></i></a>
			</li>
		<?php endif; ?>
		<?php if($user->youtube_link != NULL): ?>
			<li>
				<a target="_blank" href="<?php echo e($user->youtube_link); ?>" class="btn btn-youtube"><i class="fa fa-youtube"></i></a>
			</li>
		<?php endif; ?>
		<?php if($user->instagram_link != NULL): ?>
			<li>
				<a target="_blank" href="<?php echo e($user->instagram_link); ?>" class="btn btn-instagram"><i class="fa fa-instagram"></i></a>
			</li>
		<?php endif; ?>
		<?php if($user->linkedin_link != NULL): ?>
			<li>
				<a target="_blank" href="<?php echo e($user->linkedin_link); ?>" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
			</li>
		<?php endif; ?>
	</ul>
</div>


<!-- /Albums Widget -->

<!-- Change avatar form -->
<form class="change-avatar-form hidden" action="<?php echo e(url('ajax/change-avatar')); ?>" method="post" enctype="multipart/form-data">
	<input name="timeline_id" value="<?php echo e($timeline->id); ?>" type="hidden">
	<input name="timeline_type" value="<?php echo e($timeline->type); ?>" type="hidden">
	<input class="change-avatar-input hidden" accept="image/jpeg,image/png" type="file" name="change_avatar" >
</form>

<!-- Change cover form -->
<form class="change-cover-form hidden" action="<?php echo e(url('ajax/change-cover')); ?>" method="post" enctype="multipart/form-data">
	<input name="timeline_id" value="<?php echo e($timeline->id); ?>" type="hidden">
	<input name="timeline_type" value="<?php echo e($timeline->type); ?>" type="hidden">
	<input class="change-cover-input hidden" accept="image/jpeg,image/png" type="file" name="change_cover" >
</form>


	<?php if(Setting::get('timeline_ad') != NULL): ?>
	<div id="link_other" class="post-filters">
		<?php echo htmlspecialchars_decode(Setting::get('timeline_ad')); ?> 
	</div>	
	<?php endif; ?>
















