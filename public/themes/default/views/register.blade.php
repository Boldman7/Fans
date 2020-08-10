<div class="container">

<div class="row tpadding-20">
  <div class="col-md-6">

    <h2 class="register-heading">{{ trans('common.create_account') }}</h2>
    <div class="panel panel-default">
      <div class="panel-body nopadding">

        <div class="login-bottom">

          <ul class="signup-errors text-danger list-unstyled"></ul>

          <form method="POST" class="signup-form" action="{{ url('/register') }}">
            {{ csrf_field() }}

        <div class="row" hidden>
            <div class="col-md-6">
                <fieldset class="form-group{{ $errors->has('affiliate') ? ' has-error' : '' }}">
                  {{ Form::label('affiliate', trans('auth.affiliate_code')) }}<i class="optional">(optional)</i>
                  @if(isset($_GET['affiliate']))
                  {{ Form::text('affiliate', $_GET['affiliate'], ['class' => 'form-control', 'id' => 'affiliate', 'disabled' =>'disabled']) }}
                  {{ Form::hidden('affiliate', $_GET['affiliate']) }}
                  @else
                  {{ Form::text('affiliate', NULL, ['class' => 'form-control', 'id' => 'affiliate', 'placeholder'=> trans('auth.affiliate_code')]) }}
                  @endif
                  @if ($errors->has('affiliate'))
                  <span class="help-block">
                    {{ $errors->first('affiliate') }}
                  </span>
                  @endif
                </fieldset>
            </div>
        </div>

            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required {{ $errors->has('email') ? ' has-error' : '' }}">
                  {{ Form::label('email', trans('auth.email_address')) }} 
                  {{ Form::text('email', NULL, ['class' => 'form-control', 'id' => 'email', 'placeholder'=> trans('auth.email_address')]) }}
                  <ul class="signup-email-error text-danger list-unstyled">
                  </ul>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required {{ $errors->has('name') ? ' has-error' : '' }}">
                  {{ Form::label('name', trans('auth.name')) }} 
                  {{ Form::text('name', NULL, ['class' => 'form-control', 'id' => 'name', 'placeholder'=> trans('auth.name')]) }}

                  <ul class="signup-name-error text-danger list-unstyled">
                  </ul>
                </fieldset>
              </div>
            </div>

{{--            <div class="row">--}}
{{--              <div class="col-md-12">--}}
{{--                <fieldset class="form-group required {{ $errors->has('gender') ? ' has-error' : '' }}">--}}
{{--                  {{ Form::label('gender', trans('common.gender')) }}--}}
{{--                  {{ Form::select('gender', array('female' => 'Female', 'male' => 'Male', 'other' => 'None'), null, ['placeholder' => trans('auth.select_gender'), 'class' => 'form-control']) }}--}}
{{--                  @if ($errors->has('gender'))--}}
{{--                  <span class="help-block">--}}
{{--                    {{ $errors->first('gender') }}--}}
{{--                  </span>--}}
{{--                  @endif--}}
{{--                </fieldset>--}}
{{--              </div>--}}
{{--            </div>--}}

            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required {{ $errors->has('username') ? ' has-error' : '' }}">
                  {{ Form::label('username', trans('common.username')) }} 
                  {{ Form::text('username', NULL, ['class' => 'form-control', 'id' => 'username', 'placeholder'=> trans('common.username')]) }}

                  <ul class="signup-username-error text-danger list-unstyled">
                  </ul>
                <small class="text-muted"><a href="{{ url('/') }}">{{ url('/') }}/username</a></small>
                </fieldset>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required {{ $errors->has('password') ? ' has-error' : '' }}">
                  {{ Form::label('password', trans('auth.password')) }} 
                  {{ Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder'=> trans('auth.password')]) }}

                  <ul class="signup-password-error text-danger list-unstyled">
                  </ul>
                </fieldset>
              </div>
            </div>

            <div class="row">
{{--              @if(Setting::get('birthday') == "on")--}}
{{--              <div class="col-md-6">--}}
{{--                <fieldset class="form-group">--}}
{{--                  {{ Form::label('birthday', trans('common.birthday')) }}<i class="optional">(optional)</i>--}}
{{--                  <div class="input-group date datepicker">--}}
{{--                    <span class="input-group-addon addon-left calendar-addon">--}}
{{--                      <span class="fa fa-calendar"></span>--}}
{{--                    </span>--}}
{{--                    {{ Form::text('birthday', NULL, ['class' => 'form-control', 'id' => 'datepicker1']) }}--}}
{{--                    <span class="input-group-addon addon-right angle-addon">--}}
{{--                      <span class="fa fa-angle-down"></span>--}}
{{--                    </span>--}}
{{--                  </div>--}}
{{--                </fieldset>--}}
{{--              </div>--}}
{{--              @endif--}}

{{--              @if(Setting::get('city') == "on")--}}
{{--              <div class="col-md-6">--}}
{{--                <fieldset class="form-group">--}}
{{--                  {{ Form::label('city', trans('common.current_city')) }}<i class="optional">(optional)</i>--}}
{{--                  {{ Form::text('city', NULL, ['class' => 'form-control', 'placeholder' => trans('common.current_city')]) }}--}}
{{--                </fieldset>--}}
{{--              </div>--}}
{{--              @endif   --}}
            </div>

            <div class="row">
              @if(Setting::get('captcha') == "on")
              <div class="col-md-12">
                <fieldset class="form-group{{ $errors->has('captcha_error') ? ' has-error' : '' }}">
                  {!! app('captcha')->display() !!}
                  @if ($errors->has('captcha_error'))
                  <span class="help-block">
                    {{ $errors->first('captcha_error') }}
                  </span>
                  @endif
                </fieldset>
              </div>    
              @endif    
            </div>

            {{ Form::button(trans('auth.signup_to_dashboard'), ['type' => 'submit','class' => 'btn btn-success btn-submit']) }}
{{--            <a href="/login" class="btn btn-success" style="margin-top: 10px">{{  trans('common.signin') }}</a>--}}
          </form>
        </div>  
        @if(config('services.google.client_id') != NULL && config('services.google.client_secret') ||
          config('services.twitter.client_id') != NULL && config('services.twitter.client_secret') ||
          config('services.facebook.client_id') != NULL && config('services.facebook.client_secret') ||
          config('services.linkedin.client_id') != NULL && config('services.linkedin.client_secret') )
          <div class="divider-login">
            <div class="divider-text"> {{ trans('auth.login_via_social_networks') }}</div>
          </div>
          @endif
          <ul class="list-unstyled social-connect">

{{--            <li><a href="{{ url('twitter') }}" class="btn btn-social tw"><span class="social-circle"><i class="fa fa-twitter" aria-hidden="true"></i></span></a></li>--}}
{{--            <li><a href="{{ url('facebook') }}" class="btn btn-social fb"><span class="social-circle"><i class="fa fa-facebook" aria-hidden="true"></i></span></a></li>--}}

            <li style="margin-bottom: 5px"><a href="{{ url('twitter') }}" class="btn btn-social tw"><span style="color: white">{{ trans('common.signup_twitter') }} </span><i class="social-circle fa fa-twitter" aria-hidden="true"></i></a></li>
            <li><a href="{{ url('facebook') }}" class="btn btn-social fb"><span style="color: white">{{ trans('common.signup_facebook') }} </span><i class="social-circle fa fa-facebook" aria-hidden="true"></i></a></li>
          </ul>
        </div>
      </div><!-- /panel -->
    </div>
    
  </div><!-- /row -->
</div><!-- /container -->
{!! Theme::asset()->container('footer')->usePath()->add('app', 'js/app.js') !!}