<div class="panel panel-default">
  <div class="panel-heading no-bg panel-settings">  
    <h3 class="panel-title">
      <?php echo e(trans('common.allnotifications')); ?> 
      <?php if(count($notifications) > 0): ?>
        <span class="side-right">
          <a href="<?php echo e(url('allnotifications/delete')); ?>" class="btn btn-danger text-white allnotifications-delete"><?php echo e(trans('common.delete_all')); ?></a>
        </span>
      <?php endif; ?>
    </h3>
  </div>

    <div class="panel-body timeline">

        <div class="tab">
            <button class="tablinks active" onclick="openCity(event, 'All')">
                <svg class="g-icon" aria-hidden="true" style="">
                    <use xlink:href="#icon-all" href="#icon-all">
                        <svg id="icon-all" viewBox="0 0 24 24"> <path d="M15 6H5a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1zm3-17H8a1 1 0 0 0 0 2h11a1 1 0 0 1 1 1v11a1 1 0 0 0 2 0V5a3 3 0 0 0-3-3zm-6 9a1 1 0 0 0-.71.29L9 14.59l-1.29-1.3A1 1 0 0 0 7 13a1 1 0 0 0-1 1 1 1 0 0 0 .29.71l2 2a1 1 0 0 0 1.42 0l4-4A1 1 0 0 0 14 12a1 1 0 0 0-1-1z"></path> </svg>
                    </use>
                </svg> ALL
            </button>
            <button class="tablinks" onclick="openCity(event, 'Liked')">
                <svg class="g-icon" aria-hidden="true" style="">
                    <use xlink:href="#icon-like" href="#icon-like">
                        <svg id="icon-like" viewBox="0 0 24 24"> <path d="M12,22a1,1,0,0,1-.71-.29C5.87,16.29,2,13.06,2,9A5.89,5.89,0,0,1,7.75,3,5.66,5.66,0,0,1,12,5.2,5.66,5.66,0,0,1,16.25,3,5.89,5.89,0,0,1,22,9c0,4-3.75,7.17-9.29,12.71A1,1,0,0,1,12,22ZM7.75,5A3.88,3.88,0,0,0,4,9c0,3.12,3.81,6.44,8,10.59,4.09-4.06,8-7.47,8-10.59a3.88,3.88,0,0,0-3.75-4A3.8,3.8,0,0,0,13,7.56a1,1,0,0,1-1.9,0A3.8,3.8,0,0,0,7.75,5Z"></path> </svg>
                    </use>
                </svg> LIKED
            </button>
            <button class="tablinks" onclick="openCity(event, 'Subscribed')">
                <svg class="g-icon" aria-hidden="true" style="">
                    <use xlink:href="#icon-unlocked" href="#icon-unlocked">
                        <svg id="icon-unlocked" viewBox="0 0 24 24"> <path d="M17.5 2A4.51 4.51 0 0 0 13 6.5V8H5a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-8a3 3 0 0 0-3-3V6.5a2.5 2.5 0 0 1 5 0V8a1 1 0 0 0 2 0V6.5A4.51 4.51 0 0 0 17.5 2zM16 11v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1zm-6 2a2 2 0 1 0 2 2 2 2 0 0 0-2-2z"></path> </svg>
                    </use>
                </svg> SUBSCRIBED
            </button>
        </div>

        <div id="All" class="tabcontent">
            <h3>ALL</h3>
            <div class="table-responsive">
                <table class="table apps-table fans">
                    <?php if(count($notifications) > 0): ?>
                        <thead>
                        <th></th>
                        <th><?php echo e(trans('common.notification')); ?></th>
                        <th><?php echo e(trans('admin.action')); ?></th>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo e(url('/'.$notification->notified_from->timeline->username)); ?>">
                                        <img src="<?php echo e($notification->notified_from->avatar); ?>" alt="<?php echo e($notification->notified_from->username); ?>" title="<?php echo e($notification->notified_from->name); ?>"></a><a href="<?php echo e(url($notification->notified_from->username)); ?>"></a>
                                </td>
                                <td><?php echo e(str_limit($notification->description,50)); ?></td>
                                <td><a href="#" data-notification-id="<?php echo e($notification->id); ?>" class="notification-delete"><span class="trash-icon bg-danger"><i class="fa fa-trash" aria-hidden="true"></i></span></a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    <?php else: ?>
                        <div class="alert alert-warning"><?php echo e(trans('messages.no_notifications')); ?></div>
                        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
                </table>
                <div class="pagination-holder">
                    <?php echo e($notifications->render()); ?>

                </div>
            </div>
        </div>

        <div id="Liked" class="tabcontent">
            <h3>LIKED</h3>
            <div class="table-responsive">
                <table class="table apps-table fans">
                    <?php if(count($notifications) > 0): ?>
                        <thead>
                        <th></th>
                        <th><?php echo e(trans('common.notification')); ?></th>
                        <th><?php echo e(trans('admin.action')); ?></th>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($notification->type == "like_post" || $notification->type == "unlike_post"): ?>
                                <tr>
                                    <td><a href="<?php echo e(url('/'.$notification->notified_from->timeline->username)); ?>">
                                            <img src="<?php echo e($notification->notified_from->avatar); ?>" alt="<?php echo e($notification->notified_from->username); ?>" title="<?php echo e($notification->notified_from->name); ?>"></a><a href="<?php echo e(url($notification->notified_from->username)); ?>"></a>
                                    </td>
                                    <td><?php echo e(str_limit($notification->description,50)); ?></td>
                                    <td><a href="#" data-notification-id="<?php echo e($notification->id); ?>" class="notification-delete"><span class="trash-icon bg-danger"><i class="fa fa-trash" aria-hidden="true"></i></span></a></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    <?php else: ?>
                        <div class="alert alert-warning"><?php echo e(trans('messages.no_notifications')); ?></div>
                        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
                </table>
                <div class="pagination-holder">
                    <?php echo e($notifications->render()); ?>

                </div>
            </div>
        </div>

        <div id="Subscribed" class="tabcontent">
            <h3>SUBSCRIBED</h3>
            <div class="table-responsive">
                <table class="table apps-table fans">
                    <?php if(count($notifications) > 0): ?>
                        <thead>
                        <th></th>
                        <th><?php echo e(trans('common.notification')); ?></th>
                        <th><?php echo e(trans('admin.action')); ?></th>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($notification->type == "follow" || $notification->type == "unfollow"): ?>
                                <tr>
                                    <td><a href="<?php echo e(url('/'.$notification->notified_from->timeline->username)); ?>">
                                            <img src="<?php echo e($notification->notified_from->avatar); ?>" alt="<?php echo e($notification->notified_from->username); ?>" title="<?php echo e($notification->notified_from->name); ?>"></a><a href="<?php echo e(url($notification->notified_from->username)); ?>"></a>
                                    </td>
                                    <td><?php echo e(str_limit($notification->description,50)); ?></td>
                                    <td><a href="#" data-notification-id="<?php echo e($notification->id); ?>" class="notification-delete"><span class="trash-icon bg-danger"><i class="fa fa-trash" aria-hidden="true"></i></span></a></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    <?php else: ?>
                        <div class="alert alert-warning"><?php echo e(trans('messages.no_notifications')); ?></div>
                        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
                </table>
                <div class="pagination-holder">
                    <?php echo e($notifications->render()); ?>

                </div>
            </div>
        </div>

  </div>
</div>

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