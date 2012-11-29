
    <!-- Navbar ================================================== -->
    <div class="navbar"><!-- 1 -->
        <div class="navbar-inner"><!-- 2 -->
            <div class="container"><!-- 3 -->
                <button type="button"class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" title="Back to the home page" href="<?= base_url('') ?>">Test</a>
                <div class="nav-collapse collapse"><!-- 4 -->
                    <ul class="nav">
                        <?= fnAnchor('/','Home','title="Home"', '<i class="icon-home"></i> ')?>
                    </ul>
                    <ul class="nav pull-right">
                        <?if($logged_in):?>
                        <li class="divider-vertical"></li>
                            <?if($is_admin):?>
                        <a class="btn btn-info" href="<?=buildurl('admin');?>">Admin Panel</a>
                            <?endif;?>
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
                        <a href="<?=buildurl("login");?>" class="btn" title="Access the users-only functionality">Sign in</a>
                        <?endif;?>
                    </ul>
                </div><!-- /4 -->
            </div><!-- /3 -->
        </div><!-- /2 -->
    </div><!-- /1 -->
    <!-- /Navbar ================================================== -->




