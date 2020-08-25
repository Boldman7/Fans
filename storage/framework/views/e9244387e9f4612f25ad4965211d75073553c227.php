 <div class="panel panel-default panel-post animated" id="post<?php echo e($post->id); ?>">
  <div class="panel-heading no-bg">
    <div class="post-author">
      <div class="user-avatar">
        <a href="<?php echo e(url($post->user->username)); ?>"><img src="<?php echo e($post->user->avatar); ?>" alt="<?php echo e($post->user->name); ?>" title="<?php echo e($post->user->name); ?>"></a>
      </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="text-wrapper">

            <div class="post-image-holder post-locked  single-image">
                <a><img src="<?php echo e(url('user/gallery/locked.png')); ?>"  title="<?php echo e($post->user->name); ?>" alt="<?php echo e($post->user->name); ?>"></a>

                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Subscribe <?php echo e($post->user->name); ?>'s posts</h4>
                            </div>
                            <div class="modal-body">
                                    <img src="<?php echo e(url('user/gallery/locked.png')); ?>"  title="<?php echo e($post->user->name); ?>" alt="<?php echo e($post->user->name); ?>" style="display: block; margin-left: auto; margin-right: auto">
                                    <p  style="margin-left: auto; margin-right: auto">Monthly Subscribe <?php echo e($post->user->price); ?> US$</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Subscribe</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

    </div>

  </div>
 </div>