@extends('vendor.installer.layouts.master')

@section('title', trans('messages.requirements.title'))
@section('container')

@foreach($requirements['requirements'] as $type => $requirement)
<h3 align="center">{{ ucfirst($type) }}</h3>
<ul class="list-group">
    @foreach($requirements['requirements'][$type] as $extention => $enabled)
    <li class="list-group-item">
        {{ $extention }}
        <div class="pull-right">
            @if($enabled) 
                <i class="fa success fa-check-circle-o"></i>
            @else 
                <i class="fa error fa-times-circle-o"></i>
            @endif
        </div>
    </li>
    @endforeach
</ul>
@endforeach

@if(!isset($requirements['errors']))
    <div class="btn-installer">
        <a class="btn btn-primary" href="{{ route('LaravelInstaller::permissions') }}">
            {{ trans('messages.next') }}
        </a>
    </div>
@endif

@stop


