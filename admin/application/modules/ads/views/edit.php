<?php echo form_open_multipart('ads/edit/'.$ads->code); ?>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="code" class="control-label">Kode</label>
                    <p class="form-control-static"><?php echo $ads->code ?></p>
                </div>
                <div class="form-group">
                    <label for="name" class="control-label">Judul Iklan</label>
                    <p class="form-control-static"><?php echo $ads->name ?></p>
                </div>
                <div class="form-group">
                    <label for="name" class="control-label">Ukuran</label>
                    <p class="form-control-static"><?php echo $ads->size ?> pixels</p>
                </div>
                <div class="form-group">
                    <label for="name" class="control-label">Link</label>
                    <input type="url" name="link" id="inputLink" class="form-control" value="<?php echo $ads->link ?>" required="required" title="">
                </div>
                <div class="form-group">
                    <label for="image" class="control-label">Gambar Iklan</label>
                    <input type="file" name="image" accept="image/*">
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
                    <?php echo form_dropdown('show', ['Tidak', 'Ya'], $ads->show, array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>