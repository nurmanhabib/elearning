<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Intervention\Image\ImageManager;
use Library\Kelas\CourseRepository;

class Course extends Admin
{
    public function __construct()
    {
        parent::__construct();

        $this->kelas        = new Library\Kelas\Kelas;
        $this->repository   = new Library\Kelas\CourseRepository;
    }

    public function create()
    {
        $this->template->set_layout('dashboard');

        $data   = [
            'category_lists' => $this->kelas->getCategoryLists()
        ];
        
        $this->template->build('course/create', $data);
    }

    public function submit()
    {
        // 1. Prepare from input
        $input = collect($this->input->post('course'));
        
        // 2. Create Course
        $course                 = new Model\Kelas\Course;
        $course->name           = $input->get('name');
        $course->description    = $input->get('description', '', FALSE);
        $course->passing_standards  = $input->get('passing_standards');
        $course->days           = $input->get('days', 30);
        $course->status         = 'draft';

        $course->category()->associate($input->get('category_id'));
        $course->instructor()->associate(sentinel()->getUser());
        $course->save();

        // 3. Create Folder Content and Upload Images
        $kelas_content = PATH_KELASONLINE_CONTENT.'/'.$course->id;

        if (!is_dir($kelas_content)) {
            mkdir($kelas_content, 0775);
        }

        $imageManager   = new ImageManager;

        // 3.a. Featured image
        $featured = $this->input->post('featured[src]');
        
        if (!empty($featured)) {
            $image = $imageManager->make($featured);

            if ($image->mime == 'image/jpeg')
                $extension = '.jpg';
            elseif ($image->mime == 'image/png')
                $extension = '.png';
            elseif ($image->mime == 'image/gif')
                $extension = '.gif';
            else
                $extension = '';

            $prefix     = 'featured_';
            $filename   = $prefix . $course->id . $extension;

            $kelas_content = PATH_KELASONLINE_CONTENT.'/'.$course->id;

            $image->save($kelas_content.'/'.$filename);

            $course->featured   = $filename;
            $course->save();
        }

        // 3.b. Thumbnail image
        $thumbnail = $this->input->post('thumbnail[src]');
        
        if (!empty($thumbnail)) {
            $image = $imageManager->make($thumbnail);

            if ($image->mime == 'image/jpeg')
                $extension = '.jpg';
            elseif ($image->mime == 'image/png')
                $extension = '.png';
            elseif ($image->mime == 'image/gif')
                $extension = '.gif';
            else
                $extension = '';

            $prefix     = 'thumbnail_';
            $filename   = $prefix . $course->id . $extension;

            $image->save($kelas_content.'/'.$filename);

            $course->thumbnail = $filename;
            $course->save();
        }

        // 4. Chapters
        $chapters = collect($input->get('chapters'));

        $chapters->each(function ($chapter) use ($course) {
            $chapter                    = collect($chapter);
            $modelChapter               = new Model\Kelas\Chapter;
            $modelChapter->name         = $chapter->get('name');
            $modelChapter->content      = $chapter->get('content');
            $modelChapter->order        = $chapter->get('order');
            
            $modelChapter->course()->associate($course);
            $modelChapter->save();

            // 5. Quiz Chapter
            $quiz               = collect($chapter->get('quiz'));
            $modelQuiz          = new Model\Kelas\Quiz;
            $modelQuiz->name    = $quiz->get('name');
            $modelQuiz->time    = $quiz->get('time');

            $modelQuiz->chapter()->associate($modelChapter);
            $modelQuiz->save();

            $questions = collect($quiz->get('questions'));

            $questions->each(function ($question) use ($modelQuiz, $modelChapter, $course) {
                $question                   = collect($question);
                $modelQuestion              = new Model\Kelas\QuizQuestion;
                $modelQuestion->question    = $question->get('question');
                $modelQuestion->option_a    = $question->get('option_a');
                $modelQuestion->option_b    = $question->get('option_b');
                $modelQuestion->option_c    = $question->get('option_c');
                $modelQuestion->option_d    = $question->get('option_d');
                $modelQuestion->correct     = $question->get('correct');

                $modelQuestion->quiz()->associate($modelQuiz);
                $modelQuestion->save();
            });

            // 6. Attachment
            $attachments        = collect($chapter->get('attachments'));
            $attachment_path    = PATH_KELASONLINE_CONTENT.'/'.$course->id.'/chapter_'.$modelChapter->id;

            if (!is_dir($attachment_path)) {
                mkdir($attachment_path, 0775);
            }

            $attachments->each(function ($attachment) use ($modelChapter, $course) {
                $attachment                 = collect($attachment);
                $modelAttachment            = new Model\Kelas\Attachment;
                $modelAttachment->filename  = $attachment->get('filename');
                $modelAttachment->filetype  = $attachment->get('filetype');
                $modelAttachment->filesize  = $attachment->get('filesize');
                $modelAttachment->chapter()->associate($modelChapter);
                $modelAttachment->save();

                // 6.a. Uploading attachment
                $file_encoded   = $attachment->get('dataurl');
                $file_encoded   = explode(',', $file_encoded);
                $file_base64    = $file_encoded[1];

                $fp = fopen($modelAttachment->filepath, 'w+');
                fwrite($fp, base64_decode($file_base64));
                fclose($fp);
            });
        });

        // 7. Exam
        $exam               = collect($input->get('exam'));
        $modelExam          = new Model\Kelas\Exam;
        $modelExam->name    = $exam->get('name');
        $modelExam->time    = $exam->get('time');

        $modelExam->course()->associate($course);
        $modelExam->save();

        $questions = collect($exam->get('questions'));

        $questions->each(function ($question) use ($modelExam, $course) {
            $question                   = collect($question);
            $modelQuestion              = new Model\Kelas\ExamQuestion;
            $modelQuestion->question    = $question->get('question');
            $modelQuestion->option_a    = $question->get('option_a');
            $modelQuestion->option_b    = $question->get('option_b');
            $modelQuestion->option_c    = $question->get('option_c');
            $modelQuestion->option_d    = $question->get('option_d');
            $modelQuestion->correct     = $question->get('correct');

            $modelQuestion->exam()->associate($modelExam);
            $modelQuestion->save();
        });

        set_message_success('Kelas Anda berhasil ditambahkan. Namun kelas Anda masih berada pada tahap moderator.');

        $redirect = 'dashboard/course/edit/'.$course->id;

        if ($this->input->is_ajax_request())
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'redirect' => site_url($redirect)
            ]));
        else {
            redirect($redirect);
        }
    }

    public function edit($id, $page = 'index')
    {
        $data = [
            'category_lists'    => $this->kelas->getCategoryLists(),
            'course'            => $this->kelas->getCourse($id),
            'repository'        => new CourseRepository($id),
            'sidebar_active'    => 'basic',
        ];

        $this->template->set($data);
        $this->template->set_layout('dashboard_course');

        if ($page == 'index') {
            $this->editIndex($id);

            return;
        } else {
            $this->template->set('sidebar_active', $page);

            call_user_func_array([$this, 'edit'.ucfirst($page)], [$id]);

            return;
        }
    }

    public function editIndex($id)
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('days', 'Long Days', 'required|numeric|greater_than[1]');
        $this->form_validation->set_rules('category_id', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {
            keepValidationErrors();

            $this->template->build('course/edit');
        } else {
            $course                 = Model\Kelas\Course::withDrafts()->findOrFail($id);
            $course->name           = set_value('name');
            $course->description    = set_value('description', '', FALSE);
            $course->passing_standards  = set_value('passing_standards');
            $course->days           = set_value('days', 30);
            $course->category_id    = set_value('category_id');
            $course->save();

            set_message_success('Informasi kelas berhasil diperbarui.');

            redirect('dashboard/course/edit/'.$course->id, 'refresh');
        }
    }

    public function editRequirement($id)
    {
        $course = Model\Kelas\Course::withDrafts()->findOrFail($id);

        if (!$this->input->post()) {
            $course_lists = $this->repository->allExcept($id, 'publish')->pluck('name', 'id');
            
            $this->template->build('course/requirement', compact('courses', 'course_lists'));
        } else {
            $requirements = $this->input->post('course[requirements]') ?: [];

            $course->requirements()->sync($requirements);

            redirect('dashboard/course/edit/'.$id.'/requirement', 'refresh');
        }
    }

    public function editImage($id)
    {
        if (!$this->input->post()) {
            $this->template->build('course/image');
        } else {
            $course         = Model\Kelas\Course::withDrafts()->findOrFail($id);
            $featured       = $this->input->post('featured[src]');
            $action         = $this->input->post('featured[action]');
            $imageManager   = new ImageManager;
            $kelas_content  = PATH_KELASONLINE_CONTENT.'/'.$course->id;
            
            if ($action == 'remove') {
                // remove
            } elseif ($action == 'upload') {
                if (!empty($featured)) {
                    $image = $imageManager->make($featured);

                    if ($image->mime == 'image/jpeg')
                        $extension = '.jpg';
                    elseif ($image->mime == 'image/png')
                        $extension = '.png';
                    elseif ($image->mime == 'image/gif')
                        $extension = '.gif';
                    else
                        $extension = '';

                    $prefix     = 'featured_';
                    $filename   = $prefix . $course->id . $extension;

                    $image->save($kelas_content.'/'.$filename);

                    $course->featured   = $filename;
                    $course->save();
                }
            }

            $thumbnail      = $this->input->post('thumbnail[src]');
            $action         = $this->input->post('thumbnail[action]');
            $imageManager   = new ImageManager;
            $kelas_content  = PATH_KELASONLINE_CONTENT.'/'.$course->id;
            
            if ($action == 'remove') {
                // remove
            } elseif ($action == 'upload') {
                if (!empty($thumbnail)) {
                    $image = $imageManager->make($thumbnail);

                    if ($image->mime == 'image/jpeg')
                        $extension = '.jpg';
                    elseif ($image->mime == 'image/png')
                        $extension = '.png';
                    elseif ($image->mime == 'image/gif')
                        $extension = '.gif';
                    else
                        $extension = '';

                    $prefix     = 'thumbnail_';
                    $filename   = $prefix . $course->id . $extension;

                    $image->save($kelas_content.'/'.$filename);

                    $course->thumbnail = $filename;
                    $course->save();
                }
            }

            redirect('dashboard/course/edit/'.$course->id.'/image', 'refresh');
        }
    }

    public function editChapter($id)
    {
        if (!$this->input->post()) {
            $this->template->build('course/chapter');
        } else {
            // 1. Prepare from input
            $input  = collect($this->input->post('course'));
            $course = Model\Kelas\Course::withDrafts()->findOrFail($id);

            $this->repository->set($course);

            // 2. Remove data
            $remove = collect($this->input->post('remove'));

            // 2.a Remove Chapter
            foreach ($remove->get('chapters', []) as $chapter_id) {
                $this->repository->deleteChapter($chapter_id);
            }

            // 2.b Remove Quiz
            foreach ($remove->get('chapter_quiz', []) as $question) {
                list($chapter_id, $question_id) = explode('/', $question);

                $this->repository->deleteChapterQuiz($question_id);
            }

            // 2.b Remove Attachment
            foreach ($remove->get('chapter_attachment', []) as $attachment) {
                list($chapter_id, $attachment_id) = explode('/', $attachment);

                $this->repository->deleteChapterAttachment($attachment_id);
            }

            // 4. Chapters
            $chapters = collect($input->get('chapters'));

            $chapters->each(function ($chapter) use ($course) {
                $chapter                    = collect($chapter);

                if ($chapter->get('id')) {
                    $modelChapter   =  Model\Kelas\Chapter::findOrFail($chapter->get('id'));
                } else {
                    $modelChapter   = new Model\Kelas\Chapter;
                    $modelChapter->course()->associate($course);
                }

                $modelChapter->name         = $chapter->get('name');
                $modelChapter->content      = $chapter->get('content');
                $modelChapter->order        = $chapter->get('order');                
                $modelChapter->save();

                // 5. Quiz Chapter
                $quiz               = collect($chapter->get('quiz'));

                if ($quiz->get('id')) {
                    $modelQuiz  = Model\Kelas\Quiz::findOrFail($quiz->get('id'));
                } else {
                    $modelQuiz  = new Model\Kelas\Quiz;
                    $modelQuiz->chapter()->associate($modelChapter);
                }

                $modelQuiz->name    = $quiz->get('name');
                $modelQuiz->time    = $quiz->get('time');
                $modelQuiz->save();

                $questions = collect($quiz->get('questions'));

                $questions->each(function ($question) use ($modelQuiz, $modelChapter, $course) {
                    $question = collect($question);

                    if ($question->get('id'))
                        $modelQuestion  = Model\Kelas\QuizQuestion::findOrFail($question->get('id'));
                    else {
                        $modelQuestion  = new Model\Kelas\QuizQuestion;
                        $modelQuestion->quiz()->associate($modelQuiz);
                    }

                    $modelQuestion->question    = $question->get('question');
                    $modelQuestion->option_a    = $question->get('option_a');
                    $modelQuestion->option_b    = $question->get('option_b');
                    $modelQuestion->option_c    = $question->get('option_c');
                    $modelQuestion->option_d    = $question->get('option_d');
                    $modelQuestion->correct     = $question->get('correct');
                    $modelQuestion->save();
                });

                // 6. Attachment
                $attachments        = collect($chapter->get('attachments'));
                $attachment_path    = PATH_KELASONLINE_CONTENT.'/'.$course->id.'/chapter_'.$modelChapter->id;

                if (!is_dir($attachment_path)) {
                    mkdir($attachment_path, 0775);
                }

                $modelChapter->attachments->each(function ($attachment) use ($attachments) {
                    $attachments->each(function ($input) use ($attachment) {
                        $input = collect($input);

                        if (!$input->get('id') == $attachment->id) {
                            //
                        }
                    });
                });

                $attachments->each(function ($attachment) use ($modelChapter, $course) {
                    $attachment = collect($attachment);

                    if ($attachment->get('id')) {
                        // Already
                    } else {
                        $modelAttachment            = new Model\Kelas\Attachment;
                        $modelAttachment->filename  = $attachment->get('filename');
                        $modelAttachment->filetype  = $attachment->get('filetype');
                        $modelAttachment->filesize  = $attachment->get('filesize');
                        $modelAttachment->chapter()->associate($modelChapter);
                        $modelAttachment->save();

                        // 6.a. Uploading attachment
                        $file_encoded   = $attachment->get('dataurl');
                        $file_encoded   = explode(',', $file_encoded);
                        $file_base64    = $file_encoded[1];

                        $fp = fopen($modelAttachment->filepath, 'w+');
                        fwrite($fp, base64_decode($file_base64));
                        fclose($fp);
                    }
                });
            });

            redirect('dashboard/course/edit/'.$course->id.'/chapter', 'refresh');
        }
    }

    public function editExam($id)
    {
        if (!$this->input->post()) {
            $this->template->build('course/exam');
        } else {
            // 1. Prepare from input
            $input  = collect($this->input->post('course'));
            $course = Model\Kelas\Course::withDrafts()->findOrFail($id);
            $course = Model\Kelas\Course::withDrafts()->findOrFail($id);

            $this->repository->set($course);

            // 2. Remove data
            $remove = collect($this->input->post('remove'));

            foreach ($remove->get('exams', []) as $exam_id) {
                $this->repository->deleteExamQuestion($exam_id);
            }

            // 7. Exam
            $exam = collect($input->get('exam'));

            if ($exam->get('id')) {
                $modelExam  = Model\Kelas\Exam::findOrFail($exam->get('id'));
            } else {
                $modelExam  = new Model\Kelas\Exam;
                $modelExam->course()->associate($course);
            }

            $modelExam->name    = $exam->get('name');
            $modelExam->time    = $exam->get('time');
            $modelExam->save();

            $questions = collect($exam->get('questions'));

            $questions->each(function ($question) use ($modelExam, $course) {
                $question = collect($question);

                if ($question->get('id')) {
                    $modelQuestion  = Model\Kelas\ExamQuestion::findOrFail($question->get('id'));
                } else {
                    $modelQuestion  = new Model\Kelas\ExamQuestion;
                    $modelQuestion->exam()->associate($modelExam);
                }

                $modelQuestion->question    = $question->get('question');
                $modelQuestion->option_a    = $question->get('option_a');
                $modelQuestion->option_b    = $question->get('option_b');
                $modelQuestion->option_c    = $question->get('option_c');
                $modelQuestion->option_d    = $question->get('option_d');
                $modelQuestion->correct     = $question->get('correct');
                $modelQuestion->save();
            });

            redirect('dashboard/course/edit/'.$course->id.'/exam', 'refresh');
        }
    }

    public function editMember($id)
    {
        $status     = $this->input->get('status', 'all');
        $repository = new CourseRepository($id);
        $members    = $repository->getMemberStatus($status);
        $members    = pagination($members, 15, 'dashboard/course/edit/'.$id.'/member');

        $members->appends(compact('status'));

        $this->template->build('course/member', compact('members'));
    }

    public function approveMember($id, $member)
    {
        $repository = new CourseRepository($id);
        $repository->approveMember($member);

        redirect('dashboard/course/edit/'.$id.'/member?status=pending', 'refresh');
    }

    public function kickMember($id, $member)
    {
        $repository = new CourseRepository($id);
        $repository->deleteMember($member);

        redirect('dashboard/course/edit/'.$id.'/member?status=active', 'refresh');
    }

    public function delete($id)
    {
        $course = new CourseRepository($id);
        $course->delete();

        set_message_success('Kelas berhasil dihapus');

        redirect('dashboard', 'refresh');
    }

    public function editReview($id)
    {
        $status = $this->input->get('status', 'publish');

        $repository = new CourseRepository($id);
        $course     = $repository->get();

        if ($status == 'draft') {
            $this->template->set('sidebar_active', 'review_draft');
            $comments   = $course->comments()->withDrafts()->where('status', $status)->latest()->get();
        } else {
            $comments   = $course->comments()->latest()->get();
        }

        $comments   = pagination($comments, 15, 'dashboard/course/edit/'.$course->id.'/review');

        $comments->appends(compact('status'));

        $this->template->build('course/comment_list_review', compact('comments'));
    }

    public function editComment($id)
    {
        $status = $this->input->get('status', 'publish');

        if ($status == 'draft')
            $this->template->set('sidebar_active', 'comment_draft');

        $repository = new CourseRepository($id);
        $course     = $repository->get();
        $comments   = collect([]);

        foreach ($course->chapters as $chapter) {
            $chapter_comments = $status == 'publish' ? $chapter->comments : $chapter->comments()->onlyDrafts()->get();

            foreach ($chapter_comments as $comment) {
                $comments->push($comment);
            }
        }

        $comments   = pagination($comments, 15, 'course/edit/'.$course->id.'/comment');
        $comments->appends(compact('status'));

        $this->template->build('course/comment_list_chapter', compact('comments'));
    }

    public function approveReview($course_id, $review_id)
    {
        $repository = new CourseRepository($course_id);
        $repository->approveReview($course_id, $review_id);

        set_message_success('Review berhasil dipublikasikan');

        redirect('dashboard/course/edit/'.$course_id.'/review?status=publish', 'refresh');
    }

    public function deleteReview($course_id, $review_id)
    {
        $repository = new CourseRepository($course_id);
        $repository->deleteReview($course_id, $review_id);

        set_message_success('Review berhasil dihapus');

        redirect('dashboard/course/edit/'.$course_id.'/review?status=publish', 'refresh');
    }

    public function approveComment($course_id, $chapter_id, $comment_id)
    {
        $repository = new CourseRepository($course_id);
        $repository->approveChapterComment($comment_id);

        set_message_success('Komentar berhasil diperbarui');

        redirect('dashboard/course/edit/'.$course_id.'/comment?status=publish', 'refresh');
    }

    public function deleteComment($course_id, $chapter_id, $comment_id)
    {
        $repository = new CourseRepository($course_id);
        $repository->deleteChapterComment($comment_id);

        set_message_success('Komentar berhasil dihapus');

        redirect('dashboard/course/edit/'.$course_id.'/comment?status=publish', 'refresh');
    }

    public function _remap($method, $params = array())
    {
        switch ($method) {
            case 'approve-member':
                return $this->approveMember($params[0], $params[1]);
                break;

            case 'kick-member':
                return $this->kickMember($params[0], $params[1]);
                break;

            case 'approve-review':
                return $this->approveReview($params[0], $params[1]);
                break;

            case 'delete-review':
                return $this->deleteReview($params[0], $params[1]);
                break;

            case 'approve-comment':
                return $this->approveComment($params[0], $params[1], $params[2]);
                break;

            case 'delete-comment':
                return $this->deleteComment($params[0], $params[1], $params[2]);
                break;
            
            default:
                return call_user_func_array(array($this, $method), $params);
                break;
        }
    }

    public function scores()
    {
        $coursemember = $this->repository->getCourseMember();
        
        $this->template->build('scores', compact('coursemember'));
    }

    public function examscores($courseid)
    {


        $exam = $this->repository->examByCourse($courseid);// get exam member

        
        $correct    = 0;
        $uncorrect  = 0;
        $scores     = 0;

        echo '<table class="table">';

        // Start Exam Foreach 
        foreach ($exam as $key => $value) {
           

            $quistion = $this->repository->questionList($value->id); //get questioin
                // Start Question Foreach 
                $no=1;
                foreach ($quistion as $key => $vq) {

                    
                    
                    $learneranswer = $this->repository->learnerAnswer($value->members[0]->id, $vq->id);
                    // Start Learner Answer Foreach 
                    foreach ($learneranswer as $key => $vAns) {

                        if ($vAns->is_correct == '1') {
                            $correct   = $correct + 1;
                            $scores    = $scores + 10;
                            $hasil     = "<span style='color:green'>Benar</span>";
                            $jawabanlearner = "<span style='color:green'>Jawaban Learner: ".$vAns->answer."</span>";
                        } else {
                            $uncorrect = $uncorrect + 1;
                            $scores    = $scores;
                            $hasil     = "<span style='color:red'>Salah</span>";
                            $jawabanlearner = "<span style='color:red'>Jawaban Learner: ".$vAns->answer."</span>";
                        }
                        
                        echo $no.". ".strip_tags($vq->question)."  <br>&nbsp;&nbsp;&nbsp;<b>(<span style='color:green'>Kunci jawaban: ".$vq->correct."</span>)</b> - <b>(".$jawabanlearner.") - ".$hasil."</b><br><br>";                
                        
                        
                    }
                    // End Learner Answer Foreach



                    
                $no++;
                }
                // End Question Foreach


           


                echo "<br>";
                echo "<b>Total Benar : </b>".$correct."<br>";
                echo "<b>Total Salah : </b>".$uncorrect."<br>";
                echo "<b>Scores : </b>".$scores."<br>";

                $correct    = 0;
                $uncorrect  = 0;
                $scores     = 0;


            
        }
        // End Exam Foreach 

        echo '</table>';

        
        
        exit();
    }

    public function quizscores($courseid)
    {
        $chapter = $this->repository->chapterByCourseId($courseid);

        $correct    = 0;
        $uncorrect  = 0;
        $scores     = 0;
        
        echo '<table clas="table">';
            
     
        foreach ($chapter as $key => $value) {

         
            echo $value->name."<br>";
            
            $quiz = $this->repository->quizByChapterId($value->id);             
            foreach ($quiz as $key => $vquiz) {
                
                
                $question = $this->repository->quizQuestionList($vquiz->id);
                $no = 1;
                foreach ($question as $key => $vques) {
                   

                    $learneranswer = $this->repository->learnerQuizAnswer($vquiz->members[0]->id, $vques->id);
                    foreach ($learneranswer as $key => $vAns) {
                        
                        if ($vAns->is_correct == '1') {
                            $correct   = $correct + 1;
                            $scores    = $scores + 10;
                            $hasil     = "<span style='color:green'>Benar</span>";
                            $jawabanlearner = "<span style='color:green'>Jawaban Learner: ".$vAns->answer."</span>";
                        } else {
                            $uncorrect = $uncorrect + 1;
                            $scores    = $scores;
                            $hasil     = "<span style='color:red'>Salah</span>";
                            $jawabanlearner = "<span style='color:red'>Jawaban Learner: ".$vAns->answer."</span>";
                        }
                        
                        echo $no.". ".strip_tags($vques->question)."  <br>&nbsp;&nbsp;&nbsp;<b>(<span style='color:green'>Kunci jawaban: ".$vques->correct."</span>)</b> - <b>(".$jawabanlearner.") - ".$hasil."</b><br><br>";                
                        
                    }


                $no++;
                }


                echo "<br>";
                echo "<b>Total Benar : </b>".$correct."<br>";
                echo "<b>Total Salah : </b>".$uncorrect."<br>";
                echo "<b>Scores : </b>".$scores."<br>";

                $correct    = 0;
                $uncorrect  = 0;
                $scores     = 0;
                    
                
                
                
            }

         

        }

        echo "</table>";

        exit();
    }


}

/* End of file Course.php */
/* Location: ./application/modules/dashboard/controllers/Course.php */