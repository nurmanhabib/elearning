
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <section class="content-articles">
            <div class="content-articles-heading">
                <h3>Dashboard Post Articles</h3>
            </div>
        </section> 
        <div class="container content-submit">
            <a class="btn btn-primary" href="<?php echo site_url('dashboard/sendArticle') ?>"><i class="fa fa-plus"></i> Submit Artikel</a><hr>
            <div class="table-responsive">
                <table id="post-articles" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?php echo $article->id ?></td>
                                <td><?php echo $article->title ?></td>
                                <td><?php echo $article->getStatusLabel() ?></td>
                                <td><?php echo $article->getTypeLabel() ?></td>
                                <td><?php echo $article->date->format('d F Y H:i') ?></td>
                                <td>
                                    <a href="<?php echo site_url('dashboard/editArticle/'.$article->id) ?>" class="btn btn-primary btn-konsul">Update</a>
                                    <a href="<?php echo site_url('dashboard/delete/'.$article->id) ?>" class="btn btn-danger btn-konsul btn-margin-btm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><br><br>

<?php custom_stylesheet() ?>

    <link rel="stylesheet" href="<?php echo asset('node_modules/datatables/media/css/jquery.dataTables.min.css') ?>">

<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
 <!--jQuery-->

<script type="text/javascript" src="<?php echo asset('/plugins/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#post-articles').dataTable({
            responsive: true,
            "sDom": '<"row"<"col-lg-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>'
        });
    });
</script>
<?php endcustom_script() ?>
