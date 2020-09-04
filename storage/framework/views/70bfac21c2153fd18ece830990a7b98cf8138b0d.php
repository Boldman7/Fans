<?php if(isset($post->shared_post_id)): ?>
  <?php
    $sharedOwner = $post;
    $post = App\Post::where('id', $post->shared_post_id)->with('comments')->first();
  ?>
<?php endif; ?>

 <div class="panel panel-default panel-post animated" id="post<?php echo e($post->id); ?>">
  <div class="panel-heading no-bg">
    <div class="post-author">

        

    <?php if(isset($user) == false || $user->followers->contains(Auth::user()->id) || $user->id == Auth::user()->id || $user->price == 0): ?>
      <div class="post-options">
        <ul class="list-inline no-margin">
          <li class="dropdown"><a href="#" class="dropdown-togle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <?php if($post->notifications_user->contains(Auth::user()->id)): ?>
              <li class="main-link">
                <a href="#" data-post-id="<?php echo e($post->id); ?>" class="notify-user unnotify">
                  <i class="fa  fa-bell-slash" aria-hidden="true"></i><?php echo e(trans('common.stop_notifications')); ?>

                  <span class="small-text"><?php echo e(trans('messages.stop_notification_text')); ?></span>
                </a>
              </li>
              <li class="main-link hidden">
                <a href="#" data-post-id="<?php echo e($post->id); ?>" class="notify-user notify">
                  <i class="fa fa-bell" aria-hidden="true"></i><?php echo e(trans('common.get_notifications')); ?>

                  <span class="small-text"><?php echo e(trans('messages.get_notification_text')); ?></span>
                </a>
              </li>
              <?php else: ?>
              <li class="main-link hidden">
                <a href="#" data-post-id="<?php echo e($post->id); ?>" class="notify-user unnotify">
                  <i class="fa  fa-bell-slash" aria-hidden="true"></i><?php echo e(trans('common.stop_notifications')); ?>

                  <span class="small-text"><?php echo e(trans('messages.stop_notification_text')); ?></span>
                </a>
              </li>
              <li class="main-link">
                <a href="#" data-post-id="<?php echo e($post->id); ?>" class="notify-user notify">
                  <i class="fa fa-bell" aria-hidden="true"></i><?php echo e(trans('common.get_notifications')); ?>

                  <span class="small-text"><?php echo e(trans('messages.get_notification_text')); ?></span>
                </a>
              </li>
              <?php endif; ?>

              <?php if(Auth::user()->id == $post->user->id): ?>
              <li class="main-link">
                <a href="#" data-post-id="<?php echo e($post->id); ?>" class="edit-post">
                  <i class="fa fa-edit" aria-hidden="true"></i><?php echo e(trans('common.edit')); ?>

                  <span class="small-text"><?php echo e(trans('messages.edit_text')); ?></span>
                </a>
              </li>
              <?php endif; ?>

              <?php if((Auth::id() == $post->user->id) || ($post->timeline_id == Auth::user()->timeline_id)): ?>
              <li class="main-link">
                <a href="#" class="delete-post" data-post-id="<?php echo e($post->id); ?>">
                  <i class="fa fa-trash" aria-hidden="true"></i><?php echo e(trans('common.delete')); ?>

                  <span class="small-text"><?php echo e(trans('messages.delete_text')); ?></span>
                </a>
              </li>
              <?php endif; ?>

              <?php if(Auth::user()->id != $post->user->id): ?>
               <li class="main-link">
                <a href="#" class="hide-post" data-post-id="<?php echo e($post->id); ?>">
                  <i class="fa fa-eye-slash" aria-hidden="true"></i><?php echo e(trans('common.hide_notifications')); ?>

                  <span class="small-text"><?php echo e(trans('messages.hide_notification_text')); ?></span>
                </a>
              </li>

              <li class="main-link">
                <a href="#" class="save-post" data-post-id="<?php echo e($post->id); ?>">
                  <i class="fa fa-save" aria-hidden="true"></i>
                    <?php if(!Auth::user()->postsSaved->contains($post->id)): ?>
                      <?php echo e(trans('common.save_post')); ?>

                      <span class="small-text"><?php echo e(trans('messages.post_save_text')); ?></span>
                    <?php else: ?>
                      <?php echo e(trans('common.unsave_post')); ?>

                      <span class="small-text"><?php echo e(trans('messages.post_unsave_text')); ?></span>
                    <?php endif; ?>
                </a>
              </li>

              <li class="main-link">
                  <a href="#" class="pin-post" data-post-id="<?php echo e($post->id); ?>">
                      <i class="fa fa-save" aria-hidden="true"></i>
                      <?php if(!Auth::user()->postsPinned->contains($post->id)): ?>
                          <?php echo e(trans('common.pin_to_your_profile_page')); ?>

                          <span class="small-text"><?php echo e(trans('messages.post_pin_to_profile_text')); ?></span>
                      <?php else: ?>
                          <?php echo e(trans('common.unpin_from_your_profile_page')); ?>

                          <span class="small-text"><?php echo e(trans('messages.post_unpin_from_profile_text')); ?></span>
                      <?php endif; ?>
                  </a>
              </li>

              <li class="main-link">
                <a href="#" class="manage-report report" data-post-id="<?php echo e($post->id); ?>">
                  <i class="fa fa-flag" aria-hidden="true"></i><?php echo e(trans('common.report')); ?>

                  <span class="small-text"><?php echo e(trans('messages.report_text')); ?></span>
                </a>
              </li>
              <?php endif; ?>

              <li class="divider"></li>

              <?php if((Auth::id() == $post->user->id) || ($post->timeline_id == Auth::user()->timeline_id)): ?>
                  <li class="main-link">
                      <a href="#" data-toggle="modal" data-target="#statisticsModal<?php echo e($post->id); ?>"><i class="fa fa-bar-chart"></i><?php echo e(trans('common.post_statistics')); ?></a>
                  </li>
              <?php endif; ?>

              <li class="main-link">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(url('/share-post/'.$post->id))); ?>" class="fb-xfbml-parse-ignore" target="_blank"><i class="fa fa-facebook-square"></i>Facebook <?php echo e(trans('common.share')); ?></a>
              </li>

              <li class="main-link">
                <a href="https://twitter.com/intent/tweet?text=<?php echo e(url('/share-post/'.$post->id)); ?>" target="_blank"><i class="fa fa-twitter-square"></i>Twitter <?php echo e(trans('common.tweet')); ?></a>
              </li>

              <li class="main-link">
                <a href="#" data-toggle="modal" data-target="#shareModal<?php echo e($post->id); ?>"><i class="fa fa-share-alt"></i>Embed <?php echo e(trans('common.post')); ?></a>
              </li>
              
              <li class="main-link">
                <a href="#" data-toggle="modal" data-target="#copyLinkModal<?php echo e($post->id); ?>"><i class="fa fa-link"></i>Copy Link</a>

              </li>

            </ul>

          </li>

        </ul>
      </div>
    <?php endif; ?>
      <div class="user-avatar">
        <a href="<?php echo e(url($post->user->username)); ?>"><img src="<?php echo e($post->user->avatar); ?>" alt="<?php echo e($post->user->name); ?>" title="<?php echo e($post->user->name); ?>"></a>
      </div>
      <div class="user-post-details">
        <ul class="list-unstyled no-margin">
          <li>

              <?php if(isset($sharedOwner)): ?>
                <a href="<?php echo e(url($sharedOwner->user->username)); ?>" title="<?php echo e('@'.$sharedOwner->user->username); ?>" data-toggle="tooltip" data-placement="top" class="user-name user">
                <?php echo e($sharedOwner->user->name); ?>

              </a>
              shared
              <?php endif; ?>

            <a href="<?php echo e(url($post->user->username)); ?>" title="<?php echo e('@'.$post->user->username); ?>" data-toggle="tooltip" data-placement="top" class="user-name user">
              <?php echo e($post->user->name); ?>

            </a>
            <?php if($post->user->verified): ?>
              <span class="verified-badge bg-success">
                    <i class="fa fa-check"></i>
                </span>
            <?php endif; ?>

            <?php if(isset($sharedOwner)): ?>
               's post
            <?php endif; ?>

            <?php if($post->users_tagged->count() > 0): ?>
              <?php echo e(trans('common.with')); ?>

              <?php $post_tags = $post->users_tagged->pluck('name')->toArray(); ?>
              <?php $post_tags_ids = $post->users_tagged->pluck('id')->toArray(); ?>
              <?php $__currentLoopData = $post->users_tagged; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($key==1): ?>
                  <?php echo e(trans('common.and')); ?>

                    <?php if(count($post_tags)==1): ?>
                      <a href="<?php echo e(url($user->username)); ?>"> <?php echo e($user->name); ?></a>
                    <?php else: ?>
                      <a href="#" data-toggle="tooltip" title="" data-placement="top" class="show-users-modal" data-html="true" data-heading="<?php echo e(trans('common.with_people')); ?>"  data-users="<?php echo e(implode(',', $post_tags_ids)); ?>" data-original-title="<?php echo e(implode('<br />', $post_tags)); ?>"> <?php echo e(count($post_tags).' '.trans('common.others')); ?></a>
                    <?php endif; ?>
                  <?php break; ?>
                <?php endif; ?>
                <?php if($post_tags != null): ?>
                  <a href="<?php echo e(url($user->username)); ?>" class="user"> <?php echo e(array_shift($post_tags)); ?> </a>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php endif; ?>
            <div class="small-text">
              <?php if(isset($timeline)): ?>
                <?php if($timeline->type != 'event' && $timeline->type != 'page' && $timeline->type != 'group'): ?>
                  <?php if($post->timeline->type == 'page' || $post->timeline->type == 'group' || $post->timeline->type == 'event'): ?>
                    (posted on
                    <a href="<?php echo e(url($post->timeline->username)); ?>"><?php echo e($post->timeline->name); ?></a>
                    <?php echo e($post->timeline->type); ?>)
                  <?php endif; ?>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </li>
          <li>
            <?php if(isset($sharedOwner)): ?>
               <time class="post-time timeago" datetime="<?php echo e($sharedOwner->created_at); ?>+00:00" title="<?php echo e($sharedOwner->created_at); ?>+00:00">
                <?php echo e($sharedOwner->created_at); ?>+00:00
              </time>
            <?php else: ?>

              <!--<time class="post-time" datetime="<?php echo e($post->created_at); ?>" title="<?php echo e($post->created_at); ?>">-->
              <!--  <?php echo e(date('m/d/Y', strtotime($post->created_at))); ?>-->
              <!--</time>-->
              <time class="post-time timeago" datetime="<?php echo e($post->created_at); ?>+00:00" title="<?php echo e($post->created_at); ?>+00:00" hidden>
                  <?php echo e($post->created_at); ?>+00:00
                <!--<?php echo e(date('m/d/Y', strtotime($post->created_at))); ?>-->
              </time>
            <?php endif; ?>


            <?php if($post->location != NULL && !isset($sharedOwner)): ?>
            <?php echo e(trans('common.at')); ?> <span class="post-place">
              <a target="_blank" href="<?php echo e(url('/get-location/'.$post->location)); ?>">
                <i class="fa fa-map-marker"></i> <?php echo e($post->location); ?>

              </a>
              </span></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="text-wrapper">

          <div id="statisticsModal<?php echo e($post->id); ?>" class="modal fade" role="dialog" tabindex='-1'>
              <div class="modal-dialog">

                  <div class="modal-content">
                      <div class="modal-body">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h3 class="modal-title"><?php echo e(trans('common.post_statistics')); ?></h3>
                          </div>

                          <div class="b-stats-row__content">
                              <div class="b-stats-row__label m-border-line m-purchases m-current">
                                  <span class="b-stats-row__name m-dots"> <?php echo e(trans('common.purchases')); ?> </span><span class="b-stats-row__val"> $0.00 </span>
                              </div>
                              <div class="b-stats-row__label m-border-line m-viewers m-current">
                                  <span class="b-stats-row__name m-dots"> <?php echo e(trans('common.viewers')); ?> </span><span class="b-stats-row__val"> 0 </span>
                              </div>
                              <div class="b-stats-row__label m-border-line m-likes m-current">
                                  <span class="b-stats-row__name m-dots"> <?php echo e(trans('common.likes')); ?> </span><span class="b-stats-row__val"> <?php echo e($post->users_liked->count()); ?> </span>
                              </div>
                              <div class="b-stats-row__label m-border-line m-comments m-current">
                                  <span class="b-stats-row__name m-dots"> <?php echo e(trans('common.comments')); ?> </span><span class="b-stats-row__val"> <?php echo e($post->comments->count()); ?> </span>
                              </div>
                              <div class="b-stats-row__label m-border-line m-tips m-current">
                                  <span class="b-stats-row__name m-dots"> <?php echo e(trans('common.tips')); ?> </span><span class="b-stats-row__val"> $0.00 <!----></span>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('common.close')); ?></button>
                      </div>
                  </div>

              </div>
          </div>

          <div id="shareModal<?php echo e($post->id); ?>" class="modal fade" role="dialog" tabindex='-1'>
              <div class="modal-dialog">

                  <div class="modal-content">
                      <div class="modal-body">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h3 class="modal-title"><?php echo e(trans('common.copy_embed_post')); ?></h3>
                          </div>
                          <textarea class="form-control" rows="3">
          <iframe src="<?php echo e(url('/share-post/'.$post->id)); ?>" width="600px" height="420px" frameborder="0"></iframe>
          </textarea>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('common.close')); ?></button>
                      </div>
                  </div>

              </div>
          </div>

          <div id="copyLinkModal<?php echo e($post->id); ?>" class="modal fade" role="dialog" tabindex='-1'>
              <div class="modal-dialog">

                  <div class="modal-content">
                      <div class="modal-body">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h3 class="modal-title"><?php echo e(trans('common.copy_link_post')); ?></h3>
                          </div>
                          
                          <a href="<?php echo e(url('/post/'.$post->id)); ?>"><?php echo e(url('/post/'.$post->id)); ?></a>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('common.close')); ?></button>
                      </div>
                  </div>

              </div>
          </div>


        <?php
              $links = preg_match_all("/(?i)\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/", $post->description, $matches);

              $main_description = $post->description;
              ?>
              <?php $__currentLoopData = $matches[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $linkPreview = new LinkPreview(/*$link*/ "www.google.com");
                  $parsed = $linkPreview->getParsed();
                  $data = $link;
                  foreach ($parsed as $parserName => $main_link) {
                    $data = '<div class="row link-preview">
                              <div class="col-md-3">
                                <a target="_blank" href="'.$link.'"><img src="'.$main_link->getImage().'"></a>
                              </div>
                              <div class="col-md-9">
                                <a target="_blank" href="'.$link.'">'.$main_link->getTitle().'</a><br>'.substr($main_link->getDescription(), 0, 500). '...'.'
                              </div>
                            </div>';
                    //echo substr($main_link->getDescription(), 0, 500);
                  }
                 $main_description = str_replace($link, $data, $main_description);
                  ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        

        <?php if(isset($user) == false || $user->followers->contains(Auth::user()->id) || $user->id == Auth::user()->id || $user->price == 0): ?>
            <p class="post-description">
              <?php echo clean($main_description); ?>

            </p>
        <?php endif; ?>

        


        <?php if(isset($user) && !$user->followers->contains(Auth::user()->id) && $user->id != Auth::user()->id && $user->price > 0 ): ?>
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
        <?php else: ?>

        <div class="post-image-holder  <?php if(count($post->images()->get()) == 1): ?> single-image <?php endif; ?>">

          <?php $__currentLoopData = $post->images()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $postImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($postImage->type=='image'): ?>
            <a href="<?php echo e(url('user/gallery/'.$postImage->source)); ?>" data-lightbox="imageGallery.<?php echo e($post->id); ?>" ><img src="<?php echo e(url('user/gallery/'.$postImage->source)); ?>"  title="<?php echo e($post->user->name); ?>" alt="<?php echo e($post->user->name); ?>"></a>
          <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="post-v-holder">
          <?php $__currentLoopData = $post->images()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $postImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($postImage->type=='video'): ?>
              <div id="unmoved-fixture">
                    <video width="100%" height="auto" id="video-target" controls class="video-video-playe">
                    <source src="<?php echo e(url('user/gallery/video/'.$postImage->source)); ?>"></source>
                    </video>
              </div>

          <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php endif; ?>

      </div>
      <!--<?php if($post->youtube_video_id): ?>-->
      <!--<iframe  src="https://www.youtube.com/embed/<?php echo e($post->youtube_video_id); ?>" frameborder="0" allowfullscreen></iframe>-->
      <!--<?php endif; ?>-->
      <!--<?php if($post->soundcloud_id): ?>-->
      <!--<div class="soundcloud-wrapper">-->
      <!--  <iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/<?php echo e($post->soundcloud_id); ?>&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>-->
      <!--</div>-->
      <!--<?php endif; ?>-->

    <?php if(isset($user) == false || $user->followers->contains(Auth::user()->id) || $user->id == Auth::user()->id || $user->price == 0): ?>
      <ul class="actions-count list-inline">

        <li>
            <?php if($post->users_liked()->count() > 0): ?>
            <?php
                $liked_ids = $post->users_liked->pluck('id')->toArray();
                $liked_names = $post->users_liked->pluck('name')->toArray();
            ?>
              <a href="#" class="show-users-modal tag-like-<?php echo e($post->id); ?>" data-html="true" data-heading="<?php echo e(trans('common.likes')); ?>"  data-users="<?php echo e(implode(',', $liked_ids)); ?>" data-original-title="<?php echo e(implode('<br />', $liked_names)); ?>"><span class="count-circle"><i class="fa fa-thumbs-up"></i></span> <span class="circle-like-count circle-like-count-<?php echo e($post->id); ?>"><?php echo e($post->users_liked->count()); ?></span> <?php echo e(trans('common.likes')); ?></a>
           <?php else: ?>
                <a href="#" class="show-users-modal tag-like-<?php echo e($post->id); ?> hidden" data-html="true" data-heading="<?php echo e(trans('common.likes')); ?>" data-original-title=""><span class="count-circle"><i class="fa fa-thumbs-up"></i></span> <span class="circle-like-count circle-like-count-<?php echo e($post->id); ?>"></span> <?php echo e(trans('common.likes')); ?></a>
            <?php endif; ?>
        </li>

        <?php if($post->comments->count() > 0): ?>
        <li>
          <a href="#" class="show-all-comments"><span class="count-circle"><i class="fa fa-comment"></i></span><?php echo e($post->comments->count()); ?> <?php echo e(trans('common.comments')); ?></a>
        </li>
        <?php endif; ?>

        <?php if($post->shares->count() > 0): ?>
        <?php
        $shared_ids = $post->shares->pluck('id')->toArray();
        $shared_names = $post->shares->pluck('name')->toArray(); ?>
        <li>
          <a href="#" class="show-users-modal" data-html="true" data-heading="<?php echo e(trans('common.shares')); ?>"  data-users="<?php echo e(implode(',', $shared_ids)); ?>" data-original-title="<?php echo e(implode('<br />', $shared_names)); ?>"><span class="count-circle"><i class="fa fa-share"></i></span> <?php echo e($post->shares->count()); ?> <?php echo e(trans('common.shares')); ?></a>
        </li>
        <?php endif; ?>

      </ul>
    <?php endif; ?>
    </div>

    <?php
    $display_comment ="";
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
          $display_comment = "everyone";
        }
      }
    }

    ?>

     

     <?php if(isset($user) == false || $user->followers->contains(Auth::user()->id) || $user->id == Auth::user()->id  || $user->price == 0): ?>
    <div class="panel-footer fans">
      <ul class="list-inline footer-list">
        <?php if(!$post->users_liked->contains(Auth::user()->id)): ?>

          <li><a href="#" class="like-post like-<?php echo e($post->id); ?>" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa-thumbs-o-up"></i><?php echo e(trans('common.like')); ?></a></li>

          <li class="hidden"><a href="#" class="like-post unlike-<?php echo e($post->id); ?>" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa-thumbs-o-down"></i><?php echo e(trans('common.unlike')); ?></a></li>
        <?php else: ?>
          <li class="hidden"><a href="#" class="like-post like-<?php echo e($post->id); ?>" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa-thumbs-o-up"></i><?php echo e(trans('common.like')); ?></a></li>
          <li><a href="#" class="like-post unlike-<?php echo e($post->id); ?>" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa-thumbs-o-down"></i></i><?php echo e(trans('common.unlike')); ?></a></li>
        <?php endif; ?>
        <li><a href="#" class="show-comments"><i class="fa fa-comment-o"></i><?php echo e(trans('common.comment')); ?></a></li>

        <?php if(Auth::user()->id != $post->user_id): ?>
          <?php if(!$post->users_shared->contains(Auth::user()->id)): ?>
            <li><a href="#" class="share-post share" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa-share-square-o"></i><?php echo e(trans('common.share')); ?></a></li>
            <li class="hidden"><a href="#" class="share-post shared" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa fa-share-square-o"></i><?php echo e(trans('common.unshare')); ?></a></li>
          <?php else: ?>
            <li class="hidden"><a href="#" class="share-post share" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa-share-square-o"></i><?php echo e(trans('common.share')); ?></a></li>
            <li><a href="#" class="share-post shared" data-post-id="<?php echo e($post->id); ?>"><i class="fa fa fa-share-square-o"></i><?php echo e(trans('common.unshare')); ?></a></li>
          <?php endif; ?>

          <li>
              <a href="#" class="send-tip-post" data-toggle="modal" data-target="#sendTipModal"><i class="fa fa-dollar"></i><?php echo e(trans('common.send_tip')); ?></a>
          </li>

        <?php endif; ?>

      </ul>
    </div>

    <?php if($post->comments->count() > 0 || $post->user_id == Auth::user()->id || $display_comment == "everyone"): ?>
      <div class="comments-section all_comments" style="display:none">
        <div class="comments-wrapper">
          <div class="to-comment">  <!-- to-comment -->
            <?php if($display_comment == "only_follow" || $display_comment == "everyone" || $user_setting == "everyone" || $post->user_id == Auth::user()->id): ?>
            <div class="commenter-avatar">
              <a href="#"><img src="<?php echo e(Auth::user()->avatar); ?>" alt="<?php echo e(Auth::user()->name); ?>" title="<?php echo e(Auth::user()->name); ?>"></a>
            </div>
            <div class="comment-textfield">
              <form action="#" class="comment-form" method="post" files="true" enctype="multipart/form-data" id="comment-form">
                <div class="comment-holder">
                  <input class="form-control post-comment" autocomplete="off" data-post-id="<?php echo e($post->id); ?>" name="post_comment" placeholder="<?php echo e(trans('messages.comment_placeholder')); ?>" >

                    <input type="file" class="comment-images-upload hidden" accept="image/jpeg,image/png,image/gif" name="comment_images_upload">
                     <ul class="list-inline meme-reply hidden">
                      <li><a href="#" id="imageComment"><i class="fa fa-camera" aria-hidden="true"></i></a></li>
                      
                    </ul>
                </div>
                  <div id="comment-image-holder"></div>
              </form>
            </div>
            <div class="clearfix"></div>
            <?php endif; ?>
          </div><!-- to-comment -->

          <div class="comments post-comments-list"> <!-- comments/main-comment  -->
            <?php if($post->comments->count() > 0): ?>
            <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo Theme::partial('comment',compact('comment','post')); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
          </div><!-- comments/main-comment  -->
        </div>
      </div><!-- /comments-section -->
    <?php endif; ?>
    <?php endif; ?>
  </div>

