  <?php 
  $display_comment ="";           
  $user_setting =""; 
  $user_follower = $post->chkUserFollower(Auth::user()->id,$post->user_id);
  $user_setting = $post->chkUserSettings($post->user_id);
  

  if($user_follower != NULL)
  {
    if($user_follower == "only_follow") {
      $display_comment = "only_follow";
    }elseif ($user_follower == "everyone") {
      $display_comment = "everyone"; 
    }
  }
  else{
    if($user_setting){
      if($user_setting == "everyone"){
        $user_setting = "everyone";
      }            
    }
  }

  ?>
  <ul class="list-unstyled main-comment comment<?php echo e($comment->id); ?> <?php if($comment->replies()->count() > 0): ?> has-replies <?php endif; ?>" id="comment<?php echo e($comment->id); ?>">
    <li> 
      <div class="comments delete_comment_list"> <!-- main-comment -->
        <div class="commenter-avatar">
          <a href="#"><img src="<?php echo e($comment->user->avatar); ?>" title="<?php echo e($comment->user->name); ?>" alt="<?php echo e($comment->user->name); ?>"></a>
        </div>
        <div class="comments-list">
          <div class="commenter">
            <?php if($comment->user_id == Auth::user()->id): ?>
            <a href="#" class="delete-comment delete_comment" data-commentdelete-id="<?php echo e($comment->id); ?>"><i class="fa fa-times"></i></a>
            <?php endif; ?>
            <div class="commenter-name">
              <a href="<?php echo e(url($comment->user->username)); ?>"><?php echo e($comment->user->name); ?></a>

              <?php 
              $links = preg_match_all("/(?i)\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/", $comment->description, $matches);
              
              $main_description = $comment->description;
              ?>
              <?php $__currentLoopData = $matches[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $main_description = str_replace($link, '<a href="'.$link.'">'.$link.'</a>', $main_description); ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              <span class="comment-description"><?php echo $main_description; ?></span>
            </div>
            <ul class="list-inline comment-options">

              <?php if(!$comment->comments_liked->contains(Auth::user()->id)): ?>
              <li><a href="#" class="text-capitalize like-comment like" data-comment-id="<?php echo e($comment->id); ?>"><?php echo e(trans('common.like')); ?></a></li>
              <li class="hidden"><a href="#" class="text-capitalize like-comment unlike" data-comment-id="<?php echo e($comment->id); ?>"><?php echo e(trans('common.unlike')); ?></a></li>
              <?php else: ?>
              <li class="hidden"><a href="#" class="text-capitalize like-comment like" data-comment-id="<?php echo e($comment->id); ?>"><?php echo e(trans('common.like')); ?></a></li>
              <li><a href="#" class="text-capitalize like-comment unlike" data-comment-id="<?php echo e($comment->id); ?>"><?php echo e(trans('common.unlike')); ?></a></li>
              <?php endif; ?>
              <li>.</li>
              <li><a href="#" class="show-comment-reply"><?php echo e(trans('common.reply')); ?></a></li>    
              <li>.</li>
              <?php if($comment->comments_liked->count() != null): ?>
              <li><a href="#" class="show-likes like3-<?php echo e($comment->id); ?>"><i class="fa fa-thumbs-up"></i><?php echo e($comment->comments_liked->count()); ?></a></li>
              <li class="show-likes like4-<?php echo e($comment->id); ?> hidden"></li>
              <?php else: ?>
              <li><a href="#" class="show-likes like3-<?php echo e($comment->id); ?>"><i class="fa fa-thumbs-up"></i><?php echo e($comment->comments_liked->count()); ?></a></li>
              <li class="show-likes like4-<?php echo e($comment->id); ?> hidden"></li>
              <?php endif; ?>
              <li>.</li>
              <li>
                <time class="post-time timeago" datetime="<?php echo e($comment->created_at); ?>+00:00" title="<?php echo e($comment->created_at); ?>+00:00"><?php echo e($comment->created_at); ?>+00:00</time>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <li>
       <?php if($display_comment == "only_follow" || $display_comment == "everyone" || $user_setting == "everyone" || $post->user_id == Auth::user()->id): ?>
       <div class="to-comment comment-reply" style="display:none" >  <!-- to-comment -->
        <div class="commenter-avatar">
          <img src="<?php echo e(Auth::user()->avatar); ?>" alt="<?php echo e(Auth::user()->name); ?>" title="<?php echo e(Auth::user()->name); ?>">
        </div>
        <div class="comment-textfield">
          <form action="#" class="comment-form">
            <input class="form-control post-comment" autocomplete="off" data-post-id="<?php echo e($post->id); ?>" data-comment-id="<?php echo e($comment->id); ?>" name="post_comment" placeholder="<?php echo e(trans('messages.comment_placeholder')); ?>" rows="1">
          </form>
        </div>
        <div class="clearfix"></div>
      </div><!-- to-comment -->
      <?php endif; ?> 
    </li>
    
    <?php if($comment->replies()->count() > 0): ?>
    <li>
      <a href="#" class="show-comment-replies replies-count"><i class="fa fa-reply"></i><?php echo e($comment->replies()->count()); ?> <?php echo e(trans('common.replies')); ?></a>
      <div class="comment-replies" style="display:none">
        <ul class="list-unstyled comment-replys"> <!-- comment-replys-list/sub-comment-list -->
          <?php if($comment->replies()->count() > 0 ): ?>
          <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php echo Theme::partial('reply',compact('reply','post')); ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </ul>
      </div>
    </li>
    <?php endif; ?>

  </ul>
</li><!-- replys/sub-comment -->