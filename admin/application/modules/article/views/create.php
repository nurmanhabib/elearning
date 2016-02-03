<?php echo form_open_multipart('article/add'); ?>

<div class="row" id="app-cropit">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo validation_errors(); ?>
                <div class="form-group">
                    <?php echo form_input('title', set_value('title'), array('class' => 'form-control input-lg', 'placeholder' => 'Title')); ?>
                </div>

                <div class="form-group">
                    <?php echo form_textarea(array(
                        'value'         => set_value('description', $artikel->description),
                        'name'          => 'description',
                        'class'         => 'form-control description-text',
                        'rows'          => 2,
                        'placeholder'   => 'Text intro (Maksimal 100 karakter)'
                    )); ?>
                </div>

                <div class="form-group">
                    <?php echo form_textarea('content', set_value('content', '', FALSE), array('class' => 'form-control editor-portal')); ?>
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
                    <?php echo form_dropdown('status', $status, 'publish', array('class' => 'form-control')); ?>
                </div>
                <div class="form-group">
                    <label for="type">Only Registered?</label>
                    <div class="form-group">
                        <label class="switch switch-danger">
                            <?php echo form_checkbox('private', 1, FALSE, array('class' => 'switch-input')); ?>
                            <span class="switch-label" data-on="Yes" data-off="No"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>                    
                </div>
                <div class="form-group">
                    <label for="published">Set Publish Datetime</label>
                    <a href="#" class="text-primary open-schedule">Set Schedule</a>
                    <div class="input-group input-schedule" style="display: none;">
                        <?php echo form_input('published', date('Y-m-d H:i:s'), array('class' => 'form-control input-sm datetimepicker', 'id' => 'published')); ?>
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm close-schedule">No schedule</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>

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
                <?php echo form_dropdown('tags[]', array(), array(), array('class' => 'form-control input-tags', 'multiple' => true)) ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Carousel Slider</h4>
            </div>
            <div class="panel-body">
                <cropit-preview name="slider" width="275px" height="140px"></cropit-preview>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Featured Image</h4>
            </div>
            <div class="panel-body">
                <cropit-preview name="featured" width="275px" height="140px" :show-description="true"></cropit-preview>
            </div>
        </div>
    </div>

    <cropit-result name="featured"></cropit-result>
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
