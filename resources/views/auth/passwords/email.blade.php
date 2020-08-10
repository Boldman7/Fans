@extends('layouts.app')

<!-- Main Content -->
@section('content')

<div class="login-block">
    <div class="panel panel-default">
        <div class="panel-body nopadding">
            <div class="login-head">{{ trans('common.reset_password') }}
                <!--<div class="header-circle"><i class="fa fa-paper-plane" aria-hidden="true"></i></div>-->
                <div class="header-circle login-progress hidden"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div>
            </div>
            <div class="login-bottom">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    {!! csrf_field() !!}

                    <fieldset class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        {{ Form::label('email', trans('common.email_address')) }}
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('common.enter_mail') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </fieldset>
                    <fieldset class="form-group">
                        {{ Form::button( trans('common.send_password_reset_link') , ['type' => 'submit','class' => 'btn btn-success btn-submit']) }}
                    </fieldset>
                </form> 
            </div><!-- /login-bottom -->
        </div>
    </div><!-- /panel -->
</div>
        <script type="text/javascript">
        function SP_source() {
          return "<?php echo e(url('/')); ?>/";
        }
        </script>
@endsection
