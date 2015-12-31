<div class="panel panel-default">
	<?php echo form_open_multipart('elibrary/submit', array('id'=>'formMedia')); ?>
	    <div class="panel-body">
		    <label>Kategori</label>
		        <select class="form-control" name="kategori">
		            <?php 
		                foreach($categories AS $cat){
		                    echo '<option value="'.$cat->id.'">'.$cat->name.'</option>';
		                }
		            ?>
		        </select>
	    </div>
	    <div class="panel-body">
	        <input type="file" name="filemedia[]" id="filer_input_media" multiple="multiple">
	    
	        <button class="btn btn-danger" onclick="checkInput(); return false;" id="extrabutton">Start</button>
	    </div>
	    <?php echo form_close(); ?>
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
                    if(document.getElementsByName('fileName'+id).value == ''){  
                        alert('Nama file harus diisi terlebih dahulu!');  
                        document.getElementsByName('fileName'+id).focus();  
                        return false;  
                    }
                }
            }
            document.getElementById('formMedia').submit();
        }
    </script>

<?php endcustom_script() ?>
