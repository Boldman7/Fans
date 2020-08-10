<div class="container">

<div class="row tpadding-20">
  <div class="col-md-6">

    <h2 class="register-heading"><?php echo e(trans('common.create_account')); ?></h2>
    <div class="panel panel-default">
      <div class="panel-body nopadding">

        <div class="login-bottom">

          <ul class="signup-errors text-danger list-unstyled"></ul>

          <form method="POST" class="signup-form" action="<?php echo e(url('/register')); ?>">
            <?php echo e(csrf_field()); ?>


        <div class="row" hidden>
            <div class="col-md-6">
                <fieldset class="form-group<?php echo e($errors->has('affiliate') ? ' has-error' : ''); ?>">
                  <?php echo e(Form::label('affiliate', trans('auth.affiliate_code'))); ?><i class="optional">(optional)</i>
                  <?php if(isset($_GET['affiliate'])): ?>
                  <?php echo e(Form::text('affiliate', $_GET['affiliate'], ['class' => 'form-control', 'id' => 'affiliate', 'disabled' =>'disabled'])); ?>

                  <?php echo e(Form::hidden('affiliate', $_GET['affiliate'])); ?>

                  <?php else: ?>
                  <?php echo e(Form::text('affiliate', NULL, ['class' => 'form-control', 'id' => 'affiliate', 'placeholder'=> trans('auth.affiliate_code')])); ?>

                  <?php endif; ?>
                  <?php if($errors->has('affiliate')): ?>
                  <span class="help-block">
                    <?php echo e($errors->first('affiliate')); ?>

                  </span>
                  <?php endif; ?>
                </fieldset>
            </div>
        </div>

            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required <?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                  <?php echo e(Form::label('email', trans('auth.email_address'))); ?> 
                  <?php echo e(Form::text('email', NULL, ['class' => 'form-control', 'id' => 'email', 'placeholder'=> trans('auth.email_address')])); ?>

                  <ul class="signup-email-error text-danger list-unstyled">
                  </ul>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                  <?php echo e(Form::label('name', trans('auth.name'))); ?> 
                  <?php echo e(Form::text('name', NULL, ['class' => 'form-control', 'id' => 'name', 'placeholder'=> trans('auth.name')])); ?>


                  <ul class="signup-name-error text-danger list-unstyled">
                  </ul>
                </fieldset>
              </div>
            </div>















            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required <?php echo e($errors->has('username') ? ' has-error' : ''); ?>">
                  <?php echo e(Form::label('username', trans('common.username'))); ?> 
                  <?php echo e(Form::text('username', NULL, ['class' => 'form-control', 'id' => 'username', 'placeholder'=> trans('common.username')])); ?>


                  <ul class="signup-username-error text-danger list-unstyled">
                  </ul>
                <small class="text-muted"><a href="<?php echo e(url('/')); ?>"><?php echo e(url('/')); ?>/username</a></small>
                </fieldset>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12">
                <fieldset class="form-group required <?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                  <?php echo e(Form::label('password', trans('auth.password'))); ?> 
                  <?php echo e(Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder'=> trans('auth.password')])); ?>


                  <ul class="signup-password-error text-danger list-unstyled">
                  </ul>
                </fieldset>
              </div>
            </div>

            <div class="row">

























            </div>

            <div class="row">
              <?php if(Setting::get('captcha') == "on"): ?>
              <div class="col-md-12">
                <fieldset class="form-group<?php echo e($errors->has('captcha_error') ? ' has-error' : ''); ?>">
                  <?php echo app('captcha')->display(); ?>

                  <?php if($errors->has('captcha_error')): ?>
                  <span class="help-block">
                    <?php echo e($errors->first('captcha_error')); ?>

                  </span>
                  <?php endif; ?>
                </fieldset>
              </div>    
              <?php endif; ?>    
            </div>

            <?php echo e(Form::button(trans('auth.signup_to_dashboard'), ['type' => 'submit','class' => 'btn btn-success btn-submit'])); ?>


          </form>
        </div>  
        <?php if(config('services.google.client_id') != NULL && config('services.google.client_secret') ||
          config('services.twitter.client_id') != NULL && config('services.twitter.client_secret') ||
          config('services.facebook.client_id') != NULL && config('services.facebook.client_secret') ||
          config('services.linkedin.client_id') != NULL && config('services.linkedin.client_secret') ): ?>
          <div class="divider-login">
            <div class="divider-text"> <?php echo e(trans('auth.login_via_social_networks')); ?></div>
          </div>
          <?php endif; ?>
          <ul class="list-unstyled social-connect">




            <li style="margin-bottom: 5px"><a href="<?php echo e(url('twitter')); ?>" class="btn btn-social tw"><span style="color: white"><?php echo e(trans('common.signup_twitter')); ?> </span><i class="social-circle fa fa-twitter" aria-hidden="true"></i></a></li>
            <li><a href="<?php echo e(url('facebook')); ?>" class="btn btn-social fb"><span style="color: white"><?php echo e(trans('common.signup_facebook')); ?> </span><i class="social-circle fa fa-facebook" aria-hidden="true"></i></a></li>
          </ul>
        </div>
      </div><!-- /panel -->
    </div>
    
  </div><!-- /row -->
</div><!-- /container -->
<?php echo Theme::asset()->container('footer')->usePath()->add('app', 'js/app.js'); ?>