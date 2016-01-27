<div class="row">
    <div class="col-md-4">
        <?php echo form_open('forum/category/create'); ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Tambah Kategori Forum</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name" class="control-label">Nama</label>
                    <?php echo form_input('name', set_value('name'), ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <label for="parent" class="control-label">Tenaga Ahli / Moderator</label>
                    <?php echo form_multiselect('tenagaahli[]', $users, '', array('class' => 'form-control select2')); ?>
                </div>
            </div>
            <div class="panel-footer">
                <?php echo button_save() ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
        <?php if ($categories->count()): ?>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Tenaga Ahli</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($categories->count()): ?>
                                <?php foreach ($categories as $category): ?>
                                    <?php $i = 0; foreach ($category->users as $tenagaahli): ?>
                                        <tr>
                                            <?php if ($i == 0): ?>
                                                <td rowspan="<?php echo $category->users->count() ?>"><?php echo $category->name ?></td>
                                                <td><?php echo $tenagaahli->full_name ?><br>
                                                <small><?php echo $tenagaahli->email ?></small></td>
                                                <td rowspan="<?php echo $category->users->count() ?>"><?php echo button_edit('forum/category/edit/'.$category->id) ?> <?php echo button_delete('forum/category/delete/'.$category->id) ?></td>
                                            <?php else: ?>
                                                <td><?php echo $tenagaahli->full_name ?><br>
                                                <small><?php echo $tenagaahli->email ?></small></td>
                                            <?php endif ?>
                                        </tr>
                                    <?php $i++; endforeach ?>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr class="warning">
                                    <td colspan="3">Tidak ada kategori yang ditampilkan</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
                <nav class="pull-right">
                    <ul>
                        <?php echo $categories->render() ?>
                    </ul>
                </nav>
            </div>
        <?php else: ?>
            <p class="alert alert-warning">Belum ada data</p>
        <?php endif ?>
        </div>
    </div>
</div>

<?php custom_script() ?>
    <script>
    $(document).ready(function () {
        $('select.select2').select2()
    })
    </script>
<?php endcustom_script() ?>