<?php echo form_open_multipart('dashboard/sendArticle'); ?>

<div class="row content-submit" id="app-cropit">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <section class="content-articles">
            <div class="content-articles-heading">
                <h3>Kirim Artikel</h3>
            </div>
        </section>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">             
                <?php echo validation_errors(); ?>
                <fieldset class="form-group">
                    <label for="title">Judul Artikel</label>
                    <input name="title" type="text" class="form-control" id="title">
                    <small class="text-muted">Masukkan judul artikel disini</small>
                </fieldset>
                <fieldset class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control description-text" rows="4" name="description"></textarea>
                    <small class="text-muted">Maksimal 100 karakter</small>
                </fieldset>
                <fieldset class="form-group">
                    <textarea name="content" class="editor-simple"></textarea>
                </fieldset>
                <fieldset class="form-group hidden-sm-up">
                     <input type="file" name="filemedia" id="filer_input_img">
                </fieldset>
                <fieldset class="form-group hidden-sm-up">
                    <label for="">Keterangan gambar</label>
                    <input type="text" class="form-control" name="caption-img">
                </fieldset>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <!-- begin: category -->
                <div class="widget">
                    <div class="widget-sidebar-heading">
                        <h3>Category</h3>
                    </div>
                    <div class="widget-sidebar-content">
                        <?php echo $categories_checkbox ?>
                    </div>
                </div>
                <!-- end: category -->
                <!-- begin: image preview -->
                <div class="widget hidden-lg-down">
                    <div class="widget-sidebar-heading">
                        <h3>Gambar Fitur</h3>
                    </div>
                    <div class="widget-sidebar-content">
                        <cropit-preview name="featured" :show-description="true"></cropit-preview>
                        <cropit-result name="featured"></cropit-result>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('modal/featured'); ?>
</div>

<?php echo form_close(); ?>

<?php custom_stylesheet() ?>
    <link rel="stylesheet" href="<?php echo asset('stylesheets/cropit.css') ?>">
<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
    <?php $this->load->view('template/vue_cropit'); ?>
    <script src="<?php echo asset('plugins/tinymce/tinymce.jquery.min.js') ?>"></script>
    <script src="<?php echo asset('node_modules/cropit/dist/jquery.cropit.js') ?>"></script>
    <script src="<?php echo asset('node_modules/vue/dist/vue.min.js') ?>"></script>
    <script src="<?php echo asset('javascript/cropit.vue.js') ?>"></script>
    <script src="<?php echo asset('javascript/editor.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('/javascript/jquery.filer.custom.js') ?>"></script>

    <script type="text/javascript">
        $('.description-text').on('keyup', function() {
            limitText(this, 100)
        });

        function limitText(field, maxChar){
            var ref = $(field),
                val = ref.val();
            if ( val.length >= maxChar ){
                ref.val(function() {
                    console.log(val.substr(0, maxChar))
                    return val.substr(0, maxChar);       
                });
            }
        }

    </script>
    
<?php endcustom_script() ?>
