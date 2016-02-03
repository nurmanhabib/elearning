<article>
    <ol class="breadcrumb" style="margin-bottom: 5px;">
        <li><a href="<?php echo dashboard_url() ?>">Dashboard</a></li>
        <li><a href="<?php echo dashboard_url() ?>">Article</a></li>
        <?php try { ?>
            <?php $category = $article->categories()->firstOrFail() ?>
            <li><a href="<?php echo dashboard_url('category/show/'.$category->name) ?>"><?php echo $category->name ?></a></li>
        <?php } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {} ?>
    </ol>
    <div class="single-article-title">
        <h1><?php echo $article->title ?></h1>
    </div>
    <?php if ($article->hasFeaturedImage()): ?>
        <div class="single-article-img">
            <img src="<?php echo $article->featured_image ?>" alt="">
        </div>
    <?php endif ?>
    <div class="single-article-meta">
        <?php if ($article->hasFeaturedImage()): ?>
            <div class="photo">
                <em><?php echo $article->featured_description ?></em>
            </div>
        <?php endif ?>
        <ul>
            <li><i class="fa fa-calendar-check-o"></i> <?php echo $article->date->format('d F Y') ?></li>
            <li><i class="fa fa-folder-open-o"></i> <?php echo $article->categories->implode('name', ', ') ?></li>
            <li><i class="fa fa-comments-o"></i> <?php echo $article->comments->count() ?> Komentar</li>
        </ul>
    </div>
    <div class="single-article-content">
        <p><b>"<?php echo $article->description ?>"</b></p>        
        <?php echo $article->content ?>
    </div>
    <div class="single-article-author">
        <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-12">
                <div class="single-article-author-img">
                    <img src="<?php echo $article->author_avatar ?>" alt="">
                </div>
            </div>
            <div class="col-md-10 col-sm-9 col-xs-12">
                <div class="single-article-author-title">
                    <p><a href="#"><?php echo $article->author_name ?></a></p>
                </div>
                <div class="single-article-author-role">
                    <p><em>Editor: <?php echo $article->editor->full_name?></em></p>
                </div>
                <div class="single-article-author-description">
                    
                </div>
            </div>
        </div>
    </div>
</article>

<!-- start:section comments -->
<section id="comments">
    <div class="comments-form">
        <div class="comments-title">
            <h3>Leave a Comments</h3>
        </div>
        <div class="comments-form-main">
            <?php echo $this->load->view('comment/create', compact('article')); ?>
        </div>
        <div class="comments-title">
            <h3>Latest Comments</h3>
        </div>
        <div class="comments-content">
            <?php if ($article->comments->count()): foreach ($article->comments as $comment): ?>
            <div class="media" id="comment-<?php echo $comment->id ?>">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" src="<?php echo $comment->avatar ?>">
                    </a>
                </div>
                <div class="media-body">
                    <div class="media-body-bg">
                        <h4 class="media-heading"><?php echo $comment->nama ?> <a href="#comments" class="pull-right btn btn-sm btn-reply" v-on:click="reply('<?php echo $comment->id ?>', '<?php echo $comment->nama ?>')">Reply</a></h4>
                        <p><?php echo $comment->content ?></p>
                    </div>

                    <?php foreach ($comment->replies as $reply): ?>
                    <div class="media" id="comment-<?php echo $reply->id ?>">
                        <a class="media-left" href="#">
                            <img class="media-object" src="<?php echo $reply->avatar ?>">
                        </a>
                        <div class="media-body">
                            <div class="media-body-bg">
                                <h4 class="media-heading"><?php echo $reply->nama ?></h4>
                                <p><?php echo $reply->content ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>

                </div>
            </div>            
            <?php endforeach; else: ?>
            <p class="alert alert-info">Belum ada komentar.</p>
            <?php endif ?>
        </div>
    </div>
</section>
<!-- end:section comments -->


<!-- start:section content main articles -->
<section class="content-articles">
    <div class="content-articles-heading">
        <h3>Artikel Terkait</h3>
    </div>
    <div class="content-articles-main news3">
            <div class="row">
            <?php foreach ($relevance->chunk(3) as $chunk): ?>
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


<?php custom_stylesheet() ?>
    <link rel="stylesheet" type="text/css" href="<?php echo asset('plugins/rrssb-master/css/rrssb.css') ?>">
<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
    <script src="<?php echo asset('plugins/rrssb-master/js/rrssb.min.js') ?>"></script>
<?php endcustom_script() ?>
