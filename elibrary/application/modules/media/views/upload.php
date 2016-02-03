<div class="elib-content-single">
    <div class="elib-single-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="#">Media</a></li>
            <li class="active">Upload</li>
        </ol>
    </div>
    <?php
        if(isset($failed)){
            echo '<div class="alert alert-danger">';
                echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                echo '<strong>Warning!</strong> '.$failed;
            echo '</div>';
        }
    ?>
    <div class="title">
        <?php echo form_open_multipart('media/submit', array('id'=>'formMedia')); ?>
            <div class="form-group">
                <label>Kategori</label>
                <select class="form-control" name="kategori">
                    <?php 
                        foreach($categories AS $cat){
                            echo '<option value="'.$cat->id.'">'.$cat->name.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <p class="label label-info">Maximum Files 20MB</p>
                <input type="file" name="filemedia[]" id="filer_input_media" multiple="multiple">
                <p style="text-align:left; font-size:12px;"><b>Supported file format</b>:<br> jpg | jpeg | png | gif | pdf | rar  | zip | xlsx | docx | doc | xls |<br>
                 ppt | pptx | 3gp | mp4 | mpeg | mpg | webm | ogg | mkv | flv | mp3 | txt</p>
            </div>
            <button type="button" onclick="checkInput(); return false;" class="btn btn-danger" id="extrabutton">Start</button>
        <?php echo form_close(); ?>
    </div>
    <div class="description-meta">
    
    </div>
</div>

<!-- emd:content -->
<?php custom_stylesheet() ?>

    <link href="<?php echo asset('/plugins/jQuery.filer-1.0.5/css/jquery.filer.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo asset('/plugins/jQuery.filer-1.0.5/css/themes/jquery.filer-dragdropbox-theme.css') ?>" type="text/css" rel="stylesheet" />

<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
    <!--jQuery-->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="<?php echo asset('/plugins/jQuery.filer-1.0.5/js/jquery.filer.min.js?v=1.0.5') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('/javascript/jquery.filer.custom.js') ?>"></script>
    <script type="text/javascript">
        function checkInput(){
            if(document.getElementById('filer_input_media').value == ''){  
                alert('Anda harus memilih file untuk diunggah terlebih dahulu!');  
                document.getElementById('filer_input_media').focus();  
                return false;  
            }else{
                var count   = document.getElementsByClassName('fileName');
                var id      = '';
                for(var i=0;i<count.length;i++){
                    if(i>0){
                        id = i;
                    }
                    if(document.getElementById('fileName'+id).value == ''){  
                        alert('Nama file harus diisi terlebih dahulu!');  
                        document.getElementById('fileName'+id).focus();  
                        return false;  
                    }
                }
            }
            document.getElementById('formMedia').submit();
        }
    </script>
<?php endcustom_script() ?>
