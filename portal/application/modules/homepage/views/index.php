<?php if ($latest->count()): ?>
    <!-- start:section content main articles -->
     <section class="content-articles">
        <div class="content-articles-heading">
            <h3>BERITA TERKINI</h3>
        </div>
        <div class="content-articles-main news-latest">
            <div class="row">
                <div class="col-lg-12">
                <?php foreach ($latest->slice(0, 5) as $artikel): ?>
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
                                        <li><i class="fa fa-calendar"></i> <?php echo $artikel->date->format('d F Y') ?></li>
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
<?php endif ?>

<?php if ($homepage_category_2_a->count()): ?>
    <!-- start:section content main articles -->
    <section class="content-articles">
        <div class="content-articles-heading">
            <h3><?php echo $homepage_category_2_title ?></h3>
        </div>
        <div class="content-articles-heading-view hidden-sm-down">
            <span class="pull-right"><a href="<?php echo $homepage_category_2_link ?>">view all <i class="fa fa-plus-square"></i></a></span>
        </div>
        <div class="content-articles-main news2">
            <div class="row">
                <div class="col-lg-12">
                    <?php foreach ($homepage_category_2_a as $article): ?>
                    <div class="articles-box">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="articles-box-img news1-bottom">
                                    <a href="<?php echo $article->link ?>"><img src="<?php echo $article->featured_image ?>" alt=""></a>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="articles-box-meta">
                                    <ul>
                                        <li><i class="fa fa-calendar"></i> <?php echo $article->date->format('d F Y') ?></li>
                                    </ul>
                                </div>
                                <div class="articles-box-title">
                                    <h3><a href="<?php echo $article->link ?>"><?php echo $article->title ?></a></h3>
                                </div>
                                <div class="articles-box-content">
                                    <p><?php echo $article->description ?></p>
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
<?php endif ?>

<?php if ($homepage_category_3->count()): ?>
    <!-- start:section content main articles -->
    <section class="content-articles">
        <div class="content-articles-heading">
            <h3><?php echo $homepage_category_3_title ?></h3>
        </div>
        <div class="content-articles-heading-view hidden-sm-down">
            <span class="pull-right"><a href="<?php echo $homepage_category_3_link ?>">view all <i class="fa fa-plus-square"></i></a></span>
        </div>
        <div class="content-articles-main news2">
            <div class="row">
                <div class="col-lg-12">
                    <?php foreach ($homepage_category_3 as $article): ?>
                    <div class="articles-box">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="articles-box-img news1-bottom">
                                    <a href="<?php echo $article->link ?>"><img src="<?php echo $article->featured_image ?>" alt=""></a>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="articles-box-meta">
                                    <ul>
                                        <li><i class="fa fa-calendar"></i> <?php echo $article->date->format('d F Y') ?></li>
                                    </ul>
                                </div>
                                <div class="articles-box-title">
                                    <h3><a href="<?php echo $article->link ?>"><?php echo $article->title ?></a></h3>
                                </div>
                                <div class="articles-box-content">
                                    <p><?php echo $article->description ?></p>
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
<?php endif ?>

<?php if ($homepage_category_4->count()): ?>
    <!-- start:section content main articles -->
    <section class="content-articles">
        <div class="content-articles-heading">
            <h3><?php echo $homepage_category_4_title ?></h3>
        </div>
        <div class="content-articles-heading-view hidden-sm-down">
            <span class="pull-right"><a href="<?php echo $homepage_category_4_link ?>">view all <i class="fa fa-plus-square"></i></a></span>
        </div>
        <div class="content-articles-main news3">
            <div class="row">
                <?php foreach ($homepage_category_4->chunk(5) as $chunk): ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php foreach ($chunk as $artikel): ?>
                        <div class="box-articles-sm">
                            <div class="row">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="box-articles-sm-img">
                                        <a href="<?php echo $artikel->link ?>"><img src="<?php echo $artikel->featured_image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-xs-12">
                                    <div class="box-articles-sm-meta">
                                        <p><i class="fa fa-calendar"></i> <?php echo $artikel->date->format('d F Y') ?></p>
                                    </div>
                                    <div class="box-articles-sm-title">
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
<?php endif ?>