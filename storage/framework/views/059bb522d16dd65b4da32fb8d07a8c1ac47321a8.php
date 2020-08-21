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
							<?php echo e(trans('common.add_payment')); ?>

						</h3>
					</div>
					<div class="panel-body nopadding">
						<p hidden id="username" data-username=<?php echo e($username); ?>></p>
						<div class="fans-form">
							<!--<form method="POST" action="<?php echo e(url('/'.$username.'/settings/save-payment-details')); ?>">-->
							<form method="POST" id="addPaymentForm">
								<?php echo e(csrf_field()); ?>

								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('card_name') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('card_name', trans('common.name_on_card'))); ?>

											<?php echo e(Form::text('card_name', Auth::user()->name, ['class' => 'form-control', 'placeholder' => trans('common.name_on_card')])); ?>

											<?php if($errors->has('card_name')): ?>
											<span class="help-block">
												<?php echo e($errors->first('card_name')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required  <?php echo e($errors->has('card_number') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('card_number', trans('common.card_number'))); ?>

											<?php echo e(Form::text('card_number', '', array('class' => 'form-control', 'placeholder' => trans('---- ---- ---- ----')))); ?>

											<?php if($errors->has('card_number')): ?>
												<span class="help-block">
												<?php echo e($errors->first('card_number')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>
								</div>
								
								<div class="row">
									<!--<div class="col-md-3">-->
									<!--	<fieldset class="form-group required <?php echo e($errors->has('exp_mm') ? ' has-error' : ''); ?> ">-->
									<!--		<?php echo e(Form::label('exp_mm', trans('Expiration Month'))); ?>-->
											<!--<?php if(Auth::user()->is_payment_set == true): ?>-->
											<!--	<?php echo e(Form::text('exp_mm', Auth::user()->payment->exp_mm, array('class' => 'form-control', 'placeholder' => trans('MM')))); ?>-->
											<!--<?php else: ?>-->
									<!--			<?php echo e(Form::text('exp_mm', '', array('class' => 'form-control', 'placeholder' => trans('MM')))); ?>-->
											<!--<?php endif; ?>-->
									<!--		<?php if($errors->has('exp_mm')): ?>-->
									<!--			<span class="help-block">-->
									<!--			<?php echo e($errors->first('exp_mm')); ?>-->
									<!--		</span>-->
									<!--		<?php endif; ?>-->
									<!--	</fieldset>-->
									<!--</div>-->
									<div class="col-md-6">
										<fieldset class="form-group required <?php echo e($errors->has('expiry') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('expiry', trans('common.expiration_year'))); ?>

											<?php echo e(Form::text('expiry', '', array('class' => 'form-control', 'placeholder' => trans('-- / ----')))); ?>

											<?php if($errors->has('expiry')): ?>
												<span class="help-block">
												<?php echo e($errors->first('expiry')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>
									<div class="col-md-3">
										<fieldset class="form-group required  <?php echo e($errors->has('cvv') ? ' has-error' : ''); ?>">
											<?php echo e(Form::label('cvv', trans('common.cvv'))); ?>

											<?php echo e(Form::text('cvv', '', array('class' => 'form-control', 'placeholder' => trans('---')))); ?>

											<?php if($errors->has('cvv')): ?>
												<span class="help-block">
												<?php echo e($errors->first('cvv')); ?>

											</span>
											<?php endif; ?>
										</fieldset>
									</div>									
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<?php echo e(Form::label('billing_address', trans('common.billing_street_address'))); ?>

												<?php echo e(Form::text('billing_address', '', ['class' => 'form-control', 'placeholder' => trans('common.billing_street_address')])); ?>

										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group">
											<?php echo e(Form::label('billing_city', trans('common.city'))); ?>

												<?php echo e(Form::text('billing_city', '', ['class' => 'form-control', 'placeholder' => trans('common.city')])); ?>

										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<?php echo e(Form::label('billing_state', trans('common.state_province'))); ?>

												<?php echo e(Form::text('billing_state', '', ['class' => 'form-control', 'placeholder' => trans('common.state_province')])); ?>

										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group">
											<?php echo e(Form::label('billing_zip', trans('common.zip_postal_code'))); ?>

												<?php echo e(Form::text('billing_zip', '', ['class' => 'form-control', 'placeholder' => trans('common.zip_postal_code')])); ?>

										</fieldset>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-10">
										<fieldset class="form-group <?php echo e($errors->has('cvv') ? ' has-error' : ''); ?>">
											<?php echo e(Form::checkbox('subscribe_content_confirm', 'Yes')); ?>

											<?php echo e(Form::label('subscribe_content_confirm',trans('common.transaction_confirm'))); ?>

										</fieldset>
										<?php if($errors->has('subscribe_content_confirm')): ?>
											<span class="help-block">
												<?php echo e($errors->first('subscribe_content_confirm')); ?>

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
										<?php echo e(Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success'])); ?>

									</div>
									<div class="clearfix"></div>
								</form>
							</div>
						</div>
					</div>
					<!-- End of first panel -->















































					<!-- End of second panel -->

				</div>
			</div><!-- /row -->
		</div>
	<!-- </div> --><!-- /main-content -->
	
	<script src="../../js/dist/jquery.payform.js"></script>
<script>
	$('input[name="card_number"]').payform('formatCardNumber');
	$('input[name="expiry"]').payform('formatCardExpiry');
	$('input[name="cvv"]').payform('formatCardCVC');
</script>
