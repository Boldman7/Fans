<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf_token" content="{!! csrf_token() !!}"/>
        
    <link href="{{ url('/').mix('themes/default/assets/css/style.css', '') }}" rel="stylesheet"/>
    <link href="{{ url('/').mix('themes/default/assets/css/flag-icon.css', '') }}" rel="stylesheet"/>
    
        <title>Fans Platform</title>


</head>
<body id="app-layout">
    <nav class="navbar fans navbar-default no-bg" style="position: relative;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-4" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
                            <a class="navbar-brand fans" href="{{ url('/') }}" style="padding-top:20px;">
                                {{ Setting::get('site_name') }}
                                {{--<img class="fans-logo" src="{{ asset('images/logo.png') }}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}">--}}
                            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-4">


            @if (Auth::guest())
            <ul class="nav navbar-nav navbar-right">
                <li class="logout">
                    <a href="{{ url('/register') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> {{ trans('common.join') }}</a>
                </li>
                <li class="logout">
                    <a href="{{ url('/login') }}"><i class="fa fa-unlock" aria-hidden="true"></i> {{ trans('common.signin') }}</a>
                </li>
                @if (Config::get('app.env') == 'demo')
                    <li class="logout">
                        <a href="http://fans-rtl.laravelguru.com" target="_blank">{{ trans('common.rtl_version') }}</a>
                    </li>
                @endif
            </ul>
            @else
            <ul class="nav navbar-nav navbar-right" id="navbar-right">
                    <li class="dropdown user-image fans">
                        <a href="{{ url(Auth::user()->username) }}" class="dropdown-toggle no-padding" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="img-radius img-30" title="{{ Auth::user()->name }}">

                            <span class="user-name">{{ Auth::user()->name }}</span><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->hasRole('admin'))
                                <li class="{{ Request::segment(1) == 'admin' ? 'active' : '' }}"><a href="{{ url('admin') }}"><i class="fa fa-user-secret" aria-hidden="true"></i>{{ trans('common.admin') }}</a></li>
                                @endif
                                <li class="{{ (Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : '' }}"><a href="{{ url(Auth::user()->username) }}"><i class="fa fa-user" aria-hidden="true"></i>{{ trans('common.my_profile') }}</a></li>

                                <li class="{{ Request::segment(2) == 'pages-groups' ? 'active' : '' }}"><a href="{{ url(Auth::user()->username.'/pages-groups') }}"><i class="fa fa-bars" aria-hidden="true"></i>{{ trans('common.my_pages_groups') }}</a></li>

                                <li class="{{ Request::segment(3) == 'general' ? 'active' : '' }}"><a href="{{ url('/'.Auth::user()->username.'/settings/general') }}"><i class="fa fa-cog" aria-hidden="true"></i>{{ trans('common.settings') }}</a></li>

                                <li><a href="{{ url('/logout') }}"><i class="fa fa-unlock" aria-hidden="true"></i>{{ trans('common.logout') }}</a></li>
                            </ul>
                        </li>
                   <!--  <li class="logout">
                        <a href="{{ url('/logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </li> -->
                </ul>
                @endif
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>


    @yield('content')

    <!-- Modal starts here-->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
    <div class="modal-dialog modal-likes" role="document">
        <div class="modal-content">
            <i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="footer-description">
        <!--<div class="fans-terms text-center">-->
        <!--    @if(Auth::check())-->
        <!--        <a href="{{ url(Auth::user()->username.'/create-page') }}">{{ trans('common.create_page') }}</a> --->
        <!--        <a href="{{ url(Auth::user()->username.'/create-group') }}">{{ trans('common.create_group') }}</a>-->
        <!--    @else-->
        <!--        <a href="{{ url('login') }}">{{ trans('auth.login') }}</a> --->
        <!--        <a href="{{ url('register') }}">{{ trans('auth.register') }}</a>-->
        <!--    @endif-->
        <!--    @foreach(App\StaticPage::active() as $staticpage)-->
        <!--        - <a href="{{ url('page/'.$staticpage->slug) }}">{{ $staticpage->title }}</a>-->
        <!--    @endforeach-->
        <!--    <a href="{{url('/contact')}}"> - {{ trans('common.contact') }}</a>-->
        <!--</div>-->

        <!--@if(Setting::get('footer_languages') == 'on')-->
        <!--    <div class="fans-terms text-center">-->
        <!--        {{ trans('common.available_languages') }} <span>:</span>-->
        <!--        <?php $i = 0  ?>-->
        <!--        @foreach( Config::get('app.locales') as $key => $value)-->
        <!--            {{ $value }} --->
        <!--        @endforeach-->
        <!--    </div>-->
        <!--@endif-->
        
        
        
<!-- Modal starts here-->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
    <div class="modal-dialog modal-likes" role="document">
        <div class="modal-content">
        	<i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
</div>
<div class="col-md-12">
	<div class="footer-description">
		<div class="row" style="margin-bottom: 60px; text-align:center">
			<span class="col-sm-2 col-2 col-lg-2"></span>
			<a href="{{url('/faq')}}" class="col-sm-2 col-2 col-lg-2 col-xs-12"><b>FAQ</b></a>
			<a href="{{url('support')}}" class="col-sm-2 col-2 col-lg-2 col-xs-12"><b>Support</b></a>
			<a href="{{url('terms-of-use')}}" class="col-sm-2 col-2 col-lg-2 col-xs-12"><b>Terms of Use</b></a>
			<a href="{{url('privacy-policy')}}" class="col-sm-2 col-2 col-lg-2 col-xs-12"><b>Privacy Policy</b></a>
			<span class="col-sm-2 col-2 col-lg-2"></span>
		</div>
		<div class="fans-terms text-center" >
		    Copyright &copy; 2020 Fans Platform. All rights reserved.

			<span class="dropup"  style="margin-left: 20px">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
										<span>
											<?php $key = 'en'; ?>
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
			</ul></span>
		</div>
		</div>
	</div>
</div>

    </div>
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap/bootstrap.min.js"></script>
<script src="../js/bootstrap/app.js"></script>
</body>
</html>
