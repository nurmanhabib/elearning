$(document).ready(function () {
    var STORAGE_KEY = 'vue-kelas-app';
    var STORAGE_ATTACHMENT_KEY = 'vue-kelas-app-attachment';

    var store = {
        fetch: function () {
            return JSON.parse(localStorage.getItem(STORAGE_KEY))
        },
        save: function (course) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(course));
        },
        destroy: function () {
            localStorage.removeItem(STORAGE_KEY);
        }
    }

    function ObjCourse() {
        this.id = 0;
        this.name = '';
        this.days = 30;
        this.description = '';
        this.passing_standards = 0;
        this.category_id = 0;
        this.chapters = [];
        this.exam = new ObjQuiz();
    }

    function ObjChapter() {
        this.id = 0;
        this.name = '';
        this.content = '';
        this.order = 0;
        this.quiz = new ObjQuiz();
    }

    function ObjQuiz() {
        this.id = 0;
        this.name = '';
        this.time = 5;
        this.questions = [];
    }

    function ObjQuestion() {
        this.id = 0;
        this.question = '';
        this.option_a = '';
        this.option_b = '';
        this.option_c = '';
        this.option_d = '';
        this.correct = '';
    }

    function ObjAttachment() {
        this.contents = []
    }

    function ObjContent() {
        this.id = 0;
        this.filename = '';
        this.filetype = '';
        this.filesize = 0;
        this.dataurl = '';
    }

    window.app_kelas_online = new Vue({
        el: '#app-kelas-online',
        data: {
            course: new ObjCourse(),
            input: {
                chapter: new ObjChapter(),
                chapterQuiz: new ObjQuestion(),
                chapterAttachment: new ObjAttachment(),
                exam: new ObjQuestion()
            },
            errors: {
                chapter: [],
                chapterQuiz: [],
                chapterAttachment: [],
                exam: []
            },
            remove: {
                chapters: [],
                chapterQuiz: [],
                chapterAttachment: [],
                exams: []
            },
            attachments: [],
            tinymce: 'editor-question',
            tinymceExam: 'editor-exam',
            filer: $('#file_attachment'),
            contentResult: '#result-content',
            withCache: true
        },
        watch: {
            course: {
                deep: true,
                handler: function (value) {
                    if (this.withCache)
                        store.save(value);
                }
            }
        },
        methods: {
            initData: function (storage) {
                if (!storage) {
                    storage = store.fetch();
                }

                $.extend(this.course, storage);

                var count = this.course.chapters.length;

                for (var i = 0; i < count; i++) {
                    this.attachments.push(new ObjAttachment());
                }
            },
            initCourse: function (storage) {
                this.course = $.extend({}, new ObjCourse(), storage);
            },
            initAttachment: function (storage) {
                this.attachments = storage;
            },
            saveChapter: function (event) {
                var index = this.input.chapter.chapterIndex;

                if (this.validationForm('#form-chapter-modal')) {
                    if (typeof index === 'undefined') {
                        var attachment = new ObjAttachment();
                        var chapter = new ObjChapter();
                        chapter.name = this.input.chapter.name;
                        chapter.content = this.input.chapter.content;

                        this.course.chapters.push(chapter);                    
                        this.attachments.push(attachment)
                    } else {
                        this.course.chapters[index].name = this.input.chapter.name;
                        this.course.chapters[index].content = this.input.chapter.content;
                    }

                    $(event.target).closest('.modal').modal('hide');

                    this.resetInput('chapter', new ObjChapter());
                }
            },
            editChapter: function (chapterIndex) {
                var chapter = this.course.chapters[chapterIndex];

                this.input.chapter.name = chapter.name;
                this.input.chapter.content = chapter.content;
                this.input.chapter.chapterIndex = chapterIndex;
            },
            removeChapter: function (chapter) {
                if (chapter.id)
                    this.remove.chapters.push(chapter.id);
                
                this.course.chapters.$remove(chapter);
            },
            cancelChapter: function () {
                this.resetInput('chapter', new ObjChapter());
            },
            addChapterQuiz: function (chapterIndex) {
                this.input.chapterQuiz.chapterIndex = chapterIndex;
            },
            saveChapterQuiz: function (event) {
                var chapter_index = this.input.chapterQuiz.chapterIndex;
                var index = this.input.chapterQuiz.questionIndex;
                var chapter = this.course.chapters[chapter_index];
                var error = false;

                tinyMCE.triggerSave();

                if ($('#editor-question').val() == '') {
                    alert('Anda belum memasukkan pertanyaan');

                    error = true;
                }

                if (this.validationForm('#form-question-modal', {content: 'questiontinymce'}) && !error) {
                    if (chapter.quiz === null) {
                        chapter.quiz = new ObjQuiz();
                    }

                    var quiz = chapter.quiz;

                    if (typeof index === 'undefined') {
                        var question = new ObjQuestion();
                    } else {
                        var question = quiz.questions[index];
                    }

                    question.question = this.input.chapterQuiz.question;
                    question.option_a = this.input.chapterQuiz.option_a;
                    question.option_b = this.input.chapterQuiz.option_b;
                    question.option_c = this.input.chapterQuiz.option_c;
                    question.option_d = this.input.chapterQuiz.option_d;
                    question.correct = this.input.chapterQuiz.correct;

                    if (typeof index === 'undefined') {
                        quiz.questions.push(question);
                    } else {
                        quiz.questions[index] = question;
                    }

                    $(event.target).closest('.modal').modal('hide');

                    this.resetInput('chapterQuiz', new ObjQuestion());
                    tinymce.get(this.tinymce).setContent('');
                }
            },
            editChapterQuiz: function (questionIndex, chapterIndex) {
                var chapter = this.course.chapters[chapterIndex];
                var question = chapter.quiz.questions[questionIndex];

                this.input.chapterQuiz.question = question.question;
                this.input.chapterQuiz.option_a = question.option_a;
                this.input.chapterQuiz.option_b = question.option_b;
                this.input.chapterQuiz.option_c = question.option_c;
                this.input.chapterQuiz.option_d = question.option_d;
                this.input.chapterQuiz.correct = question.correct;
                this.input.chapterQuiz.chapterIndex = chapterIndex;
                this.input.chapterQuiz.questionIndex = questionIndex;
                
                tinymce.get(this.tinymce).setContent(question.question);
            },
            removeChapterQuiz: function (questionIndex, chapterIndex) {                
                var chapter = this.course.chapters[chapterIndex];
                var question = chapter.quiz.questions[questionIndex];

                if (question.id)
                    this.remove.chapterQuiz.push(chapter.id + '/' + question.id);

                chapter.quiz.questions.$remove(question);
            },
            setQuestion: function (question) {
                this.input.chapterQuiz.question = question;
            },
            addChapterAttachment: function (chapterIndex) {
                this.input.chapterAttachment.chapterIndex = chapterIndex;
            },
            addChapterContent: function (filename, filetype, filesize, dataurl) {
                var content = new ObjContent();

                content.filename = filename;
                content.filetype = filetype;
                content.filesize = filesize;
                content.dataurl = dataurl;

                this.input.chapterAttachment.contents.push(content);
            },
            removeInputChapterContent: function (index) {
                this.input.chapterAttachment.contents.splice(index, 1);
            },
            clearInputChapterContent: function () {
                this.input.chapterAttachment.contents = [];
            },
            saveChapterContent: function () {
                var chapterIndex = this.input.chapterAttachment.chapterIndex;
                var attachment = this.attachments[chapterIndex];
                var contents = this.input.chapterAttachment.contents;
                var that = this;

                contents.forEach(function (data, index) {
                    var content = new ObjContent();

                    content.filename = data.filename;
                    content.filetype = data.filetype;
                    content.filesize = data.filesize;
                    content.dataurl = data.dataurl;

                    attachment.contents.push(content);
                });

                this.resetInput('chapterAttachment', new ObjAttachment());

                var filerKit = this.filer.prop('jFiler')
                filerKit.reset()
            },
            removeChapterContent: function (index, chapterIndex) {
                var attachment = this.attachments[chapterIndex];
                var content = attachment.contents[index];
                var chapter = this.course.chapters[chapterIndex];

                if (content.id)
                    this.remove.chapterAttachment.push(chapter.id + '/' + content.id);

                attachment.contents.splice(index, 1);
            },
            saveExam: function (event) {
                var index = this.input.exam.examIndex;
                var error = false;

                tinyMCE.triggerSave();

                if ($('#editor-exam').val() == '') {
                    alert('Anda belum memasukkan pertanyaan')

                    error = true;
                }
                
                if (this.validationForm('#form-exam-modal') && !error) {
                    if (typeof index === 'undefined') {
                        var question = new ObjQuestion();

                        question.question = this.input.exam.question;
                        question.option_a = this.input.exam.option_a;
                        question.option_b = this.input.exam.option_b;
                        question.option_c = this.input.exam.option_c;
                        question.option_d = this.input.exam.option_d;
                        question.correct = this.input.exam.correct;

                        if (this.course.exam === null) {
                            this.course.exam = new ObjQuiz();
                        }

                        this.course.exam.questions.push(question);
                    } else {
                        var question = this.course.exam.questions[index];

                        question.question = this.input.exam.question;
                        question.option_a = this.input.exam.option_a;
                        question.option_b = this.input.exam.option_b;
                        question.option_c = this.input.exam.option_c;
                        question.option_d = this.input.exam.option_d;
                        question.correct = this.input.exam.correct;
                    }

                    $(event.target).closest('.modal').modal('hide');

                    this.resetInput('exam', new ObjQuestion());
                    tinymce.get(this.tinymceExam).setContent('');
                }
            },
            setExamQuestion: function (question) {
                this.input.exam.question = question;
            },
            editExamQuestion: function (questionIndex) {
                var question = this.course.exam.questions[questionIndex];

                this.input.exam.question = question.question;
                this.input.exam.option_a = question.option_a;
                this.input.exam.option_b = question.option_b;
                this.input.exam.option_c = question.option_c;
                this.input.exam.option_d = question.option_d;
                this.input.exam.correct = question.correct;
                this.input.exam.examIndex = questionIndex;

                tinymce.get(this.tinymceExam).setContent(question.question);
            },
            removeExamQuestion: function (questionIndex) {
                var question = this.course.exam.questions[questionIndex];

                if (question.id)
                    this.remove.exams.push(question.id);

                this.course.exam.questions.splice(questionIndex, 1);
            },
            cancelExam: function () {
                this.resetInput('exam', new ObjQuestion());
                tinymce.get(this.tinymceExam).setContent('');
            },
            resetInput: function (name, data) {
                if (name) {
                    if (data)
                        this.input[name] = data;
                    else
                        this.input[name] = {};
                }
            },
            reset: function () {
                store.destroy();
                this.course = new ObjCourse();
            },
            checkAllChapterHasQuiz: function () {
                var has = 0;

                $.each(this.course.chapters, function (index, chapter) {
                    if (chapter.quiz.questions.length > 0)
                        has++;
                })

                return has === this.course.chapters.length
            },
            validationForm: function (form_id, options) {
                var form = $(form_id);
                var optiondefault = {
                        errorPlacement: function errorPlacement(error, element) {
                            var before = element.parent();

                            if (before.attr('class') == 'input-group')
                                before.before(error);
                            else
                                element.before(error);
                        }
                    }

                if (typeof options == 'undefined')
                    form.validate(optiondefault);
                else
                    form.validate($.extend({}, optiondefault, options));

                return form.valid();
            },
            validationChapterQuiz: function () {
                var errors = [];
                var question = new ObjQuestion();

                if (question.question == "")
                    errors.push({
                        question: 'Pertanyaan harus diisi'
                    });

                if (question.option_a == "")
                    errors.push({
                        option_a: 'Pertanyaan harus diisi'
                    });

                return errors;
            }
        },
        ready: function () {
            var that = this;

            this.initData();

            // tinyMCE question
            tinyMCE.init({
                setup: function (editor) {
                    editor.on('keyup', function(e) {
                        that.setQuestion(editor.getContent());
                    });
                },
                selector: '#'+that.tinymce
            });

            // tinyMCE exam question
            tinyMCE.init({
                setup: function (editor) {
                    editor.on('keyup', function(e) {
                        that.setExamQuestion(editor.getContent());
                    });
                },
                selector: '#'+that.tinymceExam
            });

            // tinyMCE description
            tinyMCE.init({
                setup: function (editor) {
                    editor.on('keyup', function(e) {
                        var description = editor.getContent();

                        that.course.description = description;
                    });
                },
                selector: '.editor-description'
            });

            // jQuery Filer
            $('#file_attachment').filer({
                showThumbs: true,
                addMore: true,
                onSelect: function (file, item, row, jFiler, jFilerInput, input) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        var dataUrl = e.target.result;
                        that.addChapterContent(file.name, file.type, file.size, dataUrl);
                    }
                    reader.readAsDataURL(file);
                },
                onRemove: function (item, file, id, row, jFiler, jFilerInput, input) {
                    that.removeInputChapterContent(id);
                },
                templates: {
                    box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
                    item: '<li class="jFiler-item">\
                                <div class="jFiler-item-container">\
                                    <div class="jFiler-item-inner">\
                                        <div class="jFiler-item-thumb">\
                                            <div class="jFiler-item-status"></div>\
                                            <div class="jFiler-item-info">\
                                                <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                                <span class="jFiler-item-others">{{fi-size2}}</span>\
                                            </div>\
                                            {{fi-image}}\
                                        </div>\
                                        <div class="jFiler-item-assets jFiler-row">\
                                            <ul class="list-inline pull-left"></ul>\
                                            <ul class="list-inline pull-right">\
                                                <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                            </ul>\
                                        </div>\
                                    </div>\
                                </div>\
                            </li>',
                    itemAppend: '<li class="jFiler-item">\
                                    <div class="jFiler-item-container">\
                                        <div class="jFiler-item-inner">\
                                            <div class="jFiler-item-thumb">\
                                                <div class="jFiler-item-status"></div>\
                                                <div class="jFiler-item-info">\
                                                    <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                                    <span class="jFiler-item-others">{{fi-size2}}</span>\
                                                </div>\
                                                {{fi-image}}\
                                            </div>\
                                            <div class="jFiler-item-assets jFiler-row">\
                                                <ul class="list-inline pull-left">\
                                                    <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                                                </ul>\
                                                <ul class="list-inline pull-right">\
                                                    <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                                </ul>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </li>',
                    itemAppendToEnd: false,
                    removeConfirmation: true,
                    _selectors: {
                        list: '.jFiler-items-list',
                        item: '.jFiler-item',
                        remove: '.jFiler-item-trash-action'
                    }
                },
            });
        }
    });
});