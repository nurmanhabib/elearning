<!-- start:section content main articles -->
<section class="content-articles">
    <div class="content-articles-heading">
        <h3>Hasil Pencarian</h3>
    </div>
    Hasil Pencarian: "<?php echo $term ?>"<br><br>
    <div class="content-articles-main">
        <div class="row">
            <div class="col-lg-12">
                <?php foreach ($searches as $article): ?>
                <div class="articles-box">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="articles-box-img">
                                <a href="<?php echo $article->link ?>"><img src="<?php echo $article->featured_image ?>" alt=""></a>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="articles-box-meta">
                                <ul>
                                    <li><i class="fa fa-user"></i> <?php echo $article->author_name ?></li>
                                    <li>/</li>
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

                <?php echo $searches ?>
            </div>
        </div>
    </div>
</section>
<!-- end:section content main articles -->