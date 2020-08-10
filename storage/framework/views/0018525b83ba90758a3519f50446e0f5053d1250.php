 <div class="panel panel-default panel-post animated" id="post<?php echo e($post->id); ?>">
  <div class="panel-heading no-bg">
    <div class="post-author">
      <div class="user-avatar">
        <a target="_blank" href="<?php echo e(url($post->user->username)); ?>"><img src="<?php echo e($post->user->avatar); ?>" alt="<?php echo e($post->user->name); ?>" title="<?php echo e($post->user->name); ?>"></a>
      </div>
      <div class="user-post-details">
        <ul class="list-unstyled no-margin">
          <li>
            <a target="_blank" href="<?php echo e(url($post->user->username)); ?>" class="user-name user"><?php echo e($post->user->name); ?></a>
            <?php if($post->users_tagged->count() > 0): ?>
              <?php echo e(trans('common.with')); ?>

              <?php $post_tags = $post->users_tagged->pluck('name')->toArray()  ?>
              <?php $post_tags_ids = $post->users_tagged->pluck('id')->toArray()  ?>
              <?php $__currentLoopData = $post->users_tagged; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($key==1): ?>
                  <?php echo e(trans('common.and')); ?>

                    <?php if(count($post_tags)==1): ?>
                      <a target="_parent" href="<?php echo e(url($user->username)); ?>"> <?php echo e($user->name); ?></a>
                    <?php else: ?>
                      <a href="#" target="_parent"> <?php echo e(count($post_tags).' '.trans('common.others')); ?></a>
                    <?php endif; ?>
                  <?php break; ?>
                <?php endif; ?>
                <a target="_blank" href="<?php echo e(url($user->username)); ?>" class="user"> <?php echo e(array_shift($post_tags)); ?> </a>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <?php endif; ?>
          </li>
          <li>
            <time class="post-time timeago" datetime="<?php echo e($post->created_at); ?>+00:00" title="<?php echo e($post->created_at); ?>+00:00">
              <?php echo e($post->created_at); ?>+00:00
            </time>
            <?php if($post->location != NULL ): ?>
              <?php echo e(trans('common.at')); ?> 
              <span class="post-place">
                <a target="_blank" href="<?php echo e(url('/get-location/'.$post->location)); ?>"><i class="fa fa-map-marker"></i> <?php echo e($post->location); ?></a>
              </span>
          </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="text-wrapper">
        <p><?php echo e($post->description); ?></p>
        <div class="post-image-holder  <?php if(count($post->images()->get()) == 1): ?> single-image <?php endif; ?>">
          <?php $__currentLoopData = $post->images()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $postImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a target="_blank" href="<?php echo e(url('/post/'.$post->id)); ?>"><img src="<?php echo e(url('user/gallery/'.$postImage->source)); ?>"  title="<?php echo e($post->user->name); ?>" alt="<?php echo e($post->user->name); ?>"></a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
      <?php if($post->youtube_video_id): ?>
      <iframe src="https://www.youtube.com/embed/<?php echo e($post->youtube_video_id); ?>" frameborder="0" allowfullscreen></iframe>
      <?php endif; ?>
      <?php if($post->soundcloud_id): ?>
      <div class="soundcloud-wrapper">
        <iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/<?php echo e($post->soundcloud_id); ?>&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
      </div>
      <?php endif; ?>
      <ul class="actions-count list-inline">
        
        <?php if($post->users_liked()->count() > 0): ?>
        <?php
        $liked_ids = $post->users_liked->pluck('id')->toArray();
        $liked_names = $post->users_liked->pluck('name')->toArray();
        ?>
        <li>
          <a target="_blank" href="<?php echo e(url('/post/'.$post->id)); ?>"><span class="count-circle"><i class="fa fa-thumbs-up"></i></span> <?php echo e($post->users_liked->count()); ?> <?php echo e(trans('common.likes')); ?></a>
        </li>
        <?php endif; ?>
        
        <?php if($post->comments->count() > 0): ?>
        <li>
          <a target="_blank" href="<?php echo e(url('/post/'.$post->id)); ?>"><span class="count-circle"><i class="fa fa-comment"></i></span><?php echo e($post->comments->count()); ?> <?php echo e(trans('common.comments')); ?></a>
        </li>
        <?php endif; ?>
        
        <?php if($post->shares->count() > 0): ?>
        <?php
        $shared_ids = $post->shares->pluck('id')->toArray();
        $shared_names = $post->shares->pluck('name')->toArray(); ?>
        <li>
          <a target="_blank" href="<?php echo e(url('/post/'.$post->id)); ?>"><span class="count-circle"><i class="fa fa-share"></i></span> <?php echo e($post->shares->count()); ?> <?php echo e(trans('common.shares')); ?></a>
        </li>
        <?php endif; ?>
        

      </ul>
    </div>
