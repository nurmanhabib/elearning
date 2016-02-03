<!-- start:section content main articles -->
<section class="content-articles">
    <div class="content-articles-heading">
        <h3><?php echo $category->name ?></h3>
    </div>
    <div class="content-articles-main news2">
        <div class="row">
            <div class="col-lg-12">
                <?php if ($articles->count()): ?>
                    <?php foreach ($articles as $article): ?>
                        <div class="articles-box">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="articles-box-img">
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
                <?php else: ?>
                    <p class="alert alert-warning">Tidak ada artikel yang tersedia.</p>
                <?php endif ?>
                <center>
                    <?php echo $articles->render() ?>
                </center>
            </div>
        </div>
    </div>
</section>
<!-- end:section content main articles -->
