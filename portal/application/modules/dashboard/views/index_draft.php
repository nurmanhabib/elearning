
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <section class="content-articles">
            <div class="content-articles-heading">
                <h3>Dashboard Post Articles</h3>
            </div>
        </section> 
        <div class="container content-submit">
            <a class="btn btn-primary" href="<?php echo site_url('dashboard/sendArticle') ?>"><i class="fa fa-plus"></i> Submit Artikel</a>
            <div class="widget">
                <div class="widget-heading">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                             <a class="nav-link active" id="#" data-toggle="tab" href="#article-submit" role="tab" aria-controls="article-approve" aria-expanded="true">Artikel Submit</a>
                        </li>
                    </ul>
                </div>
                <div class="widget-content">
                    <div class="tab-content" id="myTabTableContent">
                        <div role="tabpanel" class="tab-pane fade active in" id="article-submit" aria-expanded="true">
                            <table id="post-articles" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul</th>
                                        <th>Status</th>
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
                                            <td><?php echo $article->date->format('d F Y H:i') ?></td>
                                            <td>
                                                <a href="<?php echo site_url('dashboard/editArticle/'.$article->id) ?>" class="btn btn-primary btn-konsul">Update</a>
                                                <a href="<?php echo site_url('dashboard/delete/'.$article->id) ?>" class="btn btn-danger btn-delete btn-konsul">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php custom_stylesheet() ?>

    <link rel="stylesheet" href="<?php echo asset('node_modules/datatables/media/css/jquery.dataTables.min.css') ?>">

<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
 <!--jQuery-->

<script type="text/javascript" src="<?php echo asset('/plugins/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#post-articles').dataTable();
    });
</script>
<?php endcustom_script() ?>
