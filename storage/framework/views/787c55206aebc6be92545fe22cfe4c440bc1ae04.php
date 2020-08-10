<!-- main-section -->
<!-- <div class="main-content"> -->
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="post-filters">
					<?php echo Theme::partial('usermenu-settings'); ?>

				</div>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading no-bg panel-settings">
						<h3 class="panel-title">
							<?php echo e(trans('common.privacy_settings')); ?>

						</h3>
					</div>
					<div class="panel-body">
						<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

						<?php echo e(Form::open(array('class' => 'form-inline','url' => Auth::user()->username.'/settings/privacy', 'method' => 'post'))); ?>


						<?php echo e(csrf_field()); ?>

						<div class="privacy-question">
							
							<ul class="list-group">
								<!--<li href="#" class="list-group-item">-->
								<!--	<fieldset class="form-group">-->
								<!--		<?php echo e(Form::label('confirm_follow', trans('common.label_confirm_request'))); ?>-->
								<!--		<?php echo e(Form::select('confirm_follow', array('yes' => trans('common.yes'), 'no' => trans('common.no')), $settings->confirm_follow, array('class' => 'form-control follow'))); ?>-->
								<!--	</fieldset>-->
								<!--</li>-->
								<!--<li href="#" class="list-group-item">-->
								<!--	<fieldset class="form-group">-->
								<!--		<?php echo e(Form::label('follow_privacy', trans('common.label_follow_privacy'))); ?>-->
								<!--		<?php echo e(Form::select('follow_privacy', array('everyone' => trans('common.everyone'), 'only_follow' => trans('common.people_i_follow')), $settings->follow_privacy, array('class' => 'form-control'))); ?>-->
								<!--	</fieldset>-->
								<!--</li>-->
								<li href="#" class="list-group-item">
									<fieldset class="form-group">
										<?php echo e(Form::label('comment_privacy', trans('common.label_comment_privacy'))); ?>

										<?php echo e(Form::select('comment_privacy', array('everyone' => trans('common.everyone'), 'only_follow' => trans('common.people_i_follow')), $settings->comment_privacy, array('class' => 'form-control'))); ?>

									</fieldset>
								</li>
								<li href="#" class="list-group-item">
									<fieldset class="form-group">
										<?php echo e(Form::label('timeline_post_privacy', trans('common.label_timline_post_privacy'))); ?>

										<?php echo e(Form::select('timeline_post_privacy', array('everyone' => trans('common.everyone'), 'only_follow' => trans('common.people_i_follow'), 'nobody' => trans('common.no_one')), $settings->timeline_post_privacy, array('class' => 'form-control'))); ?>

									</fieldset>
								</li>
								<li href="#" class="list-group-item">
									<fieldset class="form-group">
										<?php echo e(Form::label('post_privacy', trans('common.label_post_privacy'))); ?>

										<?php echo e(Form::select('post_privacy', array('everyone' => trans('common.everyone'), 'only_follow' => trans('common.people_i_follow')), $settings->post_privacy, array('class' => 'form-control'))); ?>

									</fieldset>
								</li>
								<li href="#" class="list-group-item">
									<fieldset class="form-group">
										<?php echo e(Form::label('message_privacy', trans('common.label_message_privacy'))); ?>

										<?php echo e(Form::select('message_privacy', array('everyone' => trans('common.everyone'), 'only_follow' => trans('common.people_i_follow')), $settings->message_privacy, array('class' => 'form-control'))); ?>

									</fieldset>
								</li>
							</ul>
							<div class="pull-right">
								<?php echo e(Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success'])); ?>

							</div>
						</div>
						<?php echo e(Form::close()); ?>

					</div>
				</div><!-- /panel -->
			</div>
		</div><!-- /row -->
	</div>
<!-- </div> --><!-- /main-content -->
