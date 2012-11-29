<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=@$header['title'];?></title>
    <meta name="description" content="<?=@$header['description'];?>" />
    <meta name="keywords" content="" />
    <meta charset="utf-8">
    <!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> -->
    <meta name="author" content="" />
    <link rel='index' title='<?=SITE_TITLE;?>' href='<?=base_url();?>' />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- stylesheets -->
    <?php foreach ($header['files']['css'] as $css_file): ?>
    <link rel="stylesheet" href="<?=$css_file;?>" type="text/css" media="all" />
    <?php endforeach; ?>

    <!-- set global js variables -->
    <script type="text/javascript">
    <?php
    // set the JS variables
    foreach ($js_vars as $var => $value)
    {
        // normal variable, not array
        if (! is_array($value))
            echo "var $var = " . (is_numeric($value)?$value:"\"$value\"") . ";\n" ;
        // if variable was an associated array
        else
        {
            echo "var $var = {";
            foreach ($value as $key => $key_value)
            {
                $key = is_numeric($key) ? $key : "\"$key\"";
                $key_value = is_numeric($key_value) ? $key_value : "\"$key_value\"";
                echo "$key:$key_value" . (next($value)?", ":"");
            }
            echo "};\n";
        };
    };
    ?>
    </script>

    <!-- jss -->
    <?php foreach ($header['files']['js'] as $js_file): ?>
    <script type="text/javascript" src="<?=$js_file;?>" ></script>
    <?php endforeach; ?>
</head>
<body>
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" title="Back to dashboard" href="<?=buildurl("admin/");?>">Administration Panel</a>
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="btn-group pull-right">
                    <?if($logged_in):?>
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="icon-user"></i> <?=$logged_in_user;?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?=fnAnchor('#','Profile','title="Your Profile"');?>
                        <li class="divider"></li>
                        <li><a href="<?=buildurl("logout");?>">Logout</a></li>
                    </ul>
                    <?else:?>
                    <a href="<?=buildurl("login");?>" class="btn">Sign in</a>
                    <?endif;?>
                </div><!--/.btn-group -->

                <div class="btn-group pull-right">
                    <a class="btn btn-info" href="<?=buildurl();?>">back to frontend</a>
                </div><!--/.btn-group -->

                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <?= fnAnchor('admin','Dashboard','title="Dashboard"', '<i class="icon-white icon-home"></i> ')?>
                    </ul>
                </div><!--/.nav-collapse -->

            </div><!--/.container-fluid -->
        </div>
    </div>