<div class="sidebar-kelas-online">
    <div class="widget">
        <div class="widget-categories">
            <div class="widget-categories-heading">
                <h4>SETTING</h4>
            </div>
            <div class="widget-categories-content">
                <div class="list-group">
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id) ?>" class="list-group-item <?php echo $sidebar_active == 'basic' ? 'active' : '' ?>">
                        Basic Information
                    </a>
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/requirement') ?>" class="list-group-item <?php echo $sidebar_active == 'requirement' ? 'active' : '' ?>">
                        <span class="label label-default label-pill pull-xs-right"><?php echo $repository->countRequirements() ?></span>
                        Requirement
                    </a>
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/image') ?>" class="list-group-item <?php echo $sidebar_active == 'image' ? 'active' : '' ?>">
                        <?php if (!$repository->hasFeaturedImage()): ?>
                            <span class="text-danger pull-xs-right"><i class="fa fa-fw fa-warning"></i></span>
                        <?php endif ?>
                        Image
                    </a>
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/chapter') ?>" class="list-group-item <?php echo $sidebar_active == 'chapter' ? 'active' : '' ?>">Chapter and Quiz
                        <?php if ($count = $repository->countChapters()): ?>
                            <span class="label label-default label-pill pull-xs-right"><?php echo $count ?></span>
                        <?php else: ?>
                            <span class="text-danger pull-xs-right"><i class="fa fa-fw fa-warning"></i></span>
                        <?php endif ?>
                    </a>
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/exam') ?>" class="list-group-item <?php echo $sidebar_active == 'exam' ? 'active' : '' ?>">Exam
                        <?php if ($count = $repository->countExams()): ?>
                            <span class="label label-default label-pill pull-xs-right"><?php echo $count ?></span>
                        <?php else: ?>
                            <span class="text-danger pull-xs-right"><i class="fa fa-fw fa-warning"></i></span>
                        <?php endif ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="widget">
        <div class="widget-categories">
            <div class="widget-categories-heading">
                <h4>REVIEWS</h4>
            </div>
            <div class="widget-categories-content">
                <div class="list-group">
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/review?status=draft') ?>" class="list-group-item <?php echo $sidebar_active == 'review_draft' ? 'active' : '' ?>">
                        <span class="label label-default label-pill pull-xs-right"><?php echo $repository->countNewReviews() ?></span>
                        New Review
                    </a>
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/review') ?>" class="list-group-item <?php echo $sidebar_active == 'review' ? 'active' : '' ?>">
                        <span class="label label-default label-pill pull-xs-right"><?php echo $repository->countReviews() ?></span>
                        All Review
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="widget">
        <div class="widget-categories">
            <div class="widget-categories-heading">
                <h4>COMMENT</h4>
            </div>
            <div class="widget-categories-content">
                <div class="list-group">
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/comment?status=draft') ?>" class="list-group-item <?php echo $sidebar_active == 'comment_draft' ? 'active' : '' ?>">
                        <span class="label label-default label-pill pull-xs-right"><?php echo $repository->countNewChapterComment() ?></span>
                        New Comment
                    </a>
                    <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/comment') ?>" class="list-group-item <?php echo $sidebar_active == 'comment' ? 'active' : '' ?>">
                        <span class="label label-default label-pill pull-xs-right"><?php echo $repository->countChapterComment() ?></span>
                        All Comment
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php if ($course->status == 'draft'): ?>
        <div class="widget">
            <div class="widget-categories">
                <div class="widget-categories-heading">
                    <h4>MEMBER</h4>
                </div>
                <div class="widget-categories-content">
                    <div class="alert alert-warning">
                        <p>This course is still in moderation</p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="widget">
            <div class="widget-categories">
                <div class="widget-categories-heading">
                    <h4>MEMBER</h4>
                </div>
                <div class="widget-categories-content">
                    <div class="list-group">
                        <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/member?status=active') ?>" class="list-group-item">
                            <span class="label label-info label-pill pull-xs-right"><?php echo $repository->countMemberActive() ?></span>
                            Active
                        </a>
                        <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/member?status=finished') ?>" class="list-group-item">
                            <span class="label label-success label-pill pull-xs-right"><?php echo $repository->countMemberFinished() ?></span>
                            Finished
                        </a>
                        <a href="<?php echo site_url('dashboard/course/edit/'.$course->id.'/member?status=pending') ?>" class="list-group-item list-group-item-warning">
                            <span class="label label-warning label-pill pull-xs-right"><?php echo $repository->countMemberPending() ?></span>
                            Pending
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div class="widget">
        <div class="widget-categories">
            <div class="widget-categories-content">
                <p><a href="<?php echo site_url('dashboard/course/delete/' . $course->id) ?>" class="btn btn-delete-lg btn-danger"><i class="fa fa-trash"></i> Delete This Course</a></p>
            </div>
        </div>
    </div>
</div>