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
							<?php echo e(trans('common.add_bank')); ?>

						</h3>
					</div>
					<div class="panel-body nopadding">
						<div class="fans-form">
							<form  method="POST" action="<?php echo e(url('/'.$username.'/settings/save-bank-details')); ?>" class=".bank-details">
								<?php echo e(csrf_field()); ?>

								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('name', trans('common.fullname'))); ?>

											<?php echo e(Form::text('name', Auth::user()->name, ['name' => 'name', 'value' => 'value','class' => 'form-control', 'placeholder' => trans('common.fullname')])); ?>

											<?php if($errors->has('name')): ?>
											<span class="help-block">
												<?php echo e($errors->first('name')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('gender') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('gender', trans('common.gender'))); ?>

											<?php echo e(Form::select('gender', array('male' => trans('common.male'), 'female' => trans('common.female'), 'other' => trans('common.none')), Auth::user()->gender, array('class' => 'form-control'))); ?>

										</fieldset>
										<?php if($errors->has('gender')): ?>
											<span class="help-block">
												<?php echo e($errors->first('gender')); ?>

											</span>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('birthday') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('birthday', trans('common.birthday'))); ?>

											<div class="input-group date datepicker">
												<span class="input-group-addon addon-left calendar-addon">
													<span class="fa fa-calendar"></span>
												</span>
												<?php echo e(Form::text('birthday', Auth::user()->birthday, ['class' => 'form-control', 'id' => 'datepicker1'])); ?>

												<span class="input-group-addon addon-right angle-addon">
													<span class="fa fa-angle-down"></span>
												</span>
											</div>
										</fieldset>
										<?php if($errors->has('birthday')): ?>
											<span class="help-block">
												<?php echo e($errors->first('birthday')); ?>

											</span>
										<?php endif; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('country') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('country', trans('common.country'))); ?>

											<?php echo e(Form::text('country', Auth::user()->payment != NULL ? Auth::user()->payment->country : '', array('class' => 'form-control', 'placeholder' => trans('common.country')))); ?>

										</fieldset>
										<?php if($errors->has('country')): ?>
											<span class="help-block">
												<?php echo e($errors->first('country')); ?>

											</span>
										<?php endif; ?>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('address') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('address', trans('street_address'))); ?>

											<?php echo e(Form::text('address', Auth::user()->payment != NULL ? Auth::user()->payment->address : '', ['class' => 'form-control', 'placeholder' => trans('street_address_placeholder')])); ?>

										</fieldset>
										<?php if($errors->has('address')): ?>
											<span class="help-block">
												<?php echo e($errors->first('address')); ?>

											</span>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('city', trans('common.city_placeholder'))); ?>

											<?php echo e(Form::text('city', Auth::user()->payment != NULL ? Auth::user()->payment->city : '', ['class' => 'form-control', 'placeholder' => trans('common.city')])); ?>

										</fieldset>
										<?php if($errors->has('city')): ?>
											<span class="help-block">
												<?php echo e($errors->first('city')); ?>

											</span>
										<?php endif; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('state') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('state', trans('common.state_province'))); ?>

											<?php echo e(Form::text('state', Auth::user()->payment != NULL ? Auth::user()->payment->state : '', ['class' => 'form-control', 'placeholder' => trans('common.state_province')])); ?>

										</fieldset>
										<?php if($errors->has('state')): ?>
											<span class="help-block">
												<?php echo e($errors->first('state')); ?>

											</span>
										<?php endif; ?>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('zip') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('zip', trans('common.zip_postal_code'))); ?>

											<?php echo e(Form::text('zip', Auth::user()->payment != NULL ? Auth::user()->payment->zip : '', ['class' => 'form-control', 'placeholder' => trans('common.zip_postal_code')])); ?>

										</fieldset>
										<?php if($errors->has('zip')): ?>
											<span class="help-block">
												<?php echo e($errors->first('zip')); ?>

											</span>
										<?php endif; ?>
									</div>
								</div>
								


























								<div class="row">
									<div class="col-md-10">
										<fieldset class="form-group <?php echo e($errors->has('sell_content_confirm') ? ' has-error' : ''); ?>">
											<?php echo e(Form::checkbox('sell_content_confirm', 'Yes')); ?>

											<?php echo e(Form::label('sell_content_confirm', trans('messages.adult_content_confirm'))); ?>

										</fieldset>
										<?php if($errors->has('sell_content_confirm')): ?>
											<span class="help-block">
												<?php echo e($errors->first('sell_content_confirm')); ?>

											</span>
										<?php endif; ?>
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
										<?php echo e(Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success oauth-link'])); ?>

									</div>
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
					</div>
					<!-- End of first panel -->


				</div>
			</div><!-- /row -->
		</div>
	<!-- </div> --><!-- /main-content -->
