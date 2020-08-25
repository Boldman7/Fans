<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="<?php echo csrf_token(); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />

    <meta name="keywords" content="<?php echo e(Setting::get('meta_keywords')); ?>">
    <meta name="description" content="<?php echo e(Setting::get('meta_description')); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('images/favicon.ico')); ?>">

    <meta content="<?php echo e(url('/')); ?>" property="og:url" />
    <meta content="<?php echo url('setting/'.Setting::get('logo')); ?>" property="og:image" />
    <meta content="<?php echo e(Setting::get('meta_description')); ?>" property="og:description" />
    <meta content="<?php echo e(Setting::get('site_name')); ?>" property="og:title" />
    <meta content="website" property="og:type" />
    <meta content="<?php echo e(Setting::get('site_name')); ?>" property="og:site_name" />


    <title>Fans Platform</title>
    <title><?php echo e(Setting::get('site_title')); ?></title>

    <link href="<?php echo e(Theme::asset()->url('css/flag-icon.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(Theme::asset()->url('css/custom.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(url('css/extra.css')); ?>" rel="stylesheet">
    <!--videojs-->
    <link href="//vjs.zencdn.net/7.8.2/video-js.min.css" rel="stylesheet">

<?php echo Theme::asset()->styles(); ?>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

        <![endif]-->
        <script type="text/javascript">
        function SP_source() {
          return "<?php echo e(url('/')); ?>/";
        }
        var i = 0;
        var base_url = "<?php echo e(url('/')); ?>/";
        var theme_url = "<?php echo Theme::asset()->url(''); ?>";
        </script>
        <?php echo Theme::asset()->scripts(); ?>

        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    </head>
    <body <?php if(Setting::get('enable_rtl') == 'on'): ?> class="direction-rtl" <?php endif; ?>>

        <?php echo Theme::content(); ?>


        <?php echo Theme::partial('footer'); ?>


        <?php echo Theme::asset()->container('footer')->scripts(); ?>

    </body>
</html>
