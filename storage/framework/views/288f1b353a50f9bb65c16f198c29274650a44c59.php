<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="usersModalLabel"><?php echo e($heading); ?></h4>
</div> 
<div class="modal-body">
    <div class="user-follow fans">
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="media">
                    <div class="media-left">
                        <a href="<?php echo e(url($user->username)); ?>">
                            <img src="<?php echo e($user->avatar); ?>" class="img-icon" alt="<?php echo e($user->name); ?>" title="<?php echo e($user->name); ?>">
                        </a>
                    </div>
                    <div class="media-body socialte-timeline follow-links">
                        <h4 class="media-heading"><?php echo e($user->name); ?> <span class="text-muted"><?php echo e('@'.$user->username); ?></span></h4>
                        <?php if($user->timeline_id != Auth::user()->timeline_id): ?>
                            <?php if(!$user->followers->contains(Auth::user()->id)): ?>
                                <div class="btn-follow">
                                    <a href="#" class="btn btn-default follow-user follow" data-timeline-id="<?php echo e($user->timeline->id); ?>"> <i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?></a>
                                </div>
                                <div class="btn-follow hidden">
                                    <a href="#" class="btn btn-success follow-user unfollow" data-timeline-id="<?php echo e($user->timeline->id); ?>"><i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?></a>
                                </div>
                            <?php else: ?>                            
                                <div class="btn-follow hidden">
                                    <a href="#" class="btn btn-default follow-user follow" data-timeline-id="<?php echo e($user->timeline->id); ?>"> <i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?></a>
                                </div>
                                <div class="btn-follow">
                                    <a href="#" class="btn btn-success follow-user unfollow" data-timeline-id="<?php echo e($user->timeline->id); ?>"><i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?></a>
                                </div>    
                            <?php endif; ?>
                        <?php endif; ?>    
                    </div>
                </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
</div>

