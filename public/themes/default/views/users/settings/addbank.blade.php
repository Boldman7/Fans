<!-- main-section -->
<!-- <div class="main-content"> -->
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="post-filters">
					{!! Theme::partial('usermenu-settings') !!}
				</div>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default">
				
					<div class="panel-heading no-bg panel-settings">
					@include('flash::message')
						<h3 class="panel-title">
							{{ trans('common.add_bank') }}
						</h3>
					</div>
					<div class="panel-body nopadding">
						<div class="fans-form">
							<form  method="POST" action="{{ url('/'.$username.'/settings/save-bank-details') }}" class=".bank-details">
								{{ csrf_field() }}
								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('name') ? ' has-error' : '' }}">
											{{ Form::label('name', trans('common.fullname')) }}
											{{ Form::text('name', Auth::user()->name, ['name' => 'name', 'value' => 'value','class' => 'form-control', 'placeholder' => trans('common.fullname')]) }}
											@if ($errors->has('name'))
											<span class="help-block">
												{{ $errors->first('name') }}
											</span>
											@endif
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('gender') ? ' has-error' : '' }}">
											{{ Form::label('gender', trans('common.gender')) }}
											{{ Form::select('gender', array('male' => trans('common.male'), 'female' => trans('common.female'), 'other' => trans('common.none')), Auth::user()->gender, array('class' => 'form-control')) }}
										</fieldset>
										@if ($errors->has('gender'))
											<span class="help-block">
												{{ $errors->first('gender') }}
											</span>
										@endif
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('birthday') ? ' has-error' : '' }}">
											{{ Form::label('birthday', trans('common.birthday')) }}
											<div class="input-group date datepicker">
												<span class="input-group-addon addon-left calendar-addon">
													<span class="fa fa-calendar"></span>
												</span>
												{{ Form::text('birthday', Auth::user()->birthday, ['class' => 'form-control', 'id' => 'datepicker1']) }}
												<span class="input-group-addon addon-right angle-addon">
													<span class="fa fa-angle-down"></span>
												</span>
											</div>
										</fieldset>
										@if ($errors->has('birthday'))
											<span class="help-block">
												{{ $errors->first('birthday') }}
											</span>
										@endif
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('country') ? ' has-error' : '' }}">
											{{ Form::label('country', trans('common.country')) }}
											{{ Form::text('country', Auth::user()->payment != NULL ? Auth::user()->payment->country : '', array('class' => 'form-control', 'placeholder' => trans('common.country'))) }}
										</fieldset>
										@if ($errors->has('country'))
											<span class="help-block">
												{{ $errors->first('country') }}
											</span>
										@endif
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('address') ? ' has-error' : '' }}">
											{{ Form::label('address', trans('street_address')) }}
											{{ Form::text('address', Auth::user()->payment != NULL ? Auth::user()->payment->address : '', ['class' => 'form-control', 'placeholder' => trans('street_address_placeholder')]) }}
										</fieldset>
										@if ($errors->has('address'))
											<span class="help-block">
												{{ $errors->first('address') }}
											</span>
										@endif
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('city') ? ' has-error' : '' }}">
											{{ Form::label('city', trans('common.city_placeholder')) }}
											{{ Form::text('city', Auth::user()->payment != NULL ? Auth::user()->payment->city : '', ['class' => 'form-control', 'placeholder' => trans('common.city')]) }}
										</fieldset>
										@if ($errors->has('city'))
											<span class="help-block">
												{{ $errors->first('city') }}
											</span>
										@endif
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('state') ? ' has-error' : '' }}">
											{{ Form::label('state', trans('common.state_province')) }}
											{{ Form::text('state', Auth::user()->payment != NULL ? Auth::user()->payment->state : '', ['class' => 'form-control', 'placeholder' => trans('common.state_province')]) }}
										</fieldset>
										@if ($errors->has('state'))
											<span class="help-block">
												{{ $errors->first('state') }}
											</span>
										@endif
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('zip') ? ' has-error' : '' }}">
											{{ Form::label('zip', trans('common.zip_postal_code')) }}
											{{ Form::text('zip', Auth::user()->payment != NULL ? Auth::user()->payment->zip : '', ['class' => 'form-control', 'placeholder' => trans('common.zip_postal_code')]) }}
										</fieldset>
										@if ($errors->has('zip'))
											<span class="help-block">
												{{ $errors->first('zip') }}
											</span>
										@endif
									</div>
								</div>
								
{{--								<div class="row">--}}
{{--									<div class="col-md-6">--}}
{{--										<fieldset class="form-group">--}}
{{--											{{ Form::label('document', trans('Document Type')) }}--}}
{{--											{{ Form::select('document', array('Select' => 'Select One...', 'Passport' => 'Passport', 'Drivers License' => 'Drivers License', 'ID Card' => 'ID Card'), 'Select') }}--}}
{{--										</fieldset>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--								--}}
{{--								<div class="row">--}}
{{--									<div class="col-md-6">--}}
{{--										<fieldset class="form-group">--}}
{{--											{{ Form::label('photoID', trans('Photo of Your ID')) }}--}}
{{--											{{ Form::file('image') }}--}}
{{--										</fieldset>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--								<div class="row">--}}
{{--									<div class="col-md-6">--}}
{{--										<fieldset class="form-group">--}}
{{--											{{ Form::label('photoholdingID', trans('Photo of You Holding ID')) }}--}}
{{--											{{ Form::file('image') }}--}}
{{--										</fieldset>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--								--}}
								<div class="row">
									<div class="col-md-10">
										<fieldset class="form-group {{ $errors->has('sell_content_confirm') ? ' has-error' : '' }}">
											{{ Form::checkbox('sell_content_confirm', 'Yes') }}
											{{ Form::label('sell_content_confirm', trans('messages.adult_content_confirm')) }}
										</fieldset>
										@if ($errors->has('sell_content_confirm'))
											<span class="help-block">
												{{ $errors->first('sell_content_confirm') }}
											</span>
										@endif
									</div>
								</div>
									
									@if(Setting::get('custom_option1') != NULL || Setting::get('custom_option2') != NULL)
										<div class="row">
											@if(Setting::get('custom_option1') != NULL)
											<div class="col-md-6">
												<fieldset class="form-group">
													{{ Form::label('custom_option1', Setting::get('custom_option1')) }}
													{{ Form::text('custom_option1', Auth::user()->custom_option1, ['class' => 'form-control']) }}
												</fieldset>
											</div>
											@endif

											@if(Setting::get('custom_option2') != NULL)
											<div class="col-md-6">
												<fieldset class="form-group">
													{{ Form::label('custom_option2', Setting::get('custom_option2')) }}
													{{ Form::text('custom_option2', Auth::user()->custom_option2, ['class' => 'form-control']) }}
												</fieldset>
											</div>
											@endif
										</div>
									@endif

									@if(Setting::get('custom_option3') != NULL || Setting::get('custom_option4') != NULL)
										<div class="row">
											@if(Setting::get('custom_option3') != NULL)
											<div class="col-md-6">
												<fieldset class="form-group">
													{{ Form::label('custom_option3', Setting::get('custom_option3')) }}
													{{ Form::text('custom_option3', Auth::user()->custom_option3, ['class' => 'form-control']) }}
												</fieldset>
											</div>
											@endif

											@if(Setting::get('custom_option4') != NULL)
											<div class="col-md-6">
												<fieldset class="form-group">
													{{ Form::label('custom_option4', Setting::get('custom_option4')) }}
													{{ Form::text('custom_option4', Auth::user()->custom_option4, ['class' => 'form-control']) }}
												</fieldset>
											</div>
											@endif
										</div>
									@endif


									<div class="pull-right">
										{{ Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success oauth-link']) }}
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
