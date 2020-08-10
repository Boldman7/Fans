<div class="list-group list-group-navigation fans-group">
    <a href="{{ url('/admin/general-settings') }}" class="list-group-item">

        <div class="list-icon fans-icon {{ Request::segment(2) == 'general-settings' ? 'active' : '' }}">
            <i class="fa fa-shield"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.website_settings') }}
            <div class="text-muted">
                {{ trans('common.general_website_settings') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>
    <a href="{{ url('/admin/user-settings') }}" class="list-group-item">

        <div class="list-icon fans-icon {{ Request::segment(2) == 'user-settings' ? 'active' : '' }}">
            <i class="fa fa-user-secret"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.user_settings') }}
            <div class="text-muted">
                {{ trans('common.user_settings_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>
    <a href="{{ url('/admin') }}" class="list-group-item">

        <div class="list-icon fans-icon {{ (Request::segment(1) == 'admin' && Request::segment(2)==null) ? 'active' : '' }}">
            <i class="fa fa-dashboard"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.dashboard') }}
            <div class="text-muted">
                {{ trans('common.application_statistics') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>
    <a href="{{ url('/admin/wallpapers') }}" class="list-group-item">
  
        <div class="list-icon fans-icon {{ Request::segment(2) == 'wallpapers' ? 'active' : '' }}">
            <i class="fa fa-picture-o"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.wallpapers') }}
            <div class="text-muted">
                {{ trans('common.wallpapers_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>

    <a href="{{ url('/admin/users') }}" class="list-group-item">

        <div class="list-icon fans-icon {{ Request::segment(2) == 'users' ? 'active' : '' }}">
            <i class="fa fa-user-plus"></i>
        </div>
        <div class="list-text">
            <span class="badge pull-right"></span>
            {{ trans('common.manage_users') }}
            <div class="text-muted">
                {{ trans('common.manage_users_text') }}
            </div>
        </div>
        <span class="clearfix"></span>
    </a>

    <a href="{{ url('/admin/manage-reports') }}" class="list-group-item">

        <div class="list-icon fans-icon {{ Request::segment(2) == 'manage-reports' ? 'active' : '' }}">
            <i class="fa fa-bug"></i>
        </div>
        
        <div class="list-text">
            @if(Auth::user()->getReportsCount() > 0)
            <span class="badge pull-right">{{ Auth::user()->getReportsCount() }}</span>
            @endif            
            {{ trans('common.manage_reports') }}
            <div class="text-muted">
                {{ trans('common.manage_reports_text') }}
            </div>             
        </div>
        <span class="clearfix"></span>
    </a>
</div>



