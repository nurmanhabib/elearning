<!-- Begin : list all categories -->
<section class="content-list-categories">
    <div class="container">
        <div class="row">
            <div class="list-title">
                <h3>ALL Categories</h3>
            </div>
            <div class="list-categories">
                <ul class="nav nav-inline">
                    <?php foreach ($categories as $category): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $category->link_private ?>"><?php echo $category->name ?></a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- End : list all categories -->

<!-- start:section content main articles -->
<section class="content-dashboard-articles">
    <div class="content-dashboard-articles-heading">
        <h3>ARTIKEL TERKINI</h3>
    </div>
    <div class="content-dashboard-articles-main">
        <div class="row">
            <?php foreach ($latest->take(10)->chunk(5) as $chunk): ?>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php foreach ($chunk as $artikel): ?>
                <div class="box-dashboard-articles">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="box-dashboard-articles-img">
                                <a href="<?php echo $artikel->link ?>"><img src="<?php echo $artikel->featured_image ?>" alt=""></a>
                            </div>
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <div class="box-dashboard-articles-meta">
                                <ul>
                                    <li><i class="fa fa-calendar"></i> <?php echo $artikel->date->format('d F Y') ?></li>
                                </ul>
                            </div>
                            <div class="box-dashboard-articles-title">
                                <h4><a href="<?php echo $artikel->link ?>"><?php echo $artikel->title ?></a></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach ?> 
            </div>
            <?php endforeach ?> 
        </div>
    </div>
</section>
<!-- end:section content main articles -->

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <!-- start:section content main articles -->
        <section class="content-articles">
            <div class="content-articles-heading">
                <h3><?php echo $privatepage_category_1_title ?></h3>
            </div>
            <div class="content-articles-heading-view">
                <span class="pull-right"><a href="<?php echo $private_category_1_link ?>">view all <i class="fa fa-plus-square"></i></a></span>
            </div>
            <div class="content-articles-main">
                <div class="row">
                    <div class="col-lg-12">
                        <?php foreach ($privatepage_category_1_a as $artikel): ?>
                        <div class="articles-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="articles-box-img">
                                        <a href="<?php echo $artikel->link ?>"><img src="<?php echo $artikel->featured_image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="articles-box-meta">
                                        <ul>
                                            <li><i class="fa fa-calendar"></i><?php echo $artikel->date->format('d F Y') ?></li>
                                        </ul>
                                    </div>
                                    <div class="articles-box-title">
                                        <h3><a href="<?php echo $artikel->link ?>"><?php echo $artikel->title ?></a></h3>
                                    </div>
                                    <div class="articles-box-content">
                                        <p><?php echo $artikel->description ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- end:section content main articles -->
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <!-- start:section content main articles -->
        <section class="content-articles">
            <div class="content-articles-heading">
                <h3><?php echo $privatepage_category_2_title ?></h3>
            </div>
            <div class="content-articles-heading-view">
                <span class="pull-right"><a href="<?php echo $private_category_2_link ?>">view all <i class="fa fa-plus-square"></i></a></span>
            </div>
            <div class="content-articles-main">
                <div class="row">
                    <div class="col-lg-12">
                        <?php foreach ($privatepage_category_2_a as $artikel): ?>
                        <div class="articles-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="articles-box-img">
                                        <a href="<?php echo $artikel->link ?>"><img src="<?php echo $artikel->featured_image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="articles-box-meta">
                                        <ul>
                                            <li><i class="fa fa-calendar"></i><?php echo $artikel->date->format('d F Y') ?></li>
                                        </ul>
                                    </div>
                                    <div class="articles-box-title">
                                        <h3><a href="<?php echo $artikel->link ?>"><?php echo $artikel->title ?></a></h3>
                                    </div>
                                    <div class="articles-box-content">
                                        <p><?php echo $artikel->description ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- end:section content main articles -->
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <!-- start:section content main articles -->
        <section class="content-articles">
            <div class="content-articles-heading">
                <h3><?php echo $privatepage_category_3_title ?></h3>
            </div>
            <div class="content-articles-heading-view">
                <span class="pull-right"><a href="<?php echo $private_category_3_link ?>">view all <i class="fa fa-plus-square"></i></a></span>
            </div>
            <div class="content-articles-main">
                <div class="row">
                    <div class="col-lg-12">         
                        <?php foreach ($privatepage_category_3_a as $artikel): ?>           
                        <div class="articles-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="articles-box-img">
                                        <a href="<?php echo $artikel->link ?>"><img src="<?php echo $artikel->featured_image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="articles-box-meta">
                                        <ul>
                                            <li><i class="fa fa-calendar"></i><?php echo $artikel->date->format('d F Y') ?></li>
                                        </ul>
                                    </div>
                                    <div class="articles-box-title">
                                        <h3><a href="<?php echo $artikel->link ?>"><?php echo $artikel->title ?></a></h3>
                                    </div>
                                    <div class="articles-box-content">
                                        <p><?php echo $artikel->description ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- end:section content main articles -->
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <!-- start:section content main articles -->
        <section class="content-articles">
            <div class="content-articles-heading">
                <h3><?php echo $privatepage_category_4_title ?></h3>
            </div>
            <div class="content-articles-heading-view">
                <span class="pull-right"><a href="<?php echo $private_category_4_link ?>">view all <i class="fa fa-plus-square"></i></a></span>
            </div>
            <div class="content-articles-main">
                <div class="row">
                    <div class="col-lg-12">
                        <?php foreach ($privatepage_category_4_a as $artikel): ?>
                        <div class="articles-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="articles-box-img">
                                        <a href="<?php echo $artikel->link ?>"><img src="<?php echo $artikel->featured_image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="articles-box-meta">
                                        <ul>
                                            <li><i class="fa fa-calendar"></i><?php echo $artikel->date->format('d F Y') ?></li>
                                        </ul>
                                    </div>
                                    <div class="articles-box-title">
                                        <h3><a href="<?php echo $artikel->link ?>"><?php echo $artikel->title ?></a></h3>
                                    </div>
                                    <div class="articles-box-content">
                                        <p><?php echo $artikel->description ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- end:section content main articles -->
    </div>
</div>