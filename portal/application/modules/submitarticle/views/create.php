<?php echo show_message() ?>

<section class="content-articles">
    <div class="content-articles-heading">
        <h3>Kirim Artikel</h3>
    </div>
</section>

<div class="content-articles-content">
    <fieldset class="form-group">
        <div class="row">
            <div class="col-md-4">
                <label>Foto Pengirim</label>
                <cropit-preview name="customavatar" :width="192" :height="192" image-empty="<?php echo asset('images/default_avatar_male.jpg') ?>">
                    <button type="button" class="btn btn-danger btn-margin-btm" v-on:click="remove('customavatar')" slot="button-remove"><i class="fa fa-trash-o"></i></button>
                </cropit-preview>
            </div>
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <?php echo form_input('nama', set_value('nama'), ['class' => 'form-control', 'id' => 'nama']) ?>
                </fieldset>
                
                <fieldset class="form-group">
                    <label for="email">Email</label>
                    <?php echo form_input('email', set_value('email'), ['class' => 'form-control', 'id' => 'email']) ?>
                </fieldset>
                
                <fieldset class="form-group">
                    <label for="provinsi">Propinsi</label>
                    <?php echo $this->wilayah->generateSelectProvinsi() ?>
                </fieldset>
                
                <fieldset class="form-group">
                    <label for="kota">Kota/Kabupaten</label>
                    <?php echo $this->wilayah->generateSelectKota() ?>
                </fieldset>

                <fieldset class="form-group">
                    <label for="kecamatan">Kecamatan</label>
                    <?php echo $this->wilayah->generateSelectKecamatan() ?>
               </fieldset>

                <fieldset class="form-group">
                    <label for="desa">Desa</label>
                    <?php echo $this->wilayah->generateSelectDesa() ?>
                </fieldset>
            </div>
        </div>   
    </fieldset>
    
    <fieldset class="form-group">
        <label for="title">Judul Artikel</label>
        <input type="text" name="title" class="form-control" id="title">
    </fieldset>
    <fieldset class="form-group">
        <label>Deskripsi</label>
        <textarea class="form-control description-text" rows="4" name="description"></textarea>
        <small class="text-muted">Maksimal 100 karakter</small>
    </fieldset>
    <fieldset class="form-group">
        <textarea name="content" class="editor-simple"></textarea>
    </fieldset>
</div>

<section class="content-articles">
    <div class="content-articles-heading">
        <h3>Tahap Moderasi</h3>
    </div>
</section>

<div class="content-articles-content">
    <p>Setiap artikel yang Anda kirim akan masuk ke tahap moderasi terlebih dahulu.</p>
    <fieldset class="form-group">
        <button id="asd" type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Kirim Artikel</button>
    </fieldset>
</div>

<cropit-result name="featured"></cropit-result>
<cropit-result name="customavatar"></cropit-result>

<?php custom_stylesheet() ?>
    <link rel="stylesheet" href="<?php echo asset('stylesheets/cropit.css') ?>">
<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
    <script src="<?php echo asset('node_modules/jquery-chained/jquery.chained.remote.js') ?>"></script>
    <script src="<?php echo asset('javascript/editor.js') ?>"></script>
    <?php echo $this->wilayah->script('api/wilayah') ?>

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
