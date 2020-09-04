
<div class="user-follow fans row">
    <!-- Each user is represented with media block -->

    @if($saved_users != "")

        @foreach($saved_users as $suggested_user)

            <div class="col-md-6 col-lg-4">
                <div class="media user-list-item">
                    <div class="media-left badge-verification">
                        <a href="{{ url($suggested_user->username) }}">
                            <img src="{{ $suggested_user->avatar }}" class="img-icon" alt="{{ $suggested_user->name }}" title="{{ $suggested_user->name }}">
                            @if($suggested_user->verified)
                                <span class="verified-badge bg-success verified-medium">
                            <i class="fa fa-check"></i>
                        </span>
                            @endif
                        </a>
                    </div>
                    <div class="media-body socialte-timeline follow-links">
                        <h4 class="media-heading"><a href="{{ url($suggested_user->username) }}">{{ $suggested_user->name }} </a>
                            <span class="text-muted">{{ '@'.$suggested_user->username }}</span>
                        </h4>
                        {{--						@if($suggested_user->payment != NULL && $suggested_user->payment->is_active == 1 && $suggested_user->payment->price > 0)--}}
                        @if($suggested_user->price >= 0)
                            @if(!$suggested_user->followers->contains(Auth::user()->id))
                                <div class="btn-follow">
                                    <a href="#" class="btn btn-default follow-user follow" data-price="{{ $suggested_user->price }}" data-timeline-id="{{ $suggested_user->timeline->id }}"> <i class="fa fa-heart"></i> {{ trans('common.follow') }}</a>
                                </div>
                                <div class="btn-follow hidden">
                                    <a href="#" class="btn btn-success follow-user unfollow" data-price="{{ $suggested_user->price }}" data-timeline-id="{{ $suggested_user->timeline->id }}"><i class="fa fa-check"></i> {{ trans('common.following') }}</a>
                                </div>
                            @else
                                <div class="btn-follow hidden">
                                    <a href="#" class="btn btn-default follow-user follow" data-price="{{ $suggested_user->price }}" data-timeline-id="{{ $suggested_user->timeline->id }}"> <i class="fa fa-heart"></i> {{ trans('common.follow') }}</a>
                                </div>
                                <div class="btn-follow">
                                    <a href="#" class="btn btn-success follow-user unfollow" data-price="{{ $suggested_user->price }}" data-timeline-id="{{ $suggested_user->timeline->id }}"><i class="fa fa-check"></i> {{ trans('common.following') }}</a>
                                </div>
                            @endif

                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-warning">
            {{ trans('messages.no_suggested_users') }}
        </div>
    @endif

</div>
