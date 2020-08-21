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
					<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
						<h3 class="panel-title">
							<?php echo e(trans('common.general_settings')); ?>

						</h3>
					</div>
					
					<div class="panel-body nopadding">
						<div class="fans-form">
							<form method="POST" action="<?php echo e(url('/'.$username.'/settings/general/')); ?>">
								<?php echo e(csrf_field()); ?>

								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('username') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('username', trans('common.username'))); ?>

											<?php echo e(Form::text('new_username', Auth::user()->username, ['class' => 'form-control', 'placeholder' => trans('common.username')])); ?>

											<?php if($errors->has('username')): ?>
											<span class="help-block">
												<?php echo e($errors->first('username')); ?>

											</span>
											<?php endif; ?>
											<small class="text-muted"><a href="<?php echo e(url($username)); ?>"><?php echo e(url('/')); ?>/<?php echo e($username); ?></a></small>
										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('name', trans('common.fullname'))); ?>

											<?php echo e(Form::text('name', Auth::user()->name, ['class' => 'form-control', 'placeholder' => trans('common.fullname')])); ?>

											<?php if($errors->has('name')): ?>
												<span class="help-block">
												<?php echo e($errors->first('name')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>
								</div>

								<div class="row" style="margin-bottom: 10px">
									<div class="col-md-2">
										<?php echo e(Form::label('language', trans('common.language'))); ?>

									</div>
									<div class="dropdown col-md-6">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<span class="user-name">
											<?php if(Auth::user()->language != null): ?>
												<?php $key = Auth::user()->language; ?>
											<?php else: ?>
												<?php $key = App\Setting::get('language'); ?>
											<?php endif; ?>
											<?php if($key == 'gr'): ?>
												<span class="flag-icon flag-icon-gr"></span>
											<?php elseif($key == 'en'): ?>
												<span class="flag-icon flag-icon-us"></span>
											<?php elseif($key == 'zh'): ?>
												<span class="flag-icon flag-icon-cn"></span>
											<?php else: ?>
												<span class="flag-icon flag-icon-<?php echo e($key); ?>"></span>
											<?php endif; ?>

                                        </span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
										<ul class="dropdown-menu">
											<?php $__currentLoopData = Config::get('app.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<li class=""><a href="#" class="switch-language" data-language="<?php echo e($key); ?>">
														<?php if($key == 'gr'): ?>
															<span class="flag-icon flag-icon-gr"></span>
														<?php elseif($key == 'en'): ?>
															<span class="flag-icon flag-icon-us"></span>
														<?php elseif($key == 'zh'): ?>
															<span class="flag-icon flag-icon-cn"></span>
														<?php else: ?>
															<span class="flag-icon flag-icon-<?php echo e($key); ?>"></span>
														<?php endif; ?>

														<?php echo e($value); ?></a></li>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</ul>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('email', trans('auth.email_address'))); ?>

											<?php echo e(Form::email('email', Auth::user()->email, ['class' => 'form-control', 'placeholder' => trans('auth.email_address')])); ?>

											<?php if($errors->has('email')): ?>
											<span class="help-block">
												<?php echo e($errors->first('email')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required">
											<?php echo e(Form::label('gender', trans('common.gender'))); ?>

											<?php echo e(Form::select('gender', array('male' => trans('common.male'), 'female' => trans('common.female'), 'other' => trans('common.none')), Auth::user()->gender, array('class' => 'form-control'))); ?>

										</fieldset>
									</div>






								</div>

								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group <?php echo e($errors->has('subscribe_price') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('subscribe_price', trans('auth.subscribe_price'))); ?>

											<?php echo e(Form::text('subscribe_price', Auth::user()->price, ['class' => 'form-control', 'placeholder' => trans('auth.subscribe_price')])); ?>

											<?php if($errors->has('subscribe_price')): ?>
												<span class="help-block">
												<?php echo e($errors->first('subscribe_price')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>
								</div>
								

									<?php if(Setting::get('custom_option1') != NULL || Setting::get('custom_option2') != NULL): ?>
										<div class="row">
											<?php if(Setting::get('custom_option1') != NULL): ?>
											<div class="col-md-6">
												<fieldset class="form-group">
													<?php echo e(Form::label('custom_option1', Setting::get('custom_option1'))); ?>

													<?php echo e(Form::text('custom_option1', Auth::user()->custom_option1, ['class' => 'form-control'])); ?>

												</fieldset>
											</div>
											<?php endif; ?>

											<?php if(Setting::get('custom_option2') != NULL): ?>
											<div class="col-md-6">
												<fieldset class="form-group">
													<?php echo e(Form::label('custom_option2', Setting::get('custom_option2'))); ?>

													<?php echo e(Form::text('custom_option2', Auth::user()->custom_option2, ['class' => 'form-control'])); ?>

												</fieldset>
											</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if(Setting::get('custom_option3') != NULL || Setting::get('custom_option4') != NULL): ?>
										<div class="row">
											<?php if(Setting::get('custom_option3') != NULL): ?>
											<div class="col-md-6">
												<fieldset class="form-group">
													<?php echo e(Form::label('custom_option3', Setting::get('custom_option3'))); ?>

													<?php echo e(Form::text('custom_option3', Auth::user()->custom_option3, ['class' => 'form-control'])); ?>

												</fieldset>
											</div>
											<?php endif; ?>

											<?php if(Setting::get('custom_option4') != NULL): ?>
											<div class="col-md-6">
												<fieldset class="form-group">
													<?php echo e(Form::label('custom_option4', Setting::get('custom_option4'))); ?>

													<?php echo e(Form::text('custom_option4', Auth::user()->custom_option4, ['class' => 'form-control'])); ?>

												</fieldset>
											</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>


									<div class="pull-right">
										<?php echo e(Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success'])); ?>

									</div>
									<div class="clearfix"></div>
								</form>
							</div><!-- /fans-form -->
						</div>
					</div>
					<!-- End of first panel -->

					<div class="panel panel-default">
						<div class="panel-heading no-bg panel-settings">
							<h3 class="panel-title">
								<?php echo e(trans('common.update_password')); ?>

							</h3>
						</div>
						<div class="panel-body nopadding">
							<div class="fans-form">
								<form method="POST" action="<?php echo e(url('/'.Auth::user()->username.'/settings/password/')); ?>">
									<?php echo e(csrf_field()); ?>


									<div class="row">
										<div class="col-md-6">
											<fieldset class="form-group <?php echo e($errors->has('current_password') ? ' has-error' : ''); ?>">
												<?php echo e(Form::label('current_password', trans('common.current_password'))); ?>

												<input type="password" class="form-control" id="current_password" name="current_password" value="<?php echo e(old('current_password')); ?>" placeholder= "<?php echo e(trans('messages.enter_old_password')); ?>">

												<?php if($errors->has('current_password')): ?>
												<span class="help-block">
													<?php echo e($errors->first('current_password')); ?>

												</span>
												<?php endif; ?>
											</fieldset>
										</div>
										<div class="col-md-6">
											<fieldset class="form-group <?php echo e($errors->has('new_password') ? ' has-error' : ''); ?>">
												<?php echo e(Form::label('new_password', trans('common.new_password'))); ?>

												<input type="password" class="form-control" id="new_password" name="new_password" value="<?php echo e(old('new_password')); ?>" placeholder="<?php echo e(trans('messages.enter_new_password')); ?>">

												<?php if($errors->has('new_password')): ?>
												<span class="help-block">
													<?php echo e($errors->first('new_password')); ?>

												</span>
												<?php endif; ?>
											</fieldset>
										</div>
									</div>

									<div class="pull-right">
										<?php echo e(Form::submit(trans('common.save_password'), ['class' => 'btn btn-success'])); ?>

									</div>
									<div class="clearfix"></div>
								</form>
							</div><!-- /fans-form -->
						</div>
					</div>
					<!-- End of second panel -->

				</div>
			</div><!-- /row -->
		</div>
	<!-- </div> --><!-- /main-content -->
