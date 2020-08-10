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
					<h3 class="panel-title">
						{{ trans('common.login_session') }}
					</h3>
				</div>
	<div class="panel-body timeline">
{{--		<div class="col-md-offset-9">--}}
{{--			{{ Form::label('sort by', 'Sort by:') }}--}}
{{--			{!! Form::select('manage_users', array('name_asc' => trans('admin.name_asc'), 'name_desc' => trans('admin.name_desc'), 'created_asc' => trans('admin.created_asc'), 'created_desc' => trans('admin.created_desc')), Request::get('sort'), ['class' => 'form-control usersort']) !!}--}}
{{--		</div>--}}
		@include('flash::message')
		{{--		@if(count($timelines) > 0)--}}
		@if(count($users) > 0)
			<div class="table-responsive manage-table">
				<table class="table existing-products-table fans">
					<thead>
					<tr>
						<th>&nbsp;</th>
						<th>{{ trans('admin.id') }}</th>
						<th>{{ trans('common.name') }}</th>
						<th>{{ trans('common.browser') }}</th>
						<th>{{ trans('common.os') }}</th>
						<th>{{ trans('common.machine_name') }}</th>
						<!--<th>Location</th>-->
						<th>{{ trans('common.date') }}</th>
						<th>&nbsp;</th>
					</tr>
					</thead>
					<tbody>
					@foreach($users as $user)
						<tr>
							<td>&nbsp;</td>
							<td>{{ $user->id }}</td>
							<td>{{ $user->user_name}}</td>
							<td>{{ $user->browser }}</td>
							<td>{{ $user->os }}</td>
							<td>{{ $user->machine_name }}</td>
							<!--<td>{{ $user->location }}</td>-->
							<td>{{ $user->created_at }}+00:00</td>
							<td>&nbsp;</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="pagination-holder userpage">
				{{ $users->render() }}
			</div>
		@else
			<div class="alert alert-warning">{{ trans('messages.no_users') }}</div>
		@endif
	</div>
</div>
	</div>
</div>
