<div class="panel panel-default">
  <div class="panel-heading no-bg panel-settings bottom-border">
    <h3 class="panel-title">
      <?php echo e(trans('common.lists')); ?>

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
                            <input class="red-checkbox" type="radio" name="sort-lists" id="sortByName" value="name" checked>
                            <label class="red-list-label" for="sortByName">
                               Name
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="sort-lists" id="sortByRecent" value="recent">
                            <label class="red-list-label" for="sortByRecent">
                                Recent
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="sort-lists" id="sortByPeople" value="people">
                            <label class="red-list-label" for="sortByPeople">
                                People
                            </label>
                        </div>
                    </li>
                    <div class="divider">

                    </div>
                    <li class="main-link">

                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="order-lists" id="orderByASC" value="asc" checked>
                            <label class="red-list-label" for="orderByASC">
                                Ascending
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="red-checkbox" type="radio" name="order-lists" id="orderByDESC" value="desc">
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
        <?php if(!empty($user_lists)): ?>
            <?php $__currentLoopData = $user_lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(url('mylist').'/'.$user_list['id']); ?>">
                    <div class="modal-mylist-item">
                        <span class="red-mylist-label"><?php echo e($user_list['name']); ?></span>
                        <span class="red-mylist-count-label"><?php echo e($user_list['count']); ?></span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    </div>
</div>
