
<div class="content-main">
        
    <!-- start:section content main articles -->
    <section class="content-gallery-image">
        <div class="content-gallery-heading">
            <h3>GALLERY</h3>
        </div>
        <div class="content-gallery-main">
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper">
                        <div class="row">
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    <div class="gallery-box">
                                        <div class="gallery-box-img">
                                            <a href="<?php echo asset($image) ?>" data-lightbox="news1"><img src="<?php echo asset($image) ?>" alt=""></a>
                                        </div>
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

<?php custom_stylesheet() ?>
    <link rel="stylesheet" href="<?php echo asset('node_modules/lightbox2/dist/css/lightbox.css') ?>">
<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
    <script src="<?php echo asset('node_modules/lightbox2/dist/js/lightbox.js') ?>"></script>
<?php endcustom_script() ?>