<!-- Modal starts here-->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
    <div class="modal-dialog modal-likes" role="document">
        <div class="modal-content">
        	<i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
</div>
<div class="col-md-12">
	<div class="footer-description">
		<div class="row" style="margin-bottom: 60px; text-align:center">
			<span class="col-sm-2 col-2 col-lg-2"></span>
			<a href="<?php echo e(url('/faq')); ?>" class="col-sm-2 col-2 col-lg-2 col-xs-12" style="margin-bottom:5px"><b><?php echo e(trans('common.faq')); ?></b></a>
			<a href="<?php echo e(url('support')); ?>" class="col-sm-2 col-2 col-lg-2 col-xs-12" style="margin-bottom:5px"><b><?php echo e(trans('common.support')); ?></b></a>
			<a href="<?php echo e(url('terms-of-use')); ?>" class="col-sm-2 col-2 col-lg-2 col-xs-12" style="margin-bottom:5px"><b><?php echo e(trans('common.term_of_use')); ?></b></a>
			<a href="<?php echo e(url('privacy-policy')); ?>" class="col-sm-2 col-2 col-lg-2 col-xs-12" style="margin-bottom:5px"><b><?php echo e(trans('common.privacy_policy')); ?></b></a>
			<span class="col-sm-2 col-2 col-lg-2"></span>
		</div>
		<div class="fans-terms text-center" >
		    Copyright &copy; 2020 <?php echo e(Setting::get('site_title')); ?>. All rights reserved.

			<span class="dropup"  style="margin-left: 20px">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
										<span>
											
											<?php 
											    if (Session::has('my_locale'))
											        $key = session('my_locale', 'en');
											    else if (Auth::check())
											        $key = Auth::user()->language; 
											     else $key = 'en';
											    
											?>
											
											<?php if($key == 'en'): ?>
												<span class="flag-icon flag-icon-us"></span>
											<?php elseif($key == 'gr'): ?>
												<span class="flag-icon flag-icon-gr"></span>
											<?php elseif($key == 'zh'): ?>
												<span class="flag-icon flag-icon-cn"></span>
											<?php else: ?>
												<span class="flag-icon flag-icon-<?php echo e($key); ?>"></span>
											<?php endif; ?>

                                        </span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
			<ul class="dropdown-menu">
				<?php $__currentLoopData = Config::get('app.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li class=""><a href="#" class="switch-language" data-language="<?php echo e($key); ?>">
							<?php if($key == 'en'): ?>
								<span class="flag-icon flag-icon-us"></span>
							<?php elseif($key == 'gr'): ?>
								<span class="flag-icon flag-icon-gr"></span>
							<?php elseif($key == 'zh'): ?>
								<span class="flag-icon flag-icon-cn"></span>
							<?php else: ?>
								<span class="flag-icon flag-icon-<?php echo e($key); ?>"></span>
							<?php endif; ?>

							<?php echo e($value); ?></a></li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</ul></span>
		</div>
		</div>
	</div>
</div>

<?php echo Theme::asset()->container('footer')->usePath()->add('app', 'js/app.js'); ?>



