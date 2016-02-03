
<ol class="breadcrumb">
    <li><a href="<?php echo dashboard_url() ?>">Dashboard</a></li>
    <li><a href="<?php echo site_url('dashboard') ?>">Kelas Online</a></li>
    <li><a href="<?php echo site_url('dashboard/course/edit/'.$course->id) ?>"><?php echo $course->name ?></a></li>
    <li class="active">Exam</li>
</ol>

<div class="kelas-main" id="app-kelas-online">
    <div class="card">
        <div class="card-header">
             <p>Exam <button class="btn btn-exam" data-toggle="modal" data-target=".add-exam"><i class="fa fa-paperclip"></i> Add Question</button></p>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <label for="standar" class="col-sm-2 form-control-label">Standar Kelulusan</label>
                <div class="input-group col-sm-4">
                    <input type="text" class="form-control" id="standar" placeholder="Standar Kelulusan" v-model="course.passing_standards">
                </div>
            </div>
            <div class="form-group row">
                <label for="waktu" class="col-sm-2 form-control-label">Waktu</label>
                <div class="input-group col-sm-4">
                    <input type="text" class="form-control"  id="waktu" placeholder="Waktu" v-model="course.exam.time">
                    <div class="input-group-addon">Menit</div>
                </div>
            </div>
           <!-- start: content -->
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
                            <td class="text-xs-center">
                                <a class="btn btn-sm btn-info btn-margin-btm" data-toggle="modal" data-target=".add-exam" title="Edit" v-on:click="editExamQuestion($index)"><i class="fa fa-pencil"></i></a>
                                <a class="btn btn-sm btn-danger btn-margin-btm" title="Delete" v-on:click="removeExamQuestion($index)"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        <tr v-show="course.exam.questions.length == 0">
                            <td colspan="3">Anda belum menambahkan ujian</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- end:content -->

            <!-- Begin: Add Exam -->
            <?php $this->load->view('modal/add_exam'); ?>
            <!-- End: Add Exam -->
        </div>
        <div class="card-block">
            <button type="submit" id="btn-submit" class="btn btn-primary btn-sm btn-submit" v-show="course.exam.questions.length > 0">Save</button>
            <button type="button" id="btn-submit-disable" class="btn btn-danger btn-sm" disabled v-show="course.exam.questions.length == 0">Anda belum menambahkan ujian</button>
        </div>
    </div>

    <?php echo form_open('dashboard/course/edit/'.$course->id.'/exam', ['id' => 'form-course-result']); ?>
    <div id="course-result">
        <input type="hidden" name="course[exam][id]" value="{{ course.exam.id || 0 }}">
        <input required type="hidden" name="course[exam][name]" value="{{ course.exam.name }}">
        <input required type="hidden" name="course[exam][time]" value="{{ course.exam.time }}">
        <input required type="hidden" name="course[passing_standards]" value="{{ course.passing_standards }}">
        <div v-for="exam in course.exam.questions">
            <input type="hidden" name="course[exam][questions][{{ $index }}][id]" value="{{ exam.id || 0 }}">
            <input type="hidden" name="course[exam][questions][{{ $index }}][question]" value="{{ exam.question }}">
            <input type="hidden" name="course[exam][questions][{{ $index }}][option_a]" value="{{ exam.option_a }}">
            <input type="hidden" name="course[exam][questions][{{ $index }}][option_b]" value="{{ exam.option_b }}">
            <input type="hidden" name="course[exam][questions][{{ $index }}][option_c]" value="{{ exam.option_c }}">
            <input type="hidden" name="course[exam][questions][{{ $index }}][option_d]" value="{{ exam.option_d }}">
            <input type="hidden" name="course[exam][questions][{{ $index }}][correct]" value="{{ exam.correct }}">
        </div>
        <div class="exam-remove" v-for="exam in remove.exams">
            <input type="hidden" name="remove[exams][{{ $index }}]" value="{{ exam }}">
        </div>
    </div>
    <?php echo form_close(); ?>

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

        $('#waktu').on('change', function () {
            if ($(this).val() < 1) {
                alert('Waktu exam minimal adalah 1 menit')
                $(this).val(1)
            }
        })

        $('#form-course-result').validate();

        $('#btn-submit').on('click', function () {
            if (!window.app_kelas_online.checkExamHasTime()) {
                alert('Minimal waktu exam adalah 1 menit');
            } else {
                $('#form-course-result').submit();
            }
        })
    })
    </script>

    
<?php endcustom_script() ?>
