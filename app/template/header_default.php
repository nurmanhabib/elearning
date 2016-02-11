<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Portal - <?php echo config('site_title', 'Desa Membangun') ?></title>

        <!-- start:stylesheet -->
        <link rel="stylesheet" href="<?php echo asset('plugins/bootstrap/dist/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/font-awesome/css/font-awesome.min.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/select2/dist/css/select2.min.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/tether/dist/css/tether.min.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker-standalone.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/fancybox/dist/css/jquery.fancybox.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('stylesheets/glyphicon/css/glyphicon.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('stylesheets/scrolling-nav.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('stylesheets/app.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('stylesheets/responsive.css') ?>">
        <?php echo $custom_stylesheet ?>
        <!-- end:stylesheet -->
    </head>
    <body id="page-top" data-spy="scroll" data-target="#navbar-main">

         <!-- start:header -->
        <header id="header">
            <!-- start:header top -->
            <section id="header-top">
                <!-- start:header top -->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="header-top-left">
                                    <ul>
                                        <li><i class="fa fa-clock"></i><?php echo Carbon\Carbon::today()->format('d F Y') ?></li>
                                        <li><a href="mailto:<?php echo config('email', 'support@desamembangun.go.id') ?>"><i class="fa fa-envelope"></i> <?php echo config('email', 'support@desamembangun.go.id') ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end:header top -->
            </section>
            <!-- end:header top -->

            <!-- start: header content -->
            <section id="header-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="header-top-left">
                                    <a class="navbar-brand hidden-lg-down" href="<?php echo home_url() ?>">
                                        <img src="<?php echo config('site_logo', asset('images/logo.png')) ?>" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="advertis">
                                <?php echo ads_placement('banner_top') ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="header-top-right">
                                <form action="<?php echo home_url('search') ?>" method="GET">
                                    <div class="input-group">
                                        <input name="term" class="form-control form-control-sm" placeholder="Search for..." type="text">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-secondary" type="button"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                     </div>   
                </div>
            </section>
            <!-- end: header content -->

            <!-- start:navbar main -->
            <section id="navbar-main">
                <nav class="navbar navbar-light">
                    <div class="container">
                        <div class="row">
                            <!-- Begin : Trigger navbarCollapse -->
                            <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                                <!-- &#9776; --> <i class="fa fa-bars"></i>
                            </button>
                            <!-- End : Trigger navbarCollapse-->
                            <a class="navbar-logo-mobile navbar-logo-tablet hidden-lg-up" href="<?php echo home_url() ?>">
                                <img src="<?php echo config('site_logo', asset('images/logo.png')) ?>" alt="">
                            </a>
                            <!-- Begin : Trigger navbarCollapselogin -->
                            <button class="navbar-toggler hidden-lg-up pull-right" type="button" data-toggle="collapse" data-target="#navbarCollapselogin">
                                <img src="<?php echo config('site_logo', asset('images/login.png')) ?>" alt="" class="login-button">
                            </button>
                            <!-- End : Trigger navbarCollapselogin -->
                            <!-- Begin : Content navbarCollapse -->
                            <div class="collapse navbar-toggleable-md" id="navbarCollapse">
                                <a class="navbar-brand hidden-lg-down" href="<?php echo site_url() ?>" style="display: none">
                                    <img src="<?php echo config('site_logo', asset('images/logo.png')) ?>" alt="">
                                </a>
                                <ul class="nav navbar-nav">
                                    <li class="nav-item <?php echo $active == 'home' || empty($active) ? 'active' : '' ?>">
                                        <a class="nav-link" href="<?php echo home_url() ?>">HOME <span class="sr-only">(current)</span></a>
                                    </li>
                                    <?php
                                    $categories = Model\Portal\Category::ordered()->parentOnly()->get();

                                    foreach ($categories as $category): ?>
                                        <?php if ($category->childs->count()): ?>
                                            <li class="nav-item dropdown <?php echo $active == $category->id ? 'active' : '' ?>">
                                                <a class="nav-link dropdown-toggle" href="#"><?php echo strtoupper($category->name) ?></a>
                                                <ul class="dropdown-menu dropdown-navbar">
                                                    <?php foreach ($category->childs as $child): ?>
                                                        <li>
                                                            <a href="<?php echo $child->link ?>"><?php echo $child->name ?></a>
                                                        </li>
                                                    <?php endforeach ?>
                                                </ul>
                                            </li>
                                        <?php else: ?>
                                            <li class="nav-item <?php echo $active == $category->id ? 'active' : '' ?>">
                                                <a class="nav-link" href="<?php echo $category->link ?>"><?php echo strtoupper($category->name) ?></a>
                                            </li>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </ul>
                                <!-- Begin : Login or Profile button if login -->
                                <?php if (!sentinel()->check()): ?>
                                    <?php if ($active != 'home' && !empty($active)): ?>
                                        <div class="header-top-right-login hidden-md-down pull-right">
                                            <a href="<?php echo login_url() ?>" class="btn btn-primary-outline">Login </a>
                                        </div>
                                    <?php endif ?>
                                <?php else: ?>
                                    <ul class="nav navbar-nav hidden-md-down pull-right">
                                        <div class="dropdown dropdown-people">
                                            <a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <img src="<?php echo auth()->getUser()->avatar ?>" alt="">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right custom-float" aria-labelledby="dropdownMenu2">
                                                <a href="<?php echo dashboard_url('profile') ?>" class="dropdown-item">Profile</a>
                                                <a href="<?php echo dashboard_url('sendArticle') ?>" class="dropdown-item">Submit Artikel</a>
                                                <a href="<?php echo logout_url() ?>" class="dropdown-item">Log Out</a>
                                            </div>
                                        </div>
                                    </ul>
                                <?php endif ?>
                                <!-- End : Login or Profile button if login -->
                                <div class="header-top-right hidden-lg-down pull-right" style="display:none">
                                    <form action="<?php echo home_url('search') ?>" method="GET">
                                        <div class="input-group inner-addon right-addon">
                                            <input class="form-control form-control-sm" placeholder="Search for..." type="text">
                                            <i class="fa fa-search"></i>
                                        </div>
                                    </form>
                                </div>
                            </div> 
                            <!-- End : Content navbarCollapse -->
                            
                            <!-- Begin : Content navbarCollapselogin -->
                            <div class="collapse navbar-toggleable-md" id="navbarCollapselogin">
                                <ul class="nav navbar-nav custom-login-mobile">
                                    <li class="nav-item">
                                        <div class="menu-login hidden-lg-up">
                                            <p>Silahkan masukkan username dan password untuk Login.</p>
                                            <form class="form-login" method="POST" action="<?php echo login_url() ?>">
                                                <div class="form-group">
                                                    <input type="text" name="email" class="form-control" placeholder="Username / Email">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                                </div>
                                                <label class="c-input c-checkbox">
                                                    <input type="checkbox">
                                                    <span class="c-indicator"></span>
                                                    Remember me
                                                </label>
                                                <div class="form-group">
                                                    <button  class="btn btn-sm btn-login btn-block">LOGIN</button>
                                                </div>
                                                <div class="form group">
                                                    <label for=""><a href="#">Lupa password?</a></label>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </div>  
                            <!-- End : Content navbarCollapselogin -->
                        </div>
                    </div>
                </nav>
            </section>
            <!-- end:navbar main -->
            
            <!-- start:header main -->
            <?php $slider ? get_view('slider') : '' ?>
            <!-- end:header main -->
        </header>
        <!-- end:header -->