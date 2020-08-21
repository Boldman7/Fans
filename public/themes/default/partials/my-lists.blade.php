<div class="panel panel-default">
  <div class="panel-heading no-bg panel-settings bottom-border">
    <h3 class="panel-title">
      {{ trans('common.lists') }}
    </h3>
  </div>

    <div class="lists-dropdown-menu">
        <ul class="list-inline text-right no-margin">
            <li class="dropdown">
                <a href="#" class="dropdown-togle lists-dropdown-icon" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <svg class="sort-icon has-tooltip" aria-hidden="true" data-original-title="null">
                        <use xlink:href="#icon-sort" href="#icon-sort">
                            <svg id="icon-sort" viewBox="0 0 24 24"> <path d="M4 19h4a1 1 0 0 0 1-1 1 1 0 0 0-1-1H4a1 1 0 0 0-1 1 1 1 0 0 0 1 1zM3 6a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1 1 1 0 0 0-1-1H4a1 1 0 0 0-1 1zm1 7h10a1 1 0 0 0 1-1 1 1 0 0 0-1-1H4a1 1 0 0 0-1 1 1 1 0 0 0 1 1z"></path> </svg>
                        </use>
                    </svg>
                </a>
                <ul class="dropdown-menu profile-dropdown-menu-content">
                    <li class="main-link">

                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="sort" id="sortByName" value="name" checked>
                            <label class="red-list-label" for="sortByName">
                               Name
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="sort" id="soryByRecent" value="recent">
                            <label class="red-list-label" for="soryByRecent">
                                Recent
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="sort" id="soryByPeople" value="people">
                            <label class="red-list-label" for="soryByPeople">
                                People
                            </label>
                        </div>
                    </li>
                    <div class="divider">

                    </div>
                    <li class="main-link">

                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="order" id="orderByASC" value="asc" checked>
                            <label class="red-list-label" for="orderByASC">
                                Ascending
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="order" id="orderByDESC" value="desc">
                            <label class="red-list-label" for="orderByDESC">
                                Descending
                            </label>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="panel-body timeline my-lists">
        @if (!empty($user_lists))
            @foreach ($user_lists as $user_list)
                <a href="{{ url('mylist').'/'.$user_list['id'] }}">
                    <div class="modal-mylist-item">
                        <span class="red-mylist-label">{{$user_list['name']}}</span>
                        <span class="red-mylist-count-label">{{$user_list['count']}}</span>
                    </div>
                </a>
            @endforeach
        @endif

    </div>
</div>
