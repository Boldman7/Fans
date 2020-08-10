<!-- <div class="main-content"> -->
<div class="container">
	<div class="row">
		<!-- <div class="visible-lg col-lg-2">
			<?php echo Theme::partial('home-leftbar',compact('trending_tags')); ?>

		</div> -->
		<div class="col-md-7 col-lg-8">

			<div class="panel panel-default">
				<div class="panel-heading no-bg panel-settings">

					<h3 class="panel-title">
						<?php echo e(trans('common.saved_items')); ?>

					</h3>
				</div>
				<div class="panel-body nopadding">
					<ul class="nav nav-pills heading-list">
						<li class="active"><a href="#posts" data-toggle="pill" class="text"><?php echo e(trans('common.posts')); ?><span></span></a></li>
						<!-- <li class="divider">&nbsp;</li> -->
					</ul>
				</div>
				<div class="tab-content nopadding" style="margin-top:15px;">

					<!--Start Posts tab-->
					<div id="posts" class="tab-pane fade active in">
						<ul class="list-group page-likes">
							<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php if(count($posts) > 0): ?>
								<?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				                    <?php echo Theme::partial('post',compact('post','timeline','next_page_url','user')); ?>

				                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php else: ?>
				                <div class="alert alert-warning tmargin-10"><?php echo e(trans('messages.no_saved_posts')); ?></div>
				            <?php endif; ?>       
						</ul>
					</div>
					<!-- End of posts tab-->

					<!-- Start Pages tab-->
					<div id="pages" class="tab-pane fade">
						<ul class="list-group page-likes">
							<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php if(count($page_timelines) > 0): ?>
								<?php $__currentLoopData = $page_timelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				                    <li class="list-group-item holder">
										<div class="connect-list">
											<div class="connect-link side-left">
					                            <a href="<?php echo e(url($timeline->username)); ?>">
					                            	<img src=" <?php if($timeline->avatar_id): ?> <?php echo e(url('page/avatar/'.$timeline->avatar->source)); ?> <?php else: ?> <?php echo e(url('page/avatar/default-page-avatar.png')); ?> <?php endif; ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>" alt="<?php echo e($timeline->name); ?>" class="img-icon">
					                            	<?php echo e($timeline->name); ?>

					                            </a>
				                            </div>
				                            <div class="side-right follow-links">
				                            	<div class="left-col">
				                            		<div class="left-col">
				                            			<a href="#" class="btn btn-to-follow btn-default unsave-timeline follow" data-timeline-id="<?php echo e($timeline->id); ?>"><i class="fa fa-save"></i> <?php echo e(trans('common.unsave')); ?> </a>
				                            		</div>
				                            	</div>
				                            </div>
				                            <div class="clearfix"></div>
				                        </div>
				                    </li>
				                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php else: ?>
				                <div class="alert alert-warning"><?php echo e(trans('messages.no_saved_pages')); ?></div>
				            <?php endif; ?>       
						</ul>
					</div>
					<!-- End of pages tab-->

					<!-- Start Groups tab-->
					<div id="groups" class="tab-pane fade">
						<ul class="list-group page-likes">
							<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php if(count($group_timelines) > 0): ?>
								<?php $__currentLoopData = $group_timelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				                    <li class="list-group-item holder">
										<div class="connect-list">
											<div class="connect-link side-left">
					                            <a href="<?php echo e(url($timeline->username)); ?>">
					                            	<img src=" <?php if($timeline->avatar_id): ?> <?php echo e(url('page/avatar/'.$timeline->avatar->source)); ?> <?php else: ?> <?php echo e(url('page/avatar/default-page-avatar.png')); ?> <?php endif; ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>" alt="<?php echo e($timeline->name); ?>" class="img-icon">
					                            	<?php echo e($timeline->name); ?>

					                            </a>
				                            </div>
				                            <div class="side-right follow-links">
				                            	<div class="left-col">
				                            		<div class="left-col">
				                            			<a href="#" class="btn btn-to-follow btn-default unsave-timeline follow" data-timeline-id="<?php echo e($timeline->id); ?>"><i class="fa fa-save"></i> <?php echo e(trans('common.unsave')); ?> </a>
				                            		</div>
				                            	</div>
				                            </div>
				                            <div class="clearfix"></div>
				                        </div>
				                    </li>
				                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php else: ?>
				                <div class="alert alert-warning"><?php echo e(trans('messages.no_saved_groups')); ?></div>
				            <?php endif; ?>       
						</ul>
					</div>
					<!-- End of groups tab-->

					<!-- Start events tab-->
					<div id="events" class="tab-pane fade">
						<ul class="list-group page-likes">
							<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php if(count($event_timelines) > 0): ?>
								<?php $__currentLoopData = $event_timelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				                    <li class="list-group-item holder">
										<div class="connect-list">
											<div class="connect-link side-left">
					                            <a href="<?php echo e(url($timeline->username)); ?>">
					                            	<img src=" <?php if($timeline->avatar_id): ?> <?php echo e(url('page/avatar/'.$timeline->avatar->source)); ?> <?php else: ?> <?php echo e(url('page/avatar/default-page-avatar.png')); ?> <?php endif; ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>" alt="<?php echo e($timeline->name); ?>" class="img-icon">
					                            	<?php echo e($timeline->name); ?>

					                            </a>
				                            </div>
				                            <div class="side-right follow-links">
				                            	<div class="left-col">
				                            		<div class="left-col">
				                            			<a href="#" class="btn btn-to-follow btn-default unsave-timeline follow" data-timeline-id="<?php echo e($timeline->id); ?>"><i class="fa fa-save"></i> <?php echo e(trans('common.unsave')); ?> </a>
				                            		</div>
				                            	</div>
				                            </div>
				                            <div class="clearfix"></div>
				                        </div>
				                    </li>
				                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php else: ?>
				                <div class="alert alert-warning"><?php echo e(trans('messages.no_saved_posts')); ?></div>
				            <?php endif; ?>       
						</ul>
					</div>
					<!-- End of events tab-->
				</div>
			</div>
		</div>

		<div class="col-md-5 col-lg-4">
			<?php echo Theme::partial('home-rightbar',compact('suggested_users', 'suggested_groups', 'suggested_pages')); ?>

		</div>

		</div><!-- /row -->
	</div>
<!-- </div> --><!-- /main-content -->