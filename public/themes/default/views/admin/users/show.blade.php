<div class="panel panel-default">
	<div class="panel-heading no-bg panel-settings">
		<h3 class="panel-title">
			{{ trans('common.manage_users') }}
		</h3>
	</div>
	<div class="panel-body timeline">
	<div class="col-md-offset-9">			
		{{ Form::label('sort by', 'Sort by:') }}
		{!! Form::select('manage_users', array('name_asc' => trans('admin.name_asc'), 'name_desc' => trans('admin.name_desc'), 'created_asc' => trans('admin.created_asc'), 'created_desc' => trans('admin.created_desc')), Request::get('sort'), ['class' => 'form-control usersort']) !!}	
	</div>
	@include('flash::message')
{{--		@if(count($timelines) > 0)--}}
		@if(count($users) > 0)
			<div class="table-responsive manage-table">
				<table class="table existing-products-table fans">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>{{ trans('admin.id') }}</th>
							<th>{{ trans('auth.name') }}</th>
							<th>{{ trans('common.email') }}</th>
							<th>{{ trans('admin.options') }}</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					@foreach($users as $user)
						<tr>
							<td>&nbsp;</td>
							<td>{{ $user->id }}</td>
							<td><a href="#"><img src=" @if($user->timeline->avatar_id != null) {{ url('user/avatar/'.$user->timeline->avatar->source) }} @else {{ url('user/avatar/default-'.$user->gender.'-avatar.png') }} @endif" alt="images"></a><a href="{{ url($user->timeline->username) }}"> {{ $user->timeline->name }}</a></td>
							<td>{{ $user->email }}</td>
							<td>
								<ul class="list-inline">
									<li><a href="{{ url('admin/users/'.$user->timeline->username.'/edit') }}"><span class="pencil-icon bg-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a></li>
									<li><a href="{{ url('admin/users/'.$user->timeline->username.'/delete')}}" onclick="return confirm('{{ trans("messages.are_you_sure") }}')"><span class="trash-icon bg-danger"><i class="fa fa-trash" aria-hidden="true"></i></span></a></li>
								</ul>
							</td>
							<td>&nbsp;</td>
						</tr>
					@endforeach
{{--						@foreach($timelines as $timeline)--}}
{{--						<tr>--}}
{{--							<td>&nbsp;</td>--}}
{{--							<td>{{ $timeline->user()->id }}</td>--}}
{{--							<td><a href="#"><img src=" @if($timeline->avatar_id != null) {{ url('user/avatar/'.$timeline->avatar->source) }} @else {{ url('user/avatar/default-'.$timeline->user->gender.'-avatar.png') }} @endif" alt="images"></a><a href="{{ url($timeline->username) }}"> {{ $timeline->name }}</a></td>--}}
{{--							<td>{{ $timeline->user()->email }}</td>--}}
{{--							<td>--}}
{{--								<ul class="list-inline">--}}
{{--									<li><a href="{{ url('admin/users/'.$timeline->username.'/edit') }}"><span class="pencil-icon bg-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a></li>--}}
{{--									<li><a href="{{ url('admin/users/'.$timeline->username.'/delete')}}" onclick="return confirm('{{ trans("messages.are_you_sure") }}')"><span class="trash-icon bg-danger"><i class="fa fa-trash" aria-hidden="true"></i></span></a></li>--}}
{{--								</ul>--}}

{{--							</td>--}}
{{--							<td>&nbsp;</td>--}}
{{--						</tr>--}}
{{--						@endforeach--}}
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
