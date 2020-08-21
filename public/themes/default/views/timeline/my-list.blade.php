<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">

                <div class="col-12">
					<div class="timeline-posts">

						<div class="panel panel-default">
							<div class="panel-heading no-bg panel-settings bottom-border">
								<h3 class="panel-title">

									<a href="{{url('/mylists')}}" class="btn-back">
										<svg class="g-icon" aria-hidden="true">
											<use xlink:href="#icon-back" href="#icon-back">
												<svg id="icon-back" viewBox="0 0 24 24"> <path d="M19 11H7.41l5.3-5.29A1 1 0 0 0 13 5a1 1 0 0 0-1-1 1 1 0 0 0-.71.29L3.59 12l7.7 7.71A1 1 0 0 0 12 20a1 1 0 0 0 1-1 1 1 0 0 0-.29-.71L7.41 13H19a1 1 0 0 0 0-2z"></path> </svg>
											</use>
										</svg>
									</a>

									{{ $list_type_name }}
								</h3>
							</div>


							<div class="panel-body timeline">

								@if ($list_type_id == 'followers')
									<div class="tab">
										<button class="tablinks active" onclick="openCity(event, 'All')">
											<svg class="g-icon" aria-hidden="true" style="">
												<use xlink:href="#icon-all" href="#icon-all">
													<svg id="icon-all" viewBox="0 0 24 24"> <path d="M15 6H5a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1zm3-17H8a1 1 0 0 0 0 2h11a1 1 0 0 1 1 1v11a1 1 0 0 0 2 0V5a3 3 0 0 0-3-3zm-6 9a1 1 0 0 0-.71.29L9 14.59l-1.29-1.3A1 1 0 0 0 7 13a1 1 0 0 0-1 1 1 1 0 0 0 .29.71l2 2a1 1 0 0 0 1.42 0l4-4A1 1 0 0 0 14 12a1 1 0 0 0-1-1z"></path> </svg>
												</use>
											</svg> ALL
										</button>
										<button class="tablinks" onclick="openCity(event, 'Active')">
											<svg class="g-icon" aria-hidden="true" style="">
												<use xlink:href="#icon-active" href="#icon-active">
													<svg id="icon-active" viewBox="0 0 24 24"> <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8zm4.42-11.42a1 1 0 0 0-.71.3l-5.21 5.21-2.21-2.21a1 1 0 0 0-.71-.3 1 1 0 0 0-1 1 1 1 0 0 0 .3.71l3.62 3.62 6.62-6.62a1 1 0 0 0 .3-.71 1 1 0 0 0-1-1z"></path> </svg>
												</use>
											</svg> ACTIVE
										</button>
										<button class="tablinks" onclick="openCity(event, 'Expired')">
											<svg class="g-icon" aria-hidden="true" style="">
												<use xlink:href="#icon-expired" href="#icon-expired">
													<svg id="icon-expired" viewBox="0 0 24 24"> <path d="M22.56 18.34A2.63 2.63 0 0 0 22.2 17L14.3 3.33a2.65 2.65 0 0 0-4.6 0L1.8 17a2.63 2.63 0 0 0-.36 1.33A2.66 2.66 0 0 0 4.1 21h15.8a2.66 2.66 0 0 0 2.66-2.66zm-2 0a.66.66 0 0 1-.66.66H4.1a.66.66 0 0 1-.66-.66.63.63 0 0 1 .09-.34l7.9-13.68a.68.68 0 0 1 1.14 0L20.47 18a.63.63 0 0 1 .09.34zM12 13.5a1 1 0 0 0 1-1v-4a1 1 0 0 0-2 0v4a1 1 0 0 0 1 1zm0 1.1a1.4 1.4 0 1 0 1.4 1.4 1.4 1.4 0 0 0-1.4-1.4z"></path> </svg>
												</use>
											</svg> EXPIRED
										</button>
									</div>

									<div id="All" class="tabcontent">
										{!! Theme::partial('my-list',compact('saved_users')) !!}
									</div>
									<div id="Active" class="tabcontent">
										{!! Theme::partial('my-list',compact('saved_users')) !!}
									</div>
									<div id="Expired" class="tabcontent">
										<div class="text-center">
											{{ trans('common.no_users_found') }}
										</div>
									</div>
								@else
									{!! Theme::partial('my-list',compact('saved_users')) !!}
								@endif
							</div>
						</div>
					</div>
				</div>
			</div><!-- /col-md-6 --></div>
		</div>
	<!-- </div> -->
<!-- /main-section -->

<script>

	$("#All").show();

	function openCity(evt, cityName) {
		// Declare all variables
		var i, tabcontent, tablinks;

		// Get all elements with class="tabcontent" and hide them
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}

		// Get all elements with class="tablinks" and remove the class "active"
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}

		// Show the current tab, and add an "active" class to the button that opened the tab
		document.getElementById(cityName).style.display = "block";
		evt.currentTarget.className += " active";
	}
</script>