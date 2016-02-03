<div class="content-left">
    <div class="content-title">
        <h1><?php echo $course->name ?></h1>
    </div>
    <?php if ($course->hasFeatured()): ?>
        <div class="content-featured-img">
            <img src="<?php echo $course->featured_image ?>" alt="">
        </div>
    <?php endif ?>
    <div class="content-main">
        <?php echo $course->description ?>
        <div class="bookmark">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="bookmark-social">
                        <ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="bookmark-button">
                        <?php if ($repository->isMember('pending')): ?>
                            <a href="#" class="btn btn-sm btn-bookmark">WAITING APPROVE INSTRUCTOR</a>
                        <?php elseif ($repository->isMember()): ?>
                            <a href="<?php echo site_url('course/chapter/'.$course->slug) ?>" class="btn btn-sm btn-bookmark">START COURSE</a>
                        <?php else: ?>
                            <a href="<?php echo site_url('course/join/'.$course->slug) ?>" class="btn btn-sm btn-bookmark">JOIN THIS COURSE</a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-lesson">
        <div class="content-lesson-title">
            <h3>CHAPTERS</h3>
        </div>
        <?php $numb = 1; foreach ($course->chapters as $chapter): ?>
            <a href="#" class="btn btn-block disabled text-xs-left">
                <div class="card card-grey">
                    <div class="card-block">
                        <span>CHAPTER <?php echo $numb ?></span>
                        <h4><?php echo $chapter->name ?></h4>
                    </div>
                </div>
            </a>
        <?php $numb++; endforeach ?>
    </div>
    <br><br>
    <div class="content-course-forum">
        <div class="content-course-forum-title">
            <h3>COURSE REVIEW</h3>
        </div>
        <div class="content-course-forum-main" id="comments">
            <p>There are <?php echo $course->comments->count() ?> reviews on this course</p>
            <ul class="list-course-forum">
                <?php if ($course->comments->count()): foreach ($course->comments()->parentOnly()->get() as $comment): ?>
                    <li>
                        <div class="row">
                            <div class="col-sm-2 col-xs-12">
                                <div class="text-center">
                                    <img src="<?php echo $comment->avatar ?>" alt="">
                                </div>
                            </div>
                            <div class="col-sm-10 col-xs-12">
                                <div class="meta">
                                    <p><strong><?php echo $comment->name ?></strong></p>
                                    <p><i class="fa fa-calendar"></i> <em><?php echo $comment->created_at->format('d F Y') ?></em></p>
                                </div>
                                <div class="list-content-forum">
                                    <h4 class="media-heading"><?php echo $comment->nama ?> <a href="#comment-reply" class="pull-right btn btn-konsul btn-primary" v-on:click="reply('<?php echo $comment->id ?>', '<?php echo $comment->name ?>')">Reply</a></h4>
                                    <p><?php echo $comment->content ?></p>

                                    <?php foreach ($comment->replies as $reply): ?>
                                    <div class="media" id="comment-<?php echo $reply->id ?>">
                                        <a class="media-left" href="#">
                                            <img class="media-object" src="<?php echo $reply->avatar ?>">
                                        </a>
                                        <div class="media-body">
                                            <div class="media-body-bg">
                                                <div class="meta">
                                                    <p><strong><?php echo $reply->name ?></strong></p>
                                                    <p><i class="fa fa-calendar"></i> <em><?php echo $reply->created_at->format('d F Y') ?></em></p>
                                                </div>
                                                <p><?php echo $reply->content ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; else: ?>
                <li><p class="alert alert-info">Belum ada review.</p></li>
            <?php endif ?>
            </ul>

            <p id="comment-reply">Add your reviews</p>
            <div class="form-review">
                <?php $this->load->view('course/course_comment', compact('course')); ?>                
            </div>
        </div>
    </div>
    <div class="related-course">
        <div class="related-course-title">
            <h3>KELAS TERKAIT</h3>
        </div>
        <div class="related-course-content">
            <div class="row">
                <?php foreach ($relevance->take(3) as $rel): ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="box-course">
                            <div class="box-course-img">
                                <img src="<?php echo $rel->thumbnail_image ?>" alt="">
                            </div>
                            <div class="box-course-title">
                                <h4><a href="<?php echo site_url('course/show/'.$rel->slug) ?>"><?php echo $rel->name ?></a></h4>
                                <a href="<?php echo site_url('course/join/'.$rel->slug) ?>" class="btn btn-start btn-sm btn-block">Mulai Kelas</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
