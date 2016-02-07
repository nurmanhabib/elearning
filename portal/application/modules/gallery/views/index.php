<div class="content-main">
        
    <!-- start:section content main articles -->
    <section class="content-gallery">
        <div class="content-gallery-heading">
            <h3>GALLERY</h3>
        </div>
        <div class="content-gallery-main">
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper">
                        <div class="row">
                            <?php foreach ($folders as $name): ?>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="gallery-box">
                                        <div class="gallery-box-img">
                                            <div class="box-icon">
                                                <i class="fa fa-folder-o"></i>
                                            </div>
                                        </div>
                                        <div class="gallery-box-title">
                                            <h3><?php echo $name ?></h3>
                                        </div>
                                        <a href="<?php echo site_url('gallery/show/' . $name) ?>" class="btn btn-primary btn-block">VIEW</a>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end:section content main articles -->

</div>