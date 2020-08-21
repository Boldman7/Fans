
<div class="timeline-cover-section">
	<div class="timeline-cover">
		<ul class="list-inline pagelike-links">
			
			<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'posts' ? 'active' : ''); ?>"><a href="<?php echo e(url($timeline->username.'/posts')); ?>" ><span class="top-list"><?php echo e(count($timeline->posts()->where('active', 1)->get())); ?> <?php echo e(trans('common.posts')); ?></span></a></li>
			
			
			
		<!-- <li class="<?php echo e(Request::segment(2) == 'following' ? 'active' : ''); ?> smallscreen-report"><a href="<?php echo e(url($timeline->username.'/following')); ?>" ><span class="top-list"><?php echo e($following_count); ?> <?php echo e(trans('common.following')); ?></span></a></li>
		<li class="<?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?> smallscreen-report"><a href="<?php echo e(url($timeline->username.'/followers')); ?>" ><span class="top-list"><?php echo e($followers_count); ?>  <?php echo e(trans('common.followers')); ?></span></a></li>-->

			<?php if(!$user->timeline->albums->isEmpty()): ?>
				<li class=""><a href="<?php echo e(url($timeline->username.'/albums')); ?>" > <?php echo e(trans('common.photos')); ?></span></a></li>
			<?php endif; ?>
			<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?>">
				<a href="<?php echo e(url($timeline->username.'/followers')); ?>" ><span class="top-list"><?php echo e($followers_count); ?>  <?php echo e(trans('common.followers')); ?></span>
				</a>
			</li>
			<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?>">
				<a href="#" ><span class="top-list"><span class="liked-post"><?php echo e(count($liked_post)); ?></span> <?php echo e(trans('common.likes')); ?></span>
				</a>
			</li>

			<li class="timeline-cover-status <?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?>">
				<a href="<?php echo e(url($timeline->username.'/following')); ?>" ><span class="top-list"><?php echo e($following_count); ?>  <?php echo e(trans('common.following')); ?></span>
				</a>
			</li>

			<?php if(Auth::user()->username != $timeline->username): ?>
				<?php if(!$timeline->reports->contains(Auth::user()->id)): ?>
					<li class="pull-right">
						<a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i> <?php echo e(trans('common.report')); ?>

						</a>
					</li>
					<li class="hidden pull-right">
						<a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i>	<?php echo e(trans('common.reported')); ?>

						</a>
					</li>
				<?php else: ?>
					<li class="hidden pull-right">
						<a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i> <?php echo e(trans('common.report')); ?>

						</a>
					</li>
					<li class="pull-right">
						<a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i>	<?php echo e(trans('common.reported')); ?>

						</a>
					</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if(Auth::user()->username != $timeline->username): ?>
				<?php if(!$timeline->reports->contains(Auth::user()->id)): ?>
					<li class="smallscreen-report"><a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.report')); ?></a></li>
					<li class="hidden smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.reported')); ?></a></li>
				<?php else: ?>
					<li class="hidden smallscreen-report"><a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.report')); ?></a></li>
					<li class="smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.reported')); ?></a></li>
				<?php endif; ?>
			<?php endif; ?>
		</ul>

		<div class="profile-dropdown-menu">
			<ul class="list-inline no-margin">
				<li class="dropdown">
					<a href="#" class="dropdown-togle profile-dropdown-icon" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<svg class="g-icon has-tooltip" aria-hidden="true" data-original-title="null">
							<use xlink:href="#icon-more" href="#icon-more">
								<svg id="icon-more" viewBox="0 0 24 24"> <path d="M6 10a2 2 0 1 0 2 2 2 2 0 0 0-2-2zm12 0a2 2 0 1 0 2 2 2 2 0 0 0-2-2zm-6 0a2 2 0 1 0 2 2 2 2 0 0 0-2-2z"></path> </svg>
							</use>
						</svg>
					</a>
					<ul class="dropdown-menu profile-dropdown-menu-content">
						<li class="main-link">

							<input type="hidden" id="profile-url" value="<?php echo e(url($timeline->username)); ?>">

							<a href="#" onclick="getProfileUrl(); return false;">
								<?php echo e(trans('common.copy_link_to_profile')); ?>

							</a>
							<?php if(Auth::user()->username != $timeline->username): ?>
								<a href="#" id="link-list-modal" data-toggle="modal" data-target="#listsModal">
									<?php echo e(trans('common.add_to_remove_from_lists')); ?>

								</a>
							<?php endif; ?>
						</li>
					</ul>
				</li>
			</ul>
		</div>

		<img src=" <?php if($timeline->cover_id): ?> <?php echo e(url('user/cover/'.$timeline->cover->source)); ?> <?php else: ?> <?php echo e(url('user/cover/default-cover-user.png')); ?> <?php endif; ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>">
		<?php if($timeline->id == Auth::user()->timeline_id): ?>
			<a href="#" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text"><?php echo e(trans('common.change_cover')); ?></span></a>
		<?php endif; ?>
		<div class="user-cover-progress hidden">

		</div>
			<!-- <div class="cover-bottom">
		</div> -->
		<div class="user-timeline-name">
			<a href="<?php echo e(url($timeline->username)); ?>"><?php echo e($timeline->name); ?></a><br><a class="user-timeline-username" href="<?php echo e(url($timeline->username)); ?>"><?php echo e($timeline->username); ?></a>
				<?php echo verifiedBadge($timeline); ?>

		</div>
		</div>
	<div class="timeline-list">

			<div class="status-button">
					<a href="#" class="btn btn-status">Subscription Packages</a>
			</div>
			<div class="timeline-user-avtar">

				<img src="<?php echo e($timeline->user->avatar); ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>">
				<?php if($timeline->id == Auth::user()->timeline_id): ?>
					<div class="chang-user-avatar">
						<a href="#" class="btn btn-camera change-avatar"><i class="fa fa-camera" aria-hidden="true"></i><span class="avatar-text"><?php echo e(trans('common.update_profile')); ?><span><?php echo e(trans('common.picture')); ?></span></span></a>
					</div>
				<?php endif; ?>			
				<div class="user-avatar-progress hidden">
				</div>
			</div><!-- /timeline-user-avatar -->

		</div><!-- /timeline-list -->
	</div><!-- timeline-cover-section -->


	<div id="listsModal" class="modal fade" role="dialog" tabindex='-1'>
		<div class="modal-dialog">

			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-header lists-modal">

						<h3 class="modal-title lists-modal-title">
							<svg class="g-icon" aria-hidden="true">
								<use xlink:href="#icon-lists" href="#icon-lists">
									<svg id="icon-lists" viewBox="0 0 24 24"> <path d="M6.69 5H5.24L4.8 3.64a.31.31 0 00-.6 0L3.76 5H2.31a.31.31 0 00-.18.56l1.17.85-.45 1.39a.28.28 0 000 .09.31.31 0 00.31.32.3.3 0 00.18-.06L4.5 7.3l1.17.85a.3.3 0 00.18.06.31.31 0 00.31-.32.28.28 0 000-.09L5.7 6.42l1.17-.85A.31.31 0 006.69 5zm0 6H5.24L4.8 9.64a.31.31 0 00-.6 0L3.76 11H2.31a.31.31 0 00-.18.56l1.17.85-.45 1.39a.28.28 0 000 .09.31.31 0 00.31.32.3.3 0 00.18-.06l1.16-.85 1.17.85a.3.3 0 00.18.06.31.31 0 00.31-.32.28.28 0 000-.09l-.46-1.38 1.17-.85a.31.31 0 00-.18-.57zm0 6H5.24l-.44-1.36a.31.31 0 00-.6 0L3.76 17H2.31a.31.31 0 00-.18.56l1.17.85-.45 1.39a.28.28 0 000 .09.31.31 0 00.31.32.3.3 0 00.18-.06l1.16-.85 1.17.85a.3.3 0 00.18.06.31.31 0 00.31-.32.28.28 0 000-.09l-.46-1.38 1.17-.85a.31.31 0 00-.18-.57zM10 7h10a1 1 0 000-2H10a1 1 0 000 2zm10 4H10a1 1 0 000 2h10a1 1 0 000-2zm0 6H10a1 1 0 000 2h10a1 1 0 000-2z"></path> </svg>
								</use>
							</svg>
							<?php echo e(trans("common.save_to")); ?>

						</h3>
					</div>

					<div class="b-stats-row__content">

						<input hidden id="saved-user-id" name="saved-user-id" value="<?php echo e($timeline->user->id); ?>">
						<?php if(!empty($user_lists)): ?>
							<?php $__currentLoopData = $user_lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<div class="modal-list-item">
									<input type="checkbox" class="red-checkbox" id="list-type-<?php echo e($user_list->id); ?>" name="list-type-<?php echo e($user_list->id); ?>" <?php echo e($user_list->state == 1 ? "checked" : ""); ?>>
									<label class="red-list-label" for="list-type-<?php echo e($user_list->id); ?>"><?php echo e($user_list->list_type); ?></label>
								</div>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
						<button type="button" class="g-btn" id="add-new-list"  data-toggle="modal" data-target="#newListModal">
							<svg class="g-icon" aria-hidden="true">
								<use xlink:href="#icon-add" href="#icon-add">
									<svg id="icon-add" viewBox="0 0 24 24"> <path d="M4.5 13H11v6.5a1 1 0 0 0 2 0V13h6.5a1 1 0 0 0 0-2H13V4.5a1 1 0 0 0-2 0V11H4.5a1 1 0 0 0 0 2z"></path> </svg>
								</use>
							</svg> New list
						</button>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('common.close')); ?></button>
				</div>
			</div>

		</div>
	</div>

<div id="newListModal" class="modal fade" role="dialog" tabindex='1'>
	<div class="modal-dialog">

		<div class="modal-content">
			<div class="modal-body">
				<div class="modal-header lists-modal">
					
					<h3 class="modal-title lists-modal-title">
						<svg class="g-icon" aria-hidden="true">
							<use xlink:href="#icon-new-list" href="#icon-new-list">
								<svg id="icon-new-list" viewBox="0 0 24 24"> <path d="M22.32 9.31a1 1 0 00-.93-1l-6.17-.41-2.29-5.74a1 1 0 00-1.86 0L8.78 7.9l-6.17.41a1 1 0 00-.93 1 1 1 0 00.36.77L6.79 14l-1.52 6a1 1 0 001 1.24 1 1 0 00.53-.15l5.2-3.27 5.23 3.29a1 1 0 00.53.15 1 1 0 001-1 1 1 0 000-.24l-1.52-6L22 10.08a1 1 0 00.32-.77zm-6.87 3.59a1 1 0 00-.33 1l1.06 4.17-3.65-2.29a1 1 0 00-1.06 0l-3.65 2.3 1.06-4.17a1 1 0 00-.33-1l-3.31-2.77 4.3-.29a1 1 0 00.86-.62l1.6-4 1.6 4a1 1 0 00.86.62l4.3.29z"></path> </svg>
							</use>
						</svg>
						<?php echo e(trans("common.create_new_list")); ?>

					</h3>
				</div>

				<div class="b-stats-row__content">
					<input type="text" id="etNewListName" class="form-control" placeholder="Enter list name">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="cancelNewList" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('common.cancel')); ?></button>
				<button type="button" id="saveNewList" class="btn btn-primary" disabled><?php echo e(trans('common.save')); ?></button>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">

	$( document ).ready(function() {


	});

	<?php if($timeline->background_id != NULL): ?>
		$('body')
			.css('background-image', "url(<?php echo e(url('/wallpaper/'.$timeline->wallpaper->source)); ?>)")
			.css('background-attachment', 'fixed');
	<?php endif; ?>

	// var clipboard = new Clipboard('.btn-copy', {
	// 			text: function() {
	// 				return document.querySelector('input[type=hidden]').value;
	// 			}
	// 	});
	//
	// clipboard.on('success', function(e) {
	// 	e.clearSelection();
	// });
	//
	// if (navigator.vendor.indexOf("Apple")==0 && /\sSafari\//.test(navigator.userAgent)) {
	// 	$('.btn-copy').on('click', function() {
	// 		var msg = window.prompt("Copy this link", location.href);
	//
	// 	});
	// }

	function getProfileUrl(e) {

		/* Get the text field */
		// var copyText = document.getElementById('profile-url');

		navigator.clipboard.writeText($("#profile-url").val());
	}

	// $(".modal-list-item .red-checkbox").on("change", function() {
	//
	// 	console.log($("#" + this.id).is(":checked"));
	// });




</script>