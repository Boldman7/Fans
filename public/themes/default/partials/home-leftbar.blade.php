{{--<div class="widget-events widget-left-panel">--}}
{{--	<div class="menu-list">--}}
{{--		<ul class="list-unstyled">--}}
{{--			<li class="{!! (Request::segment(1)=='' ? 'active' : '') !!}"><a href="{{ url('/') }}" class="btn menu-btn"><i class="fa fa-trophy" aria-hidden="true"></i>{{ trans('common.home') }}</a></li>--}}

{{--			@if(Setting::get('enable_browse') == 'on')--}}
{{--				<li class="{!! (Request::segment(1)=='browse' ? 'active' : '') !!}"><a href="{{ url('/browse') }}" class="btn menu-btn"><i class="fa fa-globe" aria-hidden="true"></i>{{ trans('common.browse') }} </a></li>--}}
{{--			@endif--}}

{{--			<li><a href="{{ url(Auth::user()->username.'/saved') }}" class="btn menu-btn"><i class="fa fa-save" aria-hidden="true"></i>{{ trans('common.saved_items') }} </a></li>			--}}

{{--			<li><a href="{{ url('/'.Auth::user()->username.'/settings/general') }}" class="btn menu-btn"><i class="fa fa-cog" aria-hidden="true"></i>{{ trans('common.settings') }}</a></li>   --}}
{{--		</ul>--}}
{{--	</div>--}}
{{--</div><!-- /widget-events -->--}}