<div id="app-cropit">
    <div class="row" id="app-kelas-online">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <section class="content-articles">
                <div class="content-articles-heading">
                    <h3>Dashboard Kelas Online</h3>
                </div>
            </section>
            <div class="card">
                <div class="card-block">
                    <?php echo form_open('', 'id="wizard-create-form" onsubmit="return false;"'); ?>
                    <div id="wizard">
                        <h2><label class="hidden-xs-down">Overview</label></h2>
                        <section>
                           <div class="card card-block">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 form-control-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" placeholder="Judul" v-model="course.name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="category" class="col-sm-2 form-control-label">Category</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="category" v-model="course.category_id" required>
                                            <?php foreach ($category_lists as $value => $text): ?>
                                                <option value="<?php echo $value ?>"><?php echo $text ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="days" class="form-control-label col-sm-2">Long Course</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" name="days" class="form-control" v-model="course.days">
                                            <span class="input-group-addon">
                                                days
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control editor-description required" v-model="course.description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <h2><label class="hidden-xs-down">Images</label></h2>
                        <section>
                            <div class="card">
                                <div class="card-block text-xs-center">
                                    <h4>Featured Image</h4>
                                    <cropit-preview name="featured" width="auto" height="145px" image-empty="<?php echo asset('images/kelas_online/thumbnails-lg.jpg') ?>"></cropit-preview>
                                </div><hr>
                                <div class="card-block text-xs-center">
                                    <h4>Thumbnail</h4>
                                    <cropit-preview name="thumbnail" width="auto" height="90px" image-empty="<?php echo asset('images/kelas_online/thumbnails-md.jpg') ?>"></cropit-preview>
                                </div>
                            </div>
                        </section>
                        <h2><label class="hidden-xs-down">Chapter and Quiz</label></h2>
                        <section>
                            <div id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default" v-for="chapter in course.chapters">
                                    <div class="panel-heading" role="tab" id="Chapter1">
                                        <h5 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapseOne">
                                                <p class="font-weight-bold text-uppercase"><span class="text-danger" title="Belum ada quiz" v-show="chapter.quiz.questions.length == 0"><i class="fa fa-exclamation-triangle"></i></span> Chapter {{ $index + 1 }} : {{ chapter.name }}</p>
                                            </a>
                                            <div class="btn-kelas pull-right">
                                                <button class="btn btn-info btn-kelas" data-toggle="modal" data-target=".add_chapter" v-on:click="editChapter($index)" title="Edit"><i class="fa fa-pencil-square-o"></i></button>
                                                <button class="btn btn-danger btn-kelas" data-toggle="tooltip" data-placement="top" title="Delete" v-on:click="removeChapter(chapter)"><i class="fa fa-times"></i></button>
                                            </div>
                                        </h5>
                                    </div>
                                    <div id="collapse{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Chapter{{ $index }}">
                                        <div class="container">
                                            <!-- Start: Deskripsi-->
                                                <div class="card">
                                                    <div class="card-header">
                                                         <p>Deskripsi : </p>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="form-group row">
                                                            <label for="deskripsi" class="col-sm-12 form-control-label">{{ chapter.content }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- End: Deskripsi -->
                                            <!-- Start: Attachment -->
                                                <div class="card">
                                                    <div class="card-header">
                                                         <p>Attachment : <button class="btn btn-exam" data-toggle="modal" data-target=".add-content" v-on:click="addChapterAttachment($index)"><i class="fa fa-paperclip fa-sw"></i> Add Content</button></p>
                                                    </div>
                                                    <div class="card-block">
                                                        <!-- Start: Table Attachment -->
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Name</th>
                                                                        <th>Type</th>
                                                                        <th>Size</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="content in attachments[$index].contents">
                                                                        <th scope="row">{{ $index+1 }}</th>
                                                                        <td>{{ content.filename }}</td>
                                                                        <td>{{ content.filetype }}</td>
                                                                        <td>{{ content.filesize }}</td>
                                                                        <td>
                                                                            <a class="btn btn-konsul btn-danger" title="Delete" v-on:click="removeChapterContent($index, $parent.$index)"><i class="fa fa-trash-o"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr v-show="attachments[$index].contents.length == 0">
                                                                        <td colspan="5" class="text-xs-center">Tidak ada attachment</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- End: Table Attachment -->
                                                    </div>
                                                </div>
                                            <!-- End: Attachment -->
                                            <!-- Start: Quiz -->
                                                <div class="card">
                                                    <div class="card-header">
                                                         <p>Quiz : <button class="btn btn-exam" data-toggle="modal" data-target=".add-question" v-on:click="addChapterQuiz($index)"><i class="fa fa-paperclip fa-sw"></i> Add Question</button></p>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="form-group row">
                                                            <label for="waktu" class="col-sm-2 form-control-label">Waktu</label>
                                                            <div class="input-group col-sm-4">
                                                                <input type="text" class="form-control" id="waktu" placeholder="Waktu" v-model="chapter.quiz.time">
                                                                <div class="input-group-addon">Menit</div>
                                                            </div>
                                                        </div>
                                                        <!-- Start: Table Queations -->
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                      <th>No</th>
                                                                      <th>Questions</th>
                                                                      <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="quiz in chapter.quiz.questions">
                                                                        <th scope="row">{{ $index+1 }}</th>
                                                                        <td>{{{ quiz.question }}}</td>
                                                                        <td>
                                                                            <a class="btn btn-konsul btn-info btn-margin-btm" data-toggle="modal" data-target=".add-question" v-on:click="editChapterQuiz($index, $parent.$index)"><i class="fa fa-pencil-square-o"></i></a>
                                                                            <a class="btn btn-konsul btn-danger btn-margin-btm" v-on:click="removeChapterQuiz($index, $parent.$index)"><i class="fa fa-trash-o"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr v-show="chapter.quiz.questions.length == 0">
                                                                        <td colspan="3" class="text-xs-center">Tidak ada quiz</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- End: Table Questions -->
                                                    </div>
                                                </div>
                                            <!-- End: Quiz -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel">
                                <div class="panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target=".add_chapter" v-on:click="addChapter">Add Chapter</button>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <h2><label class="hidden-xs-down">Exam</label></h2>
                        <section>
                            <div class="card">
                                <div class="card-header">
                                    <p>Exam <button class="btn btn-exam" data-toggle="modal" data-target=".add-exam"><i class="fa fa-paperclip"></i> Add Question</button></p>
                                </div>
                                <div class="card-block">
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Standar Kelulusan</label>
                                        <div class="input-group col-sm-4">
                                            <input type="text" class="form-control" placeholder="Standar Kelulusan" v-model="course.passing_standards">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="waktu" class="col-sm-2 form-control-label">Waktu</label>
                                        <div class="input-group col-sm-4">
                                            <input type="text" class="form-control" placeholder="Waktu" v-model="course.exam.time">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <!-- Start: Table Queations -->
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                  <th>No</th>
                                                  <th>Questions</th>
                                                  <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="question in course.exam.questions">
                                                    <th scope="row">{{ $index+1 }}</th>
                                                    <td>{{{ question.question }}}</td>
                                                    <td>
                                                        <a class="btn btn-konsul btn-info btn-margin-btm" data-toggle="modal" data-target=".add-exam" title="Edit" v-on:click="editExamQuestion($index)"><i class="fa fa-pencil-square-o"></i></a>
                                                        <a class="btn btn-konsul btn-danger btn-margin-btm" title="Delete" v-on:click="removeExamQuestion($index)"><i class="fa fa-trash-o"></i></a>
                                                    </td>
                                                </tr>
                                                <tr v-show="course.exam.questions.length == 0" class="warning">
                                                    <td colspan="3">Anda belum memasukkan ujian</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End: Table Questions -->
                                </div>
                            </div>
                        </section>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <?php echo form_open('dashboard/course/submit', ['id' => 'form-course-result']); ?>
        <div id="course-result">
            <input type="hidden" name="course[name]" value="{{ course.name }}">
            <input type="hidden" name="course[description]" value="{{ course.description }}">
            <input type="hidden" name="course[days]" value="{{ course.days }}">
            <input type="hidden" name="course[passing_standards]" value="{{ course.passing_standards }}">
            <cropit-result name="featured"></cropit-result>
            <cropit-result name="thumbnail"></cropit-result>
            <input type="hidden" name="course[tags]" value="{{ course.tags }}">
            <input type="hidden" name="course[category_id]" value="{{ course.category_id }}">
            <div v-for="chapter in course.chapters">
                <input type="hidden" name="course[chapters][{{ $index }}][name]" value="{{ chapter.name }}">
                <input type="hidden" name="course[chapters][{{ $index }}][order]" value="{{ $index+1 }}">
                <input type="hidden" name="course[chapters][{{ $index }}][content]" value="{{ chapter.content }}">

                <input type="hidden" name="course[chapters][{{ $index }}][quiz][name]" value="{{ chapter.quiz.name }}">
                <input type="hidden" name="course[chapters][{{ $index }}][quiz][time]" value="{{ chapter.quiz.time }}">
                <div v-for="quiz in chapter.quiz.questions">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][quiz][questions][{{ $index }}][question]" value="{{ quiz.question }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][quiz][questions][{{ $index }}][option_a]" value="{{ quiz.option_a }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][quiz][questions][{{ $index }}][option_b]" value="{{ quiz.option_b }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][quiz][questions][{{ $index }}][option_c]" value="{{ quiz.option_c }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][quiz][questions][{{ $index }}][option_d]" value="{{ quiz.option_d }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][quiz][questions][{{ $index }}][correct]" value="{{ quiz.correct }}">
                </div>
            </div>
            <div v-for="attachment in attachments">
                <div v-for="content in attachment.contents">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][filename]" value="{{ content.filename }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][filetype]" value="{{ content.filetype }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][filesize]" value="{{ content.filesize }}">
                    <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][dataurl]" value="{{ content.dataurl }}">
                </div>
            </div>

            <input type="hidden" name="course[exam][name]" value="{{ course.exam.name }}">
            <input type="hidden" name="course[exam][time]" value="{{ course.exam.time }}">
            <div v-for="exam in course.exam.questions">
                <input type="hidden" name="course[exam][questions][{{ $index }}][question]" value="{{ exam.question }}">
                <input type="hidden" name="course[exam][questions][{{ $index }}][option_a]" value="{{ exam.option_a }}">
                <input type="hidden" name="course[exam][questions][{{ $index }}][option_b]" value="{{ exam.option_b }}">
                <input type="hidden" name="course[exam][questions][{{ $index }}][option_c]" value="{{ exam.option_c }}">
                <input type="hidden" name="course[exam][questions][{{ $index }}][option_d]" value="{{ exam.option_d }}">
                <input type="hidden" name="course[exam][questions][{{ $index }}][correct]" value="{{ exam.correct }}">
            </div>
        </div>
        <?php echo form_close(); ?>

        <!-- Begin: add chapter -->
        <?php $this->load->view('modal/add_chapter'); ?>
        <!-- End: add Chapter -->

        <!-- Begin: Add Content -->
        <?php $this->load->view('modal/add_content'); ?>
        <!-- End: Add Content -->

        <!-- Begin: Add Question -->
        <?php $this->load->view('modal/add_question'); ?>
        <!-- End: Add Question -->

        <!-- Begin: Add Exam -->
        <?php $this->load->view('modal/add_exam'); ?>
        <!-- End: Add Exam -->
    </div>

    <?php $this->load->view('modal/featured'); ?>
    <?php $this->load->view('modal/thumbnail'); ?>
</div>

<?php custom_stylesheet() ?>
    <link rel="stylesheet" href="<?php echo asset('stylesheets/custom.jquery.steps.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('plugins/jquery.steps-1.1.0/css/jquery.steps.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('plugins/jQuery.filer-1.0.5/css/jquery.filer.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('plugins/jQuery.filer-1.0.5/css/themes/jquery.filer-dragdropbox-theme.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('stylesheets/cropit.css') ?>">
<?php endcustom_stylesheet() ?>

<?php custom_script() ?>
    <?php echo $this->load->view('template/vue_course_form'); ?>
    <?php echo $this->load->view('template/vue_cropit'); ?>
    
    <script src="<?php echo asset('plugins/tinymce/tinymce.min.js') ?>"></script>
    <script src="<?php echo asset('node_modules/vue/dist/vue.min.js') ?>"></script>
    <script src="<?php echo asset('node_modules/cropit/dist/jquery.cropit.js') ?>"></script>
    <script src="<?php echo asset('node_modules/jquery-form/jquery.form.js') ?>"></script>
    <script src="<?php echo asset('plugins/jquery-validation-1.14.0/dist/jquery.validate.min.js') ?>"></script>
    <script src="<?php echo asset('javascript/kelas.wizard.js') ?>"></script>
    <script src="<?php echo asset('javascript/kelas.vue.js') ?>"></script>
    <script src="<?php echo asset('javascript/cropit.vue.js') ?>"></script>
    <script src="<?php echo asset('plugins/jQuery.filer-1.0.5/js/jquery.filer.min.js') ?>"></script>
    <script src="<?php echo asset('plugins/jQuery.filer-1.0.5/js/custom.js') ?>"></script>
    <script src="<?php echo asset('plugins/jquery.steps-1.1.0/js/jquery.steps.js') ?>"></script>
    <script src="<?php echo asset('plugins/tinymce/tinymce.jquery.min.js') ?>"></script>
<?php endcustom_script() ?>
