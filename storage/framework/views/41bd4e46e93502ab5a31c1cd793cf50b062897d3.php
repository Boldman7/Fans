<div class="login-block">
    <div class="panel panel-default">
        <div class="panel-body nopadding">
            <div class="login-head">
                <?php echo e(trans('auth.login_welcome_heading')); ?>

                <!--<div class="header-circle"><i class="fa fa-paper-plane" aria-hidden="true"></i></div>-->
                <div class="header-circle login-progress hidden"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div>
            </div>
            <div class="login-bottom">
                <div class="login-errors text-danger"></div>
                <?php if(Config::get('app.env') == 'demo'): ?>
                    <div class="alert alert-success">
                        username : <code>bootstrapguru</code> &nbsp;&nbsp;&nbsp;   password : <code>fans</code>
                    </div>
                <?php endif; ?>

                <?php if(Request::get('echk') == "on"): ?>
                    <div class="alert alert-info fade in" id="emailalert">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Note!</strong> <?php echo e(trans('auth.email_verify')); ?>

                    </div>
                <?php endif; ?>
                
                <?php if(session()->has('login_notice')): ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo e(session()->get('login_notice')); ?>

                </div>
                <?php endif; ?> 

                <form method="POST" class="login-form" action="<?php echo e(url('/main-login')); ?>">
                    <?php echo e(csrf_field()); ?>

                    <fieldset class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                        <?php echo e(Form::label('email', trans('auth.enter_email_or_username'))); ?>

                        <?php echo e(Form::text('email', NULL, ['class' => 'form-control', 'id' => 'email', 'placeholder'=> trans('auth.enter_email_or_username')])); ?>

                    </fieldset>
                    <fieldset class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                        <?php echo e(Form::label('password', trans('auth.password'))); ?>

                        <?php echo e(Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder'=> trans('auth.password')])); ?>

                    </fieldset>
                    <?php echo e(Form::button( trans('common.signin') , ['type' => 'submit','class' => 'btn btn-success btn-submit'])); ?>

                </form>
            </div>
                <div class="divider-login">
                    <div class="divider-text"> <?php echo e(trans('auth.login_via_social_networks')); ?></div>
                </div>
                <ul class="list-unstyled social-connect">
    
                    <li style="margin-bottom: 5px"><a href="<?php echo e(url('twitter')); ?>" class="btn btn-social tw"><span><span style="color: white">SIGN IN WITH TWITTER </span><i class="social-circle fa fa-twitter" aria-hidden="true"></i></span></a></li>
    
                    <li><a href="<?php echo e(url('facebook')); ?>" class="btn btn-social fb"><span><span style="color: white">SIGN IN WITH FACEBOOK </span><i class="social-circle fa fa-facebook" aria-hidden="true"></i></span></a></li>
    
                </ul>
        </div>

    </div>
    <div class="problem-login">
        <div class="pull-left"><?php echo e(trans('auth.dont_have_an_account_yet')); ?><a href="<?php echo e(url('/register')); ?>" style="color: red"> <?php echo e(trans('auth.get_started')); ?></a></div>
        <div class="pull-right"><a href="<?php echo e(url('/password/reset')); ?>"><?php echo e(trans('auth.forgot_password').'?'); ?></a></div>
        <div class="clearfix"></div>
    </div>
</div><!-- /login-block -->
