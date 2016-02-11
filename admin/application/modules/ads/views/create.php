<?php echo form_open_multipart('ads/create'); ?>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="code" class="control-label">Kode</label>
                    <input type="text" name="code" id="inputCode" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name" class="control-label">Judul Iklan</label>
                    <input type="text" name="name" id="inputName" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2><strong>Save</strong></h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="type">Tampil</label>
                    <?php echo form_dropdown('show', ['Tidak', 'Ya'], 0, array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>