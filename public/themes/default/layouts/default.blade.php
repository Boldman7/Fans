<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{!! csrf_token() !!}"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />

        <meta name="keywords" content="{{ Setting::get('meta_keywords') }}">
        <meta name="description" content="{{ Setting::get('meta_description') }}">
        <link rel="icon" type="image/x-icon" href="{{asset('images/favicon.ico')}}">

        <meta content="{{ url('/') }}" property="og:url" />
        <meta content="{!! url('setting/'.Setting::get('logo')) !!}" property="og:image" />
        <meta content="{{ Setting::get('meta_description') }}" property="og:description" />
        <meta content="{{ Setting::get('site_name') }}" property="og:title" />
        <meta content="website" property="og:type" />
        <meta content="{{ Setting::get('site_name') }}" property="og:site_name" />


        <title>Fans Platform</title>
        <title>{{ Setting::get('site_title') }}</title>

        <link href="{{ Theme::asset()->url('css/flag-icon.css') }}" rel="stylesheet">
        <link href="{{ Theme::asset()->url('css/custom.css') }}" rel="stylesheet">
        <link href="{{ url('css/extra.css') }}" rel="stylesheet">
        <!--videojs-->
        <link href="//vjs.zencdn.net/7.8.2/video-js.min.css" rel="stylesheet">

        {!! Theme::asset()->styles() !!}

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
        function SP_source() {
          return "{{ url('/') }}/";
        }
        var base_url = "{{ url('/') }}/";
        var theme_url = "{!! Theme::asset()->url('') !!}";
        var current_username = "{{ Auth::user()->username }}";
        </script>
        {!! Theme::asset()->scripts() !!}
        @if(Setting::get('google_analytics') != NULL)
            {!! Setting::get('google_analytics') !!}
        @endif
        <script src="{!! Theme::asset()->url('js/lightgallery.js') !!}"></script>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet"> 
    </head>
    <body @if(Setting::get('enable_rtl') == 'on') class="direction-rtl" @endif>
        {!! Theme::partial('header') !!}

        <div class="main-content">
            {!! Theme::content() !!}
        </div>

        {!! Theme::partial('right-sidebar') !!}

        {!! Theme::partial('footer') !!}

        <script>
          @if(Config::get('app.debug'))
            // Pusher.logToConsole = true;
          @endif
            var pusherConfig = {
                token: "{{ csrf_token() }}",
                PUSHER_KEY: "{{ config('broadcasting.connections.pusher.key') }}"
            };
       </script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.0/socket.io.min.js"></script>
        <script type="text/javascript" src="https://js.stripe.com/v3/"></script>

        {!! Theme::asset()->container('footer')->scripts() !!}

    </body>
</html>
