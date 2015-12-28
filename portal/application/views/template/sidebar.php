
<!-- start:content sidebar -->
<div class="content-sidebar">
    <div class="widget">
        <div class="widget-heading">
            <h3>ARTIKEL PILIHAN</h3>
        </div>
        <div class="widget-content">
            <?php
            $choices = Model\Portal\Article::editorChoice()->latest('date')->get();

            foreach ($choices->slice(0, 5) as $article): ?>
                <div class="box-articles-widget">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="favorit">
                                <a href="<?php echo $article->link ?>">
                                    <div class="box-border-1">
                                        <h6><?php echo $article->title ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="widget">
        <div class="widget-heading">
            <ul class="nav nav-tabs" id="myTabSidebar" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="popular-post-tab" data-toggle="tab" href="#popular-post" role="tab" aria-controls="popular-post" aria-expanded="true">TERPOPULER</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="latest-post-tab" data-toggle="tab" href="#latest-post" role="tab" aria-controls="latest-post" aria-expanded="false">TERKINI</a>
                </li>
            </ul>
        </div>
        <div class="widget-content news4">
            <div class="tab-content" id="myTabSidebarContent">
                <div role="tabpanel" class="tab-pane fade active in" id="popular-post" aria-labelledby="popular-post-tab" aria-expanded="true">
                    <?php $popular = Model\Portal\Article::popular()->take(5)->get() ?>
                    <?php if ($popular->count()): ?>
                        <?php foreach ($popular as $article): ?>
                        <div class="box-articles-widget">
                            <div class="row">
                                <div class="col-sm-4 col-xs-6">
                                    <div class="box-articles-widget-img">
                                        <a href="<?php echo $article->link ?>"><img src="<?php echo $article->featured_image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-xs-6">
                                    <div class="box-articles-widget-meta">
                                        <ul>
                                            <li><i class="fa fa-calendar"></i> <?php echo $article->date->format('d F Y') ?></li>
                                        </ul>
                                    </div>
                                    <div class="box-articles-widget-title">
                                        <h4><a href="<?php echo $article->link ?>"><?php echo $article->title ?></a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="alert alert-warning">Tidak ada artikel yang ditampilkan.</p>
                    <?php endif ?>
                </div>
                <div class="tab-pane fade" id="latest-post" role="tabpanel" aria-labelledby="latest-post-tab" aria-expanded="false">
                    <?php $latest = Model\Portal\Article::latest('date')->limit(4)->get() ?>
                    <?php if ($latest->count()): ?>
                        <?php foreach ($latest as $article): ?>
                        <div class="box-articles-widget">
                            <div class="row">
                                <div class="col-sm-4 col-xs-6">
                                    <div class="box-articles-widget-img">
                                        <a href="<?php echo $article->link ?>"><img src="<?php echo $article->featured_image ?>" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-xs-6">
                                    <div class="box-articles-widget-meta">
                                        <ul>
                                            <li><i class="fa fa-calendar"></i> <?php echo $article->date->format('d F Y') ?></li>
                                        </ul>
                                    </div>
                                    <div class="box-articles-widget-title">
                                        <h4><a href="<?php echo $article->link ?>"><?php echo $article->title ?></a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        <p class="alert alert-warning">Tidak ada artikel yang ditampilkan.</p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <div class="widget">
        <div class="widget-heading">
            <h3>Komentar Terkini</h3>
        </div>
        <div class="widget-content">
            <div class="row">
                <?php $comments = Model\Portal\Comment::latest('date')->get() ?>
                <?php if ($comments->count()): ?>
                    <?php foreach ($comments as $comment): ?>
                    <div class="box-komentar-widget">
                        <div class="col-sm-3 col-xs-4">
                            <div class="box-komentar-widget-img">
                                <img src="<?php echo $comment->avatar ?>" alt="">
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-8">
                            <div class="box-komentar-widget-title">
                                <p><strong><a href="#"><?php echo $comment->nama ?></a></strong></p>
                            </div>
                            <div class="box-komentar-widget-content">
                                <p><?php echo $comment->content ?> <strong>ON</strong> <a href="<?php echo $comment->article->link ?>"><?php echo $comment->article->title ?></a></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="box-komentar-widget">
                        <div class="col-sm-12">
                            <p class="alert alert-warning">Tidak ada komentar yang ditampilkan.</p>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    
    <?php if (!$single): ?>
        <?php if (false): ?>
            <div class="widget">
                <div class="widget-heading">
                    <h3>E-LIB DOWNLOAD</h3>
                </div>
                <div class="widget-content">
                    <div class="box-grey">
                        <ul>
                            <?php
                            $elibs = (new Library\Media\Media)->latest()->slice(0, 4);

                            foreach ($elibs as $media) { ?>
                                <li><a href="<?php echo $media->link ?>"><?php echo $media->icon ?> <?php echo $media->name ?></a></li>
                            <?php } ?>
                        </ul>
                        <a href="<?php echo elib_url() ?>" class="btn btn-view">View All <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>            
        <?php endif ?>

    <div class="widget">
        <div class="widget-heading">
            <h3>LINK INFORMASI DESA</h3>
        </div>
        <div class="widget-content">
            <div class="box-grey">
                <ul>
                    <?php foreach ($links as $link): ?>
                        
                        <li><a href="<?php echo $link->url ?>"><i class="fa fa-angle-double-right"></i> <?php echo $link->name ?></a></li>
                        
                    <?php endforeach ?>  
                </ul>           
                <a href="<?php echo site_url('link') ?>" class="btn btn-view">View All <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>
<!-- end:content sidebar -->