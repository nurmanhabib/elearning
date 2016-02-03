<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//HAE
class Course extends Admin
{
    protected $repository;
    protected $roles = [];

    public function __construct()
    {
        parent::__construct();

        $this->template->set_layout('chapter');
        
        $this->repository = new Library\Kelas\CourseRepository;
    }

    public function show($slug)
    {
        $course                 = $this->repository->getBySlug($slug);
        $repository             = $this->repository;
        $course_member_status   = $repository->courseMemberStatus($slug); //get status member course
        $expired                = $repository->getMemberExpired();
        $relevance              = $repository->relevance();

        $this->template->build('show', compact('course', 'repository', 'course_member_status', 'expired', 'relevance'));
    }

    public function join($slug)
    {
        $this->repository->set($slug);
        
        $course         = $this->repository->get();
        $requirements   = $this->repository->checkRequirements();
        $repository     = $this->repository;

        if (!$requirements->isEmpty()) {
            $this->template->build('requirement', compact('requirements', 'course', 'repository'));
        } else {
            $this->repository->addMember(sentinel()->getUser());
            $course = $this->repository->get();

            redirect('course/show/'.$course->slug, 'refresh');
        }
    }

    public function rejoin($slug)
    {
        $this->repository->set($slug);
        
        $course         = $this->repository->get();
        $requirements   = $this->repository->checkRequirements();
        $repository     = $this->repository;

        if ($repository->memberAllowCourse($course)) {
            redirect('course/show/'.$course->slug, 'refresh');
        } elseif ($repository->isMember()) {
            $repository->deleteMember(sentinel()->getUser());

            redirect('course/join/'.$course->slug, 'refresh');
        } else {
            redirect('course/show/'.$course->slug, 'refresh');
        }
    }

    public function chapter($slug)
    {
        $course     = $this->repository->getBySlug($slug);
        $repository = $this->repository;
        $expired    = $repository->getMemberExpired();

       
        //$examscore  = $this->repository->examScore($course->id);// get exam score
        $examscore  = $this->repository->learnerExamMember($course->id);
       
        if ($repository->memberAllowCourse($course)) {
            if ($this->repository->isMember()) {
                $course_member_status   = $repository->courseMemberStatus($slug); //get status member course
                
                $this->template->build('chapter', compact('course', 'repository', 'course_member_status', 'expired','examscore'));
            } else {
                redirect('course/show/' . $course->slug, 'refresh');
            }
        } else {
            $this->template->build('course_expired', compact('course', 'repository', 'course_member_status'));
        }
    }

    public function showchapter($slug, $chapter)
    {
        $chapter        = str_replace('chapter-', '', $chapter);
        $course         = $this->repository->getBySlug($slug);
        $repository     = $this->repository;
        $chapter        = $course->chapters()->whereOrder($chapter)->firstOrFail();
        $nextchapter    = $chapter->getNext();
        $expired        = $repository->getMemberExpired();
        $course_member_status   = $repository->courseMemberStatus($slug); //get status member course

        if (($repository->isMember('active') || $repository->isMember('finished')) && $repository->memberAllowChapter($chapter)) {
            $this->template->build('chapter_show', compact('course', 'repository', 'chapter', 'nextchapter', 'expired','course_member_status'));
        } else {
            redirect('course/show/'.$course->slug, 'refresh');
        }
    }

    public function showquiz($slug, $chapter)
    {
        $chapter        = str_replace('chapter-', '', $chapter);
        $course         = $this->repository->getBySlug($slug);
        $repository     = $this->repository;
        $chapter        = $course->chapters()->whereOrder($chapter)->firstOrFail();
        $quiz           = $chapter->quiz;
        $nextchapter    = $chapter->getNext();

        if (!$repository->memberHasFinishedChapter($chapter)) {
            $repository->startQuiz($quiz);

            if ($repository->checkQuizTimeout($quiz)) {
                if ($repository->memberAllowChapter($chapter)) {
                    $member     = $repository->getMemberSessionQuiz($quiz);

                    $this->template->set_layout('chapter_quiz');
                    $this->template->build('quiz_show', compact('course', 'quiz', 'repository', 'chapter', 'nextchapter', 'member'));
                } else {
                    redirect('course/show/'.$course->slug, 'refresh');
                }
            } else {
                $this->template->build('quiz_timeout', compact('course', 'quiz', 'repository', 'chapter', 'nextchapter', 'member'));
            }
        } else {
            redirect('course/show/'.$course->slug.'/chapter-'.$chapter->order, 'refresh');
        }
    }

    public function submitquiz($slug, $chapter)
    {
        $chapter        = str_replace('chapter-', '', $chapter);
        $course         = $this->repository->getBySlug($slug);
        $repository     = $this->repository;
        $chapter        = $course->chapters()->whereOrder($chapter)->firstOrFail();
        $quiz           = $chapter->quiz;

        $submit         = $this->repository->submitQuizMember($quiz, $this->input->post('answers'));

        if ($submit) {
            redirect('course/showchapter/'.$course->slug.'/chapter-'.$chapter->order, 'refresh');
        } else {
            redirect('course/showchapter/'.$course->slug.'/chapter-'.$chapter->order, 'refresh');
        }
    }

    public function showexam($slug)
    {
        $course         = $this->repository->getBySlug($slug);
        $repository     = $this->repository;
        $exam           = $course->exam;
        
        $check = $repository->startExam($exam);

        if ($repository->checkExamTimeout($exam)) {
            $member     = $repository->getMemberSessionExam($exam);

            $this->template->set_layout('exam_layout');
            $this->template->build('exam_show', compact('course', 'exam', 'repository', 'member'));
        } else {
            $this->template->build('quiz_timeout', compact('course', 'exam', 'repository'));
        }
    }

    public function submitexam($slug)
    {
        $course         = $this->repository->getBySlug($slug);
        $repository     = $this->repository;
        $exam           = $course->exam;

        $submit         = $this->repository->submitExamMember($exam, $this->input->post('answers'));

        if ($submit) {
            redirect('course/chapter/'.$course->slug, 'refresh');
        } else {
            redirect('course/chapter/'.$course->slug, 'refresh');
        }
    }

    public function download($filename)
    {
        $this->load->helper('download');

        $attachment     = Model\Kelas\Attachment::findByHashids($filename);
        $content        = file_get_contents($attachment->filepath);

        force_download($attachment->filename, $content);
    }

    public function printedcertificate($slug)
    {
        $this->load->helper('dompdf');

        $course         = $this->repository->getBySlug($slug);
        $exam           = $course->exam;
        $member         = $this->repository->getMemberExam($exam)->filter(function ($member) {
            return $member->user_id == sentinel()->getUser()->id;
        })->first();
        
        // create pdf using dompdf
        $html           = $this->load->view('certificate', compact('course', 'member'), true);        
        $filename       = 'Sertifikat ' . $course->code;
        $paper          = 'A4';
        $orientation    = 'landscape';
        $stream         = FALSE;

        pdf_create($html, $filename, $paper, $orientation, $stream);
    }

    public function quizscores($chapterid)
    {
        $chapter    = $this->repository->chapterById($chapterid);
        $quiz       = $this->repository->quizLearnerByChapterId($chapter->id);
        $answers    = $this->repository->learnerQuizAnswer($chapter->id);
        $member     = $this->repository->learnerQuizMember($chapter->id);

        $this->load->view('member_quiz_answer', compact('chapter', 'quiz', 'answers', 'member'));
    }

    public function examscores($courseid)
    {

        $this->repository->set($courseid);
        
        $course     = $this->repository->get();
        $exam       = $course->exam;
        $answers    = $this->repository->learnerExamAnswer($course->id);
        $member     = $this->repository->learnerExamMember($course->id);

        $this->load->view('member_exam_answer', compact('course', 'exam', 'answers', 'member'));

    }
}

/* End of file Course.php */
/* Location: ./application/modules/course/controllers/Course.php */