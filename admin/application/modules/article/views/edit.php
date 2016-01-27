<?php echo form_open_multipart('article/edit/' . $artikel->id); ?>

<div class="row" id="app-cropit">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <?php echo form_input('title', set_value('title', $artikel->title), array('class' => 'form-control input-lg', 'placeholder' => 'Title')); ?>
                </div>

                <div class="form-group">
                    <p class="text-static">
                        Link : <a href="<?php echo getLinkArticle($artikel) ?>" target="_blank"><?php echo getLinkArticle($artikel) ?></a>
                    </p>
                </div>

                <div class="form-group">
                    <?php echo form_textarea('description', set_value('description', $artikel->description), array('class' => 'form-control description-text','rows' => '4' ,'placeholder' => 'Description (Maksimal 250 karakter)')); ?>
                </div>

                <div class="form-group">
                    <?php echo form_textarea('content', set_value('content', $artikel->content, FALSE), array('class' => 'form-control editor-portal')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2><strong>Publish</strong></h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="type">Status</label>
                    <?php echo form_dropdown('status', $status, set_value('status', $artikel->status), array('class' => 'form-control')); ?>
                </div>
                <div class="form-group">
                    <label for="type">Only Registered?</label>
                    <div class="form-group">
                        <label class="switch switch-danger">
                            <?php echo form_checkbox('type', $artikel->id, $artikel->type == 'private', array('class' => 'switch-input ajax')); ?>
                            <span class="switch-label" data-on="Yes" data-off="No"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>                    
                </div>
                <div class="form-group">
                    <label for="published">Set Publish Datetime</label>
                    <a href="#" class="text-primary open-schedule">Set Schedule</a>
                    <?php if ($artikel->published == '0000-00-00 00:00:00'): ?>
                        <div class="input-group input-schedule" style="display: none;">
                    <?php else: ?>
                        <div class="input-group input-schedule">
                        <?php echo form_hidden('with_schedule', '1'); ?>
                    <?php endif ?>
                        <?php echo form_input('published', set_value('published', $artikel->published), array('class' => 'form-control input-sm datetimepicker', 'id' => 'published')); ?>
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm close-schedule">No schedule</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Update</button>
                <?php echo button_delete_lg('article/delete/'.$artikel->id, 'md') ?>
            </div>
        </div>

        <?php if ($artikel->isPublished()): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Pilihan Editor</h4>
                </div>
                <div class="panel-body">                
                    <?php if ($artikel->editor_choice): ?>
                        <a href="<?php echo site_url('article/unchoice/'.$artikel->id) ?>" class="btn btn-default"><i class="fa fa-star"></i> Hapus dari Pilihan Editor</a>
                    <?php else: ?>                
                        <a href="<?php echo site_url('article/choice/'.$artikel->id) ?>" class="btn btn-primary"><i class="fa fa-star"></i> Jadikan sebagai Pilihan Editor</a>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Category</h4>
            </div>
            <div class="panel-body">
                <?php echo $categories_checkbox ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Tags</h4>
            </div>
            <div class="panel-body">
                <?php echo form_dropdown('tags[]', $tags, $tags, array('class' => 'form-control input-tags', 'multiple' => true)) ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Carousel Slider</h4>
            </div>
            <div class="panel-body">
                <cropit-preview name="slider" width="275px" height="140px" image-src="<?php echo $artikel->slider_image ?>"></cropit-preview>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Featured Image</h4>
            </div>
            <div class="panel-body">
                <cropit-preview name="featured" width="275px" height="140px" image-src="<?php echo $artikel->featured_image ?>" :show-description="true" :input-description="true" description="<?php echo $artikel->featured_description ?>"></cropit-preview>
            </div>
        </div>
    </div>

    <cropit-result name="featured" description="<?php echo $artikel->featured_description ?>"></cropit-result>
    <cropit-result name="slider"></cropit-result>

    <?php $this->load->view('modal/featured'); ?>
    <?php $this->load->view('modal/slider'); ?>
</div>

<?php echo form_close(); ?>

<?php custom_stylesheet() ?>
    <link rel="stylesheet" type="text/css" href="<?php echo asset('stylesheets/cropit.css') ?>">
<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
    <?php $this->load->view('template/vue_cropit'); ?>
    <script src="<?php echo asset('node_modules/vue/dist/vue.min.js') ?>"></script>
    <script src="<?php echo asset('node_modules/cropit/dist/jquery.cropit.js') ?>"></script>
    <script src="<?php echo asset('javascript/cropit.vue.js') ?>"></script>
    <script>
    $(document).ready(function () {
        $('.switch-input.ajax').on('change', function () {
            var id      = $(this).val();
            var type    = this.checked ? 'private' : 'public';

            $.ajax({
                url: siteurl + '/article/json/type',
                data: {
                    id: id,
                    type: type,
                },
                success: function (response) {
                    alert('Artikel telah diperbarui visibilitas menjadi '+type)
                },
                error: function (response) {
                    //
                }
            })
        })
    })
    </script>
    
    <script type="text/javascript">
        $('.description-text').on('keyup', function() {
            limitText(this, 250)
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