<div id="sendTipModal" class="tip-modal modal fade" role="dialog" tabindex='1'>

    <input type="hidden" value="<?php echo e($post->id); ?>" id="post-id">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-header lists-modal">
                    
                    <h3 class="modal-title lists-modal-title">
                        <?php echo e(trans("common.send_tip")); ?>

                    </h3>
                </div>

                <div class="b-stats-row__content">
                    <input type="number" id="etTipAmount" class="form-control" placeholder="Tip amount" step="0.1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelSendTip" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('common.cancel')); ?></button>
                <button type="button" id="sendTip" class="btn btn-primary" disabled><?php echo e(trans('common.send_tip')); ?></button>
                <a href="<?php echo e(url(Auth::user()->username).'/settings/addpayment'); ?>" id="addPayment" class="btn btn-warning"><?php echo e(trans('common.add_payment')); ?></a>
            </div>
        </div>

    </div>
</div>



  <!-- Modal Ends here -->
  <?php if(isset($next_page_url)): ?>
  <a class="jscroll-next hidden" href="<?php echo e($next_page_url); ?>"><?php echo e(trans('messages.get_more_posts')); ?></a>
  <?php endif; ?>

  <?php echo Theme::asset()->container('footer')->usePath()->add('lightbox', 'js/lightbox.min.js'); ?>


<!--<script src="../js/popcorn.min.js"></script>-->
<!--<script src="../js/popcorn.capture.js"></script>-->

<script type="text/javascript">

    // function getPoster() {
    //   var pop = Popcorn( "#video-target" ),
    //     poster;
    //     pop.listen( "canplayall", function() {
    //         this.currentTime( 1 ).capture();
    //     })
    // }

    // getPoster();
    
  function share(){
        FB.ui(
          {
            method: 'feed',
            name: 'Put name',
            link: 'put link',
            picture: 'image url',
            description: 'descrition'
          },
          function(response) {
            if (response) {
               alert ('success');
            } else {
               alert ('Failed');
            }
          }
        );
    }
</script>
<style>
  .link-preview
  {
    border: 1px solid #EEE;
    margin: 7px 0px;
    padding: 5px;
  }
  .link-preview img
  {
    width: 100%;
    height: auto;
  }
</style>
