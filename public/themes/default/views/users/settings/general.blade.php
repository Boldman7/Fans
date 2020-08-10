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
							{{ trans('common.general_settings') }}
						</h3>
					</div>
					
					<div class="panel-body nopadding">
						<div class="fans-form">
							<form method="POST" action="{{ url('/'.$username.'/settings/general/') }}">
								{{ csrf_field() }}
								
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('username') ? ' has-error' : '' }}">
											{{ Form::label('username', trans('common.username')) }}
											{{ Form::text('new_username', Auth::user()->username, ['class' => 'form-control', 'placeholder' => trans('common.username')]) }}
											@if ($errors->has('username'))
											<span class="help-block">
												{{ $errors->first('username') }}
											</span>
											@endif
											<small class="text-muted"><a href="{{ url($username) }}">{{ url('/') }}/{{$username}}</a></small>
										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('name') ? ' has-error' : '' }}">
											{{ Form::label('name', trans('common.fullname')) }}
											{{ Form::text('name', Auth::user()->name, ['class' => 'form-control', 'placeholder' => trans('common.fullname')]) }}
											@if ($errors->has('name'))
												<span class="help-block">
												{{ $errors->first('name') }}
											</span>
											@endif
										</fieldset>
									</div>
								</div>

								<div class="row" style="margin-bottom: 10px">
									<div class="col-md-2">
										{{ Form::label('language', trans('common.language')) }}
									</div>
									<div class="dropdown col-md-6">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<span class="user-name">
											@if(Auth::user()->language != null)
												<?php $key = Auth::user()->language; ?>
											@else
												<?php $key = App\Setting::get('language'); ?>
											@endif
											@if($key == 'gr')
												<span class="flag-icon flag-icon-gr"></span>
											@elseif($key == 'en')
												<span class="flag-icon flag-icon-us"></span>
											@elseif($key == 'zh')
												<span class="flag-icon flag-icon-cn"></span>
											@else
												<span class="flag-icon flag-icon-{{ $key }}"></span>
											@endif

                                        </span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
										<ul class="dropdown-menu">
											@foreach( Config::get('app.locales') as $key => $value)
												<li class=""><a href="#" class="switch-language" data-language="{{ $key }}">
														@if($key == 'gr')
															<span class="flag-icon flag-icon-gr"></span>
														@elseif($key == 'en')
															<span class="flag-icon flag-icon-us"></span>
														@elseif($key == 'zh')
															<span class="flag-icon flag-icon-cn"></span>
														@else
															<span class="flag-icon flag-icon-{{ $key }}"></span>
														@endif

														{{ $value }}</a></li>
											@endforeach
										</ul>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('email') ? ' has-error' : '' }}">
											{{ Form::label('email', trans('auth.email_address')) }}
											{{ Form::email('email', Auth::user()->email, ['class' => 'form-control', 'placeholder' => trans('auth.email_address')]) }}
											@if ($errors->has('email'))
											<span class="help-block">
												{{ $errors->first('email') }}
											</span>
											@endif
										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group required">
											{{ Form::label('gender', trans('common.gender')) }}
											{{ Form::select('gender', array('male' => trans('common.male'), 'female' => trans('common.female'), 'other' => trans('common.none')), Auth::user()->gender, array('class' => 'form-control')) }}
										</fieldset>
									</div>
{{--									<div class="col-md-6">--}}
{{--										<fieldset class="form-group required {{ $errors->has('password') ? ' has-error' : '' }}">--}}
{{--											{{ Form::label('password', trans('auth.password')) }}--}}
{{--											{{ Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder'=> trans('auth.password')]) }}--}}
{{--										</fieldset>--}}
{{--									</div>--}}
								</div>

								{{--Subscribe price--}}
								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group {{ $errors->has('subscribe_price') ? ' has-error' : '' }}">
											{{ Form::label('subscribe_price', trans('auth.subscribe_price')) }}
											{{ Form::text('subscribe_price', Auth::user()->price, ['class' => 'form-control', 'placeholder' => trans('auth.subscribe_price')]) }}
											@if ($errors->has('subscribe_price'))
												<span class="help-block">
												{{ $errors->first('subscribe_price') }}
											</span>
											@endif
										</fieldset>
									</div>
								</div>
								{{--End of Subscribe price--}}

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
										{{ Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success']) }}
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
								{{ trans('common.update_password') }}
							</h3>
						</div>
						<div class="panel-body nopadding">
							<div class="fans-form">
								<form method="POST" action="{{ url('/'.Auth::user()->username.'/settings/password/') }}">
									{{ csrf_field() }}

									<div class="row">
										<div class="col-md-6">
											<fieldset class="form-group {{ $errors->has('current_password') ? ' has-error' : '' }}">
												{{ Form::label('current_password', trans('common.current_password')) }}
												<input type="password" class="form-control" id="current_password" name="current_password" value="{{ old('current_password') }}" placeholder= "{{ trans('messages.enter_old_password') }}">

												@if ($errors->has('current_password'))
												<span class="help-block">
													{{ $errors->first('current_password') }}
												</span>
												@endif
											</fieldset>
										</div>
										<div class="col-md-6">
											<fieldset class="form-group {{ $errors->has('new_password') ? ' has-error' : '' }}">
												{{ Form::label('new_password', trans('common.new_password')) }}
												<input type="password" class="form-control" id="new_password" name="new_password" value="{{ old('new_password') }}" placeholder="{{ trans('messages.enter_new_password') }}">

												@if($errors->has('new_password'))
												<span class="help-block">
													{{ $errors->first('new_password') }}
												</span>
												@endif
											</fieldset>
										</div>
									</div>

									<div class="pull-right">
										{{ Form::submit(trans('common.save_password'), ['class' => 'btn btn-success']) }}
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
