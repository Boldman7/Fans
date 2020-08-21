<!-- main-section -->
<div class="container">
	<div class="row">
		<div class="col-md-12">
			{!! Theme::partial('user-header',compact('timeline', 'liked_post', 'liked_pages','user','joined_groups','followRequests','following_count',
			'followers_count','follow_confirm','user_post','joined_groups_count','guest_events', 'user_lists')) !!}

			<div class="row">
				<div class=" timeline">

					<div class="col-md-4">
						
						{!! Theme::partial('user-leftbar',compact('timeline','user','follow_user_status','own_groups','own_pages','user_events')) !!}
					</div>
					<div class="col-md-8">
						@if($timeline->type == "user" && $timeline_post == true && $user->id == Auth::user()->id)
							{!! Theme::partial('create-post',compact('timeline','user_post')) !!}
						@endif

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
													<input class="red-checkbox" type="radio" name="post-sort" id="sortByLatest" value="latest" checked>
													<label class="red-list-label" for="sortByLatest">
														Latest Posts
													</label>
												</div>
												<div class="form-check">
													<input class="red-checkbox" type="radio" name="post-sort" id="soryByLiked" value="liked">
													<label class="red-list-label" for="soryByLiked">
														Most liked
													</label>
												</div>
											</li>
											<div class="divider">

											</div>
											<li class="main-link">

												<div class="form-check">
													<input class="red-checkbox" type="radio" name="post-order" id="orderByASC" value="asc" checked>
													<label class="red-list-label" for="orderByASC">
														Ascending
													</label>
												</div>
												<div class="form-check">
													<input class="red-checkbox" type="radio" name="post-order" id="orderByDESC" value="desc">
													<label class="red-list-label" for="orderByDESC">
														Descending
													</label>
												</div>
											</li>
										</ul>
									</li>
								</ul>
							</div>

						<div class="timeline-posts">
							@if($posts->count() > 0)
								@foreach($posts as $post)
									{!! Theme::partial('post',compact('post','timeline','next_page_url', 'user')) !!}
								@endforeach
							@else
								<p class="no-posts">{{ trans('messages.no_posts') }}</p>
							@endif
						</div>
					</div><!-- /col-md-8 -->
				</div><!-- /main-content -->
			</div><!-- /row -->
		</div><!-- /col-md-10 -->

{{--		<div class="col-md-2">--}}
{{--			{!! Theme::partial('timeline-rightbar') !!}--}}
{{--		</div>--}}

	</div>
</div><!-- /container -->
