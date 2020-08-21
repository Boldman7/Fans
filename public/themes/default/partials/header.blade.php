@if(Auth::guest())
    <nav class="navbar fans navbar-default no-bg guest-nav">
        <div class="container">
            @else
                <nav class="navbar fans navbar-default no-bg">
                    <div class="container-fluid">
                    @endif
                    <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-4" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand fans" href="{{ url('/') }}" style="padding-top:20px;">
                                {{ Setting::get('site_title') }}
                                {{--<img class="fans-logo" src="{{ asset('images/logo.png') }}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}">--}}
                            </a>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-4">
                            <form class="navbar-form navbar-left form-left" role="search">
                                <div class="input-group no-margin">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
					</span>
                                    <input type="text" id="navbar-search" data-url="{{ URL::to('api/v1/timelines') }}" class="form-control" placeholder="{{ trans('messages.search_placeholder') }}">
                                </div><!-- /input-group -->
                            </form>
                            <!-- Collect the nav links, forms, and other content for toggling -->

                            @if (Auth::guest())
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <form method="POST" class="login-form navbar-form navbar-right" action="{{ url('/login') }}">
                                        {{ csrf_field() }}
                                        <fieldset class="form-group mail-form {{ $errors->has('email') ? ' has-error' : '' }}">
                                            {{ Form::text('email', NULL, ['class' => 'form-control', 'id' => 'email', 'placeholder'=> trans('auth.enter_email_or_username')]) }}
                                        </fieldset>
                                        <fieldset class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            {{ Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder'=> trans('auth.password')]) }}
                                            <a href="{{ url('/password/reset') }}" class="forgot-password">Forgot your password</a>
                                        </fieldset>
                                        {{ Form::button( trans('common.signin') , ['type' => 'submit','class' => 'btn btn-success btn-submit']) }}
                                    </form>
                                </div>
                            @else
                                <ul class="nav navbar-nav navbar-right" id="navbar-right" v-cloak>
{{--                                <li class="dropdown">--}}
{{--					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">--}}
{{--					<span class="user-name">--}}
{{--						@if(Auth::user()->language != null)--}}
{{--                                    <?php $key = Auth::user()->language; ?>--}}
{{--                                @else--}}
{{--                                    <?php $key = App\Setting::get('language'); ?>--}}
{{--                                @endif--}}
{{--                                @if($key == 'gr')--}}
{{--                                    <span class="flag-icon flag-icon-gr"></span>--}}
{{--@elseif($key == 'en')--}}
{{--                                    <span class="flag-icon flag-icon-us"></span>--}}
{{--@elseif($key == 'zh')--}}
{{--                                    <span class="flag-icon flag-icon-cn"></span>--}}
{{--@else--}}
{{--                                    <span class="flag-icon flag-icon-{{ $key }}"></span>--}}
{{--						@endif--}}

{{--                                        </span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>--}}
{{--                                        <ul class="dropdown-menu">--}}
{{--@foreach( Config::get('app.locales') as $key => $value)--}}
{{--                                    <li class=""><a href="#" class="switch-language" data-language="{{ $key }}">--}}
{{--								@if($key == 'gr')--}}
{{--                                        <span class="flag-icon flag-icon-gr"></span>--}}
{{--@elseif($key == 'en')--}}
{{--                                        <span class="flag-icon flag-icon-us"></span>--}}
{{--@elseif($key == 'zh')--}}
{{--                                        <span class="flag-icon flag-icon-cn"></span>--}}
{{--@else--}}
{{--                                        <span class="flag-icon flag-icon-{{ $key }}"></span>--}}
{{--								@endif--}}

{{--                                    {{ $value }}</a></li>--}}
{{--						@endforeach--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}


                                <!-- <li class="{!! (Request::segment(1)=='' ? 'active' : '') !!}"><a href="{{ url('/') }}">Home</a></li>-->

                                @if(Setting::get('enable_browse') == 'on')
                                    <!--<li class="{!! (Request::segment(1)=='browse' ? 'active' : '') !!}"><a href="{{ url('/browse') }}" style="margin-right:30px;">Explore</a></li>-->
                                    @endif

                                    <li>

                                        <ul class="list-inline notification-list">
                                            <li class="">
                                                <a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i><span class="small-screen">Home</span></a>
                                            </li>
                                            <li class="dropdown message notification">
                                                <a href="#" data-toggle="dropdown" @click.prevent="showNotifications" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bell" aria-hidden="true">
                                                        @if(Auth::user()->notifications()->where('seen',0)->count() > 0)
                                                            <span class="count hidden">{{ Auth::user()->notifications()->where('seen',0)->count() }}</span>
                                                            <span class="count" v-if="unreadNotifications > 0" >@{{ unreadNotifications }}</span>
                                                        @endif
                                                    </i>
                                                    <span class="small-screen">{{ trans('common.notifications') }}</span>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <div class="dropdown-menu-header">
                                                        <span class="side-left">{{ trans('common.notifications') }}</span>
                                                        <a v-if="unreadNotifications > 0" class="side-right" href="#" @click.prevent="markNotificationsRead" >{{ trans('messages.mark_all_read') }}</a>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    @if(Auth::user()->notifications()->count() > 0)
                                                        <ul class="list-unstyled dropdown-messages-list scrollable" data-type="notifications">
                                                            <li class="inbox-message"  v-bind:class="[ !notification.seen ? 'active' : '' ]" v-for="notification in notifications.data">
                                                                <a href="{{ url(Auth::user()->username.'/notification/') }}/@{{ notification.id }}">
                                                                    <div class="media">
                                                                        <div class="media-left">
                                                                            <img class="media-object img-icon" v-bind:src="notification.notified_from.avatar" alt="images">
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <h4 class="media-heading">
                                                                                <span class="notification-text"> @{{ notification.description }} </span>
                                                                                <span class="message-time">
															<span class="notification-type"><i class="fa fa-user" aria-hidden="true"></i></span>
															<time class="timeago" datetime="@{{ notification.created_at }}+00:00" title="@{{ notification.created_at }}">
																@{{ notification.created_at }}
															</time>
														</span>
                                                                            </h4>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li v-if="notificationsLoading" class="dropdown-loading">
                                                                <i class="fa fa-spin fa-spinner"></i>
                                                            </li>
                                                        </ul>
                                                    @else
                                                        <div class="no-messages">
                                                            <i class="fa fa-bell-slash-o" aria-hidden="true"></i>
                                                            <p>{{ trans('messages.no_notifications') }}</p>
                                                        </div>
                                                    @endif
                                                    <div class="dropdown-menu-footer"><br>
                                                        <a href="{{ url('allnotifications') }}">{{ trans('common.see_all') }}</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="dropdown message largescreen-message">
                                                <a href="#" data-toggle="dropdown" @click="showConversations" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-comments" aria-hidden="true">
                                                        <span class="count" v-if="unreadConversations" >@{{ unreadConversations }}</span>
                                                    </i>
                                                    <span class="small-screen">{{ trans('common.messages') }}</span>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <div class="dropdown-menu-header">
                                                        <span class="side-left">{{ trans('common.messages') }}</span>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="no-messages hidden">
                                                        <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                                        <p>{{ trans('messages.no_messages') }}</p>
                                                    </div>
                                                    <ul class="list-unstyled dropdown-messages-list scrollable" data-type="messages">
                                                        <li class="inbox-message" v-for="conversation in conversations.data">
                                                            <a href="#" onclick="chatBoxes.sendMessage(@{{ conversation.user.id }})">
                                                                <div class="media">
                                                                    <div class="media-left">
                                                                        <img class="media-object img-icon" v-bind:src="conversation.user.avatar" alt="images">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h4 class="media-heading">
                                                                            <span class="message-heading">@{{ conversation.user.name }}</span>
                                                                            <span class="online-status hidden"></span>
                                                                            <time class="timeago message-time" datetime="@{{ conversation.lastMessage.created_at }}" title="@{{ conversation.lastMessage.created_at }}">
                                                                                @{{ conversation.lastMessage.created_at }}
                                                                        </h4>
                                                                        <p class="message-text">
                                                                            @{{ conversation.lastMessage.body }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li v-if="conversationsLoading" class="dropdown-loading">
                                                            <i class="fa fa-spin fa-spinner"></i>
                                                        </li>
                                                    </ul>
                                                    <div class="dropdown-menu-footer">
                                                        <a href="{{ url('messages') }}">{{ trans('common.see_all') }}</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <!--<li class="smallscreen-message">-->
                                            <!--    <a href="{{ url('messages') }}">-->
                                            <!--        <i class="fa fa-comments" aria-hidden="true">-->
                                            <!--            <span class="count" v-if="unreadConversations" >@{{ unreadConversations }}</span>-->
                                            <!--        </i>-->
                                            <!--        <span class="small-screen">{{ trans('common.messages') }}</span>-->
                                            <!--    </a>-->
                                            <!--</li>-->
                                            <!--<li class="chat-list-toggle">-->
                                            <!--    <a href="#"><i class="fa fa-users" aria-hidden="true"></i><span class="small-screen">chat-list</span></a>-->
                                            <!--</li>-->
                                        </ul>
                                    </li>

                                    {{--user image menu--}}
                                    <li class="dropdown user-image fans">
                                        <a href="{{ url(Auth::user()->username) }}" class="dropdown-toggle no-padding" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="img-radius img-30" title="{{ Auth::user()->name }}">

                                            <span class="user-name">{{ Auth::user()->name }}</span><i class="fa fa-angle-down" aria-hidden="true"></i></a>

                                        <ul class="dropdown-menu">
                                            @if(Auth::user()->hasRole('admin'))
                                                <li class="{{ Request::segment(1) == 'admin' ? 'active' : '' }}"><a href="{{ url('admin') }}"><i class="fa fa-user-secret" aria-hidden="true"></i>{{ trans('common.admin') }}</a></li>
                                            @endif

                                            <li class="{{ (Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : '' }}"><a href="{{ url(Auth::user()->username) }}"><i class="fa fa-user" aria-hidden="true"></i>{{ trans('common.my_profile') }}</a></li>

                                            <li class="{{ Request::segment(3) == 'general' ? 'active' : '' }}"><a href="{{ url('/'.Auth::user()->username.'/settings/general') }}"><i class="fa fa-cog" aria-hidden="true"></i>{{ trans('common.settings') }}</a></li>

                                            <li class=""><a href="{{ url('/mylists') }}"><i class="fa fa-list" aria-hidden="true"></i>{{ trans('common.lists') }}</a></li>

                                            <li class=""><a href="{{ url('/'.Auth::user()->username.'/saved') }}"><i class="fa fa-bookmark" aria-hidden="true"></i>{{ trans('common.saved_post') }}</a></li>

                                            <li class=""><a href="{{ url(Auth::user()->username.'/settings/addbank') }}"><i class="fa fa-university" aria-hidden="true"></i>{{ trans('common.add_bank') }}</a></li>

                                            <li class=""><a href="{{ url(Auth::user()->username.'/settings/addpayment') }}"><i class="fa fa-credit-card" aria-hidden="true"></i>{{ trans('common.add_payment') }}</a></li>

                                            @if (Auth::user()->is_bank_set)
                                                <li class=""><a href="{{ url(Auth::user()->payment->dashboard_url) }}"><i class="fa fa-credit-card" aria-hidden="true"></i>{{ trans('common.dashboard') }}</a></li>
                                            @endif

                                            <li class=""><a href="{{ url('/'.Auth::user()->username.'/settings/affliates') }}"><i class="fa fa-retweet" aria-hidden="true"></i>{{ trans('common.referrals') }}</a></li>

                                            <li class=""><a href="{{ url('/faq') }}"><i class="fa fa-question" aria-hidden="true"></i>{{ trans('common.help_faq') }}</a></li>

                                            <li class=""><a href="{{ url('/support') }}"><i class="fa fa-envelope" aria-hidden="true"></i>{{ trans('common.support') }}</a></li>

                                            <li>
                                                <form action="{{ url('/logout') }}" method="post">
                                                    {!! csrf_field() !!}
                                                    <button type="submit" class="btn-logout" style="margin-bottom: 0px;"><i class="fa fa-sign-out" aria-hidden="true"></i>{{ trans('common.logout') }}</button>
                                                </form>
                                            </li>
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


    {!! Theme::asset()->container('footer')->usePath()->add('notifications', 'js/notifications.js') !!}

