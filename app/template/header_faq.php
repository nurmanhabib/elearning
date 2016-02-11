<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Portal - <?php echo config('site_title', 'Desa Membangun') ?></title>
        <!-- start:stylesheet -->
        <link rel="stylesheet" href="<?php echo asset('plugins/bootstrap/dist/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/font-awesome/css/font-awesome.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo asset('node_modules/tether/dist/css/tether.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo asset('stylesheets/scrolling-nav.css'); ?>">
        <link rel="stylesheet" href="<?php echo asset('stylesheets/app.css'); ?>">
        <link rel="stylesheet" href="<?php echo asset('stylesheets/responsive.css'); ?>">
        <?php echo $custom_stylesheet ?>
        <!-- end:stylesheet -->
    </head>
    <body id="page-top" data-spy="scroll" data-target="#navbar-main">

        <!-- start:header -->
        <header id="header" class="header-landing-faq">
            <!-- start:header top -->
            <section id="header-top">
                <!-- start:header top -->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="header-top-left">
                                <ul>
                                    <li><i class="fa fa-clock"></i> <?php echo Carbon\Carbon::today()->format('d F Y') ?></li>
                                    <li><a href="mailto:<?php echo config('email', 'support@desamembangun.go.id') ?>"><i class="fa fa-envelope"></i> <?php echo config('email', 'support@desamembangun.go.id') ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end:header top -->
            </section>
            <!-- end:header top -->

            <!-- start: header content -->
            <section id="header-content-private">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="header-top-left">
                                <a class="navbar-brand hidden-lg-down" href="<?php echo dashboard_url() ?>">
                                    <img src="<?php echo config('site_logo', asset('images/logo.png')) ?>" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="advertis">
                                <?php echo ads_placement('banner_top') ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="header-top-right">
                                <form action="<?php echo dashboard_url('search') ?>" method="GET">
                                    <div class="input-group">
                                        <input name="term" class="form-control form-control-sm" placeholder="Search for..." type="text">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-secondary" type="submit"><i class="fa fa-search"></i></button>
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
                            <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                                <i class="fa fa-bars"></i>
                            </button>
                            <a class="navbar-logo-mobile navbar-logo-tablet hidden-lg-up" href="<?php echo home_url() ?>">
                                <img src="<?php echo asset('images/logo.png') ?>">
                            </a>
                            <button class="navbar-toggler dropdown-people hidden-lg-up pull-right" type="button" data-toggle="collapse" data-target="#navbarCollapselogout">
                                <a type="button">
                                    <img src="<?php echo auth()->getUser()->avatar ?>" alt="">
                                </a>
                            </button>
                            <div class="collapse navbar-toggleable-md" id="navbarCollapse">
                                <a class="navbar-brand hidden-lg-down" href="<?php echo home_url() ?>" style="display: none">
                                    <img src="<?php echo asset('images/logo.png') ?>" alt="">
                                </a>
                                <ul class="nav navbar-nav">
                                    <li class="nav-item <?php echo $active == 'dashboard' ? 'active' : '' ?>">
                                        <a class="nav-link" href="<?php echo dashboard_url() ?>">DASHBOARD <span class="sr-only">(current)</span></a>
                                    </li>
                                    <li class="nav-item <?php echo $active == 'artikel' ? 'active' : '' ?>">
                                        <a class="nav-link" href="<?php echo dashboard_url('article') ?>">PORTAL</a>
                                    </li>
                                    <li class="nav-item <?php echo $active == 'elibrary' ? 'active' : '' ?>">
                                        <a class="nav-link" href="<?php echo elib_url() ?>">ELIBRARY</a>
                                    </li>
                                    <li class="nav-item <?php echo $active == 'kelas' ? 'active' : '' ?>">
                                        <a class="nav-link" href="<?php echo virtualclass_url() ?>">KELAS ONLINE</a>
                                    </li>
                                    <li class="nav-item <?php echo $active == 'forum' ? 'active' : '' ?>">
                                        <a class="nav-link" href="<?php echo forum_url() ?>">FORUM</a>
                                    </li>
                                    <li class="nav-item <?php echo $active == 'konsultasi' ? 'active' : '' ?>">
                                        <a href="<?php echo konsultasi_url() ?>" class="nav-link">KONSULTASI</a>
                                    </li>
                                    <li class="nav-item <?php echo $active == 'vicon' ? 'active' : '' ?>">
                                        <a href="<?php echo vicon_url() ?>" target="_blank" class="nav-link">VIRTUAL CONFERENCE</a>
                                    </li>
                                </ul>
                                <ul class="nav navbar-nav hidden-md-down pull-right">
                                    <div class="dropdown dropdown-people">
                                        <a class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="<?php echo auth()->getUser()->avatar ?>" alt="" title="<?php echo auth()->getUser()->full_name; ?>">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right custom-float" aria-labelledby="dropdownMenu2">
                                            <center><h2 class="dropdown-header font-weight-bold bg-inverse"><?php echo auth()->getUser()->full_name; ?></h2></center>
                                            <a href="<?php echo dashboard_url('profile') ?>" class="dropdown-item">Profile</a>
                                            <a href="<?php echo dashboard_url('sendArticle') ?>" class="dropdown-item">Submit Artikel</a>
                                            <a href="<?php echo logout_url() ?>" class="dropdown-item">Log Out</a>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                            <div class="collapse navbar-toggleable-md" id="navbarCollapselogout">
                                <ul class="hidden-lg-up nav navbar-nav">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="<?php echo dashboard_url('profile') ?>">PROFIL</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo dashboard_url('sendArticle') ?>">SUBMIT ARTIKEL</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo logout_url() ?>">LOGOUT</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </section>
            <!-- end:navbar main -->
            
            <!-- start:header main -->
            <section id="header-faq">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-main-box">
                                <div class="header-main-box-content">
                                      <div class="box-header">
                                          <h3><em>FAQ (Frequently Asked Question)</em></h3>
                                          <p>Format daftar informasi online berisi pertanyaan yang sering diajukan orang dan jawaban yang sudah disediakan.</p> 
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end:header main -->
        </header>
        <!-- end:header -->