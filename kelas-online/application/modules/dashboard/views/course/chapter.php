
<ol class="breadcrumb">
    <li><a href="<?php echo dashboard_url() ?>">Dashboard</a></li>
    <li><a href="<?php echo site_url('dashboard') ?>">Kelas Online</a></li>
    <li><a href="<?php echo site_url('dashboard/course/edit/'.$course->id) ?>"><?php echo $course->name ?></a></li>
    <li class="active">Chapter &amp; Quiz</li>
</ol>

<div class="kelas-main" id="app-kelas-online">
    <div class="card">
        <div class="card-block">
           <!-- start: content -->
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default" v-for="chapter in course.chapters">
                    <div class="panel-heading" role="tab">
                        <h5 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapseOne">
                                <p class="font-weight-bold">Chapter {{ $index + 1 }} : {{ chapter.name }}</p>
                            </a>
                            <div class="btn-kelas pull-right">
                                <button class="btn btn-info btn-kelas" data-toggle="modal" title="Edit" data-target=".add_chapter" v-on:click="editChapter($index)"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btn-kelas" data-toggle="tooltip" data-placement="top" title="Delete" v-on:click="removeChapter(chapter)"><i class="fa fa-times"></i></button>
                            </div>
                        </h5>
                    </div>
                    <div id="collapse{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Chapter{{ $index }}">
                        <div class="container">
                            <!-- Start: Deskripsi-->
                                    <div class="card">
                                        <div class="card-header">
                                             <p>Deskripsi :</p>
                                        </div>
                                        <div class="card-block">
                                            <div class="form-group">
                                                <p>{{ chapter.content }}</p>
                                            </div>
                                        </div>
                                    </div>
                            <!-- End: Deskripsi -->
                            <!-- Start: Attachment -->
                                <div class="card">
                                    <div class="card-header">
                                         <p>Attachment : <button class="btn btn-exam" data-toggle="modal" data-target=".add-content" v-on:click="addChapterAttachment($index)"><i class="fa fa-paperclip"></i> Add Content</button></p>
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
                                                        <td class="text-xs-center">
                                                            <a class="btn btn-sm btn-danger" title="Delete" v-on:click="removeChapterContent($index, $parent.$index)"><i class="fa fa-trash-o"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr v-show="attachments[$index].contents.length == 0">
                                                        <td colspan="5">Tidak ada attachment</td>
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
                                         <p>Quiz : <button class="btn btn-exam" data-toggle="modal" data-target=".add-question" v-on:click="addChapterQuiz($index)"><i class="fa fa-paperclip"></i> Add Question</button></p>
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
                                                        <td class="text-xs-center">
                                                            <a class="btn btn-sm btn-info btn-margin-btm" data-toggle="modal" data-target=".add-question" v-on:click="editChapterQuiz($index, $parent.$index)"><i class="fa fa-pencil"></i></a>
                                                            <a class="btn btn-sm btn-danger btn-margin-btm" v-on:click="removeChapterQuiz($index, $parent.$index)"><i class="fa fa-trash-o"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr v-show="chapter.quiz.questions.length == 0">
                                                        <td colspan="3">Tidak ada quiz</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <!-- End: Table Questions -->
                                    </div>
                                </div>
                            <!-- End: Quiz -->
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target=".add_chapter" v-on:click="addChapter">Add Chapter</button>
                        </h5>
                    </div>
                </div>
            </div>
            <!-- end:content -->
        </div>
        <div class="card-block">
            <template v-if="course.chapters.length > 0">
                <button type="submit" id="btn-submit" class="btn btn-primary btn-sm">Save</button>
            </template>
            <template v-else>
                <button type="button" id="btn-submit-disable" class="btn btn-danger btn-sm" disabled>Anda belum menambahkan chapter</button>
            </template>
        </div>
    </div>

    <?php echo form_open('dashboard/course/edit/'.$course->id.'/chapter', ['id' => 'form-course-result']); ?>
    <div id="course-result">
        <div v-for="chapter in course.chapters">
            <input type="hidden" name="course[chapters][{{ $index }}][id]" value="{{ chapter.id || 0 }}">
            <input type="hidden" name="course[chapters][{{ $index }}][name]" value="{{ chapter.name }}">
            <input type="hidden" name="course[chapters][{{ $index }}][order]" value="{{ $index+1 }}">
            <input type="hidden" name="course[chapters][{{ $index }}][content]" value="{{ chapter.content }}">

            <input type="hidden" name="course[chapters][{{ $index }}][quiz][id]" value="{{ chapter.quiz.id || 0 }}">
            <input type="hidden" name="course[chapters][{{ $index }}][quiz][name]" value="{{ chapter.quiz.name }}">
            <input type="hidden" name="course[chapters][{{ $index }}][quiz][time]" value="{{ chapter.quiz.time }}">
            <div v-for="quiz in chapter.quiz.questions">
                <input type="hidden" name="course[chapters][{{ $parent.$index }}][quiz][questions][{{ $index }}][id]" value="{{ quiz.id || 0 }}">
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
                <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][id]" value="{{ content.id || 0 }}">
                <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][filename]" value="{{ content.filename }}">
                <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][filetype]" value="{{ content.filetype }}">
                <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][filesize]" value="{{ content.filesize }}">
                <input type="hidden" name="course[chapters][{{ $parent.$index }}][attachments][{{ $index }}][dataurl]" value="{{ content.dataurl }}">
            </div>
        </div>

        <div class="chapter-remove" v-for="chapter in remove.chapters">
            <input type="hidden" name="remove[chapters][{{ $index }}]" value="{{ chapter }}">
        </div>
        <div class="chapter-quiz-remove" v-for="quiz in remove.chapterQuiz">
            <input type="hidden" name="remove[chapter_quiz][{{ $index }}]" value="{{ quiz }}">
        </div>
        <div class="chapter-attachment-remove" v-for="attachment in remove.chapterAttachment">
            <input type="hidden" name="remove[chapter_attachment][{{ $index }}]" value="{{ attachment }}">
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
    <script src="<?php echo asset('javascript/kelas.vue.js') ?>"></script>
    <script src="<?php echo asset('plugins/jQuery.filer-1.0.5/js/jquery.filer.min.js') ?>"></script>
    <script src="<?php echo asset('plugins/jQuery.filer-1.0.5/js/custom.js') ?>"></script>
    <script src="<?php echo asset('plugins/jquery.steps-1.1.0/js/jquery.steps.js') ?>"></script>
    <script src="<?php echo asset('plugins/jquery-validation-1.14.0/dist/jquery.validate.min.js') ?>"></script>
    <script>
    $(document).ready(function () {
        window.app_kelas_online.withCache = false;

        $.ajax({
            url: siteurl+'/dashboard/api/course/<?php echo $course->id ?>',
            type: 'GET',
            success: function (response) {
                window.app_kelas_online.initCourse(response)
            }
        })

        $.ajax({
            url: siteurl+'/dashboard/api/attachment/<?php echo $course->id ?>',
            type: 'GET',
            success: function (response) {
                window.app_kelas_online.initAttachment(response)
            }
        })

        $('#btn-submit').on('click', function () {
            $('#form-course-result').submit();
        })
    })
    </script>
<?php endcustom_script() ?>
