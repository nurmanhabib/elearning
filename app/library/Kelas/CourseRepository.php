<?php

namespace Library\Kelas;

use Model\Kelas\Course;
use Model\Kelas\CourseMember;
use Model\Kelas\Category;
use Model\Kelas\Chapter;
use Model\Kelas\ChapterComment;
use Model\Kelas\Attachment;
use Model\Kelas\Quiz;
use Model\Kelas\QuizQuestion;
use Model\Kelas\QuizAnswer;
use Model\Kelas\QuizMember;
use Model\Kelas\Exam;
use Model\Kelas\ExamQuestion;
use Model\Kelas\ExamAnswer;
use Model\Kelas\ExamMember;
use Model\User;
use Carbon\Carbon;

class CourseRepository
{
    use CategoryTrait;
    use CountingTrait;
    use MemberTrait;

    protected $model;
    protected $user;

    public function __construct($course = null, $user = null, $category = null)
    {
        $this->set($course);
        $this->setUser($user);
        $this->setCategory($category);
    }

    public function set($course)
    {
        if ($course instanceof Course)
            $this->model = $course;
        elseif (is_numeric($course))
            $this->model = Course::withDrafts()->findOrFail($course);
        elseif (is_string($course))
            $this->model = Course::withDrafts()->findBySlug($course);
        else
            $this->model = new Course;

        return $this;
    }

    public function get()
    {
        return $this->model->load('chapters.quiz.questions', 'chapters.quiz.questions', 'instructor', 'members', 'requirements');
    }

    public function getBySlug($slug)
    {
        $this->model = $this->model->findBySlug($slug);

        return $this->get();
    }

    public function getByCategory($slug)
    {
        if (is_string($slug)) {
            $category   = $this->getCategory()->findBySlug($slug);
        } else {
            $category   = $this->getCategory()->find($slug);
        }

        return $category->courses;
    }

    public function all()
    {
        return $this->getByStatus('all');
    }

    public function allExcept($id, $status = 'all')
    {
        $courses    = $this->getByStatus($status);
        $courses    = $courses->reject(function ($course) use ($id) {
            return $course->id == $id;
        });

        return $courses;
    }

    public function getOnlyPublish()
    {
        return $this->getByStatus('publish');
    }

    public function getOnlyDraft()
    {
        return $this->getByStatus('draft');
    }

    public function getByStatus($status = 'publish')
    {
        $course     = $this->model->newQueryWithoutScopes();

        if ($status !== 'all')
            $course->where('status', $status);

        return $course->latest()->get();
    }

    public function setUser($user)
    {
        if ($user instanceof User)
            $this->user = $user;
        elseif (is_numeric($user))
            $this->user = User::findOrFail($user);
        else
            $this->user = sentinel()->getUser();

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function attachCategory($category)
    {
        $this->model->category()->associate($category);
        $this->model->save();

        return $this;
    }

    public function attachInstructor($instructor)
    {
        $this->model->instructor()->associate($this->user);
        $this->model->save();

        return $this;
    }

    public function create($name, $description, $days, $category)
    {
        $this->model = Course::create(compact('name', 'description', 'days'));

        $this->attachCategory($category);
        $this->attachInstructor($this->user);

        if (!is_dir($this->model->content_path)) {
            mkdir($this->model->content_path, 0775);
        }

        $this->model->exam()->create([
            'name'  => '',
            'time'  => 0,
        ]);

        return $this;
    }

    public function saveTo($status = 'publish')
    {
        $this->model->update(compact('status'));

        return $this;
    }

    public function saveToPublish()
    {
        return $this->saveTo('publish');
    }

    public function saveToDraft()
    {
        return $this->saveTo('draft');
    }

    public function update($name, $description, $days = 0)
    {
        $this->model->update(compact('name', 'description', 'days'));

        return $this;
    }

    public function delete($course = null)
    {
        if ($course) $this->set($course);

        $this->model->chapters->each(function ($chapter) {
            $this->deleteChapter($chapter);
        });

        $this->model->delete();

        return $this;
    }

    public function addChapter($name, $content, $attachments = [], $quiz = [])
    {
        $chapter = Chapter::fill(compact('name', 'content'));
        $chapter->course()->associate($this->model);
        $chapter->save();

        if (!is_dir($chapter->content_path)) {
            mkdir($chapter->content_path, 0775);
        }

        if (count($attachments)) {

        }

        $quiz = Quiz::create(['name' => '', 'time' => 0]);
        $quiz->chapter()->associate($chapter);
        $quiz->save();

        foreach ($quiz as $question) {
            $this->addChapterQuiz($chapter, $question);
        }
    }

    public function deleteChapter($chapter)
    {
        if ($chapter instanceof Chapter)
            $chapter = $this->model->chapters()->findOrFail($chapter->id);
        else
            $chapter = $this->model->chapters()->findOrFail($chapter);

        $chapter->attachments->each(function ($attachment) {            
            $this->deleteChapterAttachment($attachment);
        });

        $chapter->quiz->questions->each(function ($question) {            
            $this->deleteChapterQuiz($question);
        });

        $chapter->delete();

        return $this;
    }

    public function addChapterAttachment($chapter, $file)
    {
        if ($chapter instanceof Chapter)
            $chapter = $this->model->chapters()->findOrFail($chapter->id);
        else
            $chapter = $this->model->chapters()->findOrFail($chapter);

        $file       = collect($file);
        $filename   = $file->get('filename');
        $filtype    = $file->get('filetype');
        $filesize   = $file->get('filesize');

        $attachment = $chapter->attachments()->create(compact('filename', 'filetype', 'filesize'));

        $file_encoded   = $file->get('dataurl');
        $file_encoded   = explode(',', $file_encoded);
        $file_base64    = $file_encoded[1];

        $fp = fopen($attachment->filepath, 'w+');
        fwrite($fp, base64_decode($file_base64));
        fclose($fp);

        return $this;
    }

    public function deleteChapterAttachment($attachment)
    {
        if ($attachment instanceof Attachment)
            $attachment = $this->model->attachments()->findOrFail($attachment->id);
        else
            $attachment = $this->model->attachments()->findOrFail($attachment);

        if (file_exists($attachment->filepath)) {
            unlink($attachment->filepath);
        }

        $attachment->delete();

        return $this;
    }

    public function addChapterQuiz($chapter, $questions = [])
    {
        if ($chapter instanceof Chapter)
            $chapter = $this->model->chapters()->findOrFail($chapter->id);
        else
            $chapter = $this->model->chapters()->findOrFail($chapter);

        $chapter->quiz->questions()->create($questions);

        return $this;
    }

    public function deleteChapterQuiz($question)
    {
        if ($question instanceof QuizQuestion) {
            // 
        } else {
            $question = QuizQuestion::findOrFail($question);
        }

        $question->delete();

        return $this;
    }

    public function addExamQuestion(array $question)
    {
        $exam   = new ExamQuestion;
        $exam->fill($question);
        $exam->exam()->associate($this->model->exam);
        $exam->save();

        return $this;
    }

    public function deleteExamQuestion($question)
    {
        if ($question instanceof ExamQuestion) {
            $question   = $this->model->exam->questions()->findOrFail($question->id);
        } else {
            $question   = $this->model->exam->questions()->findOrFail($question);
        }

        $question->delete();

        return $this;
    }

    public function addRequirement($course)
    {
        $this->model->requirements()->attach($course);

        return $this;
    }

    public function deleteRequirement($course)
    {
        $this->model->requirements()->detach($course);

        return $this;
    }

    public function checkRequirements($course = null, $user = null)
    {
        if ($course) $this->set($course);
        if ($user) $this->set($user);

        $requirements   = $this->model->requirements;
        $user_finished  = $this->getByMemberFinished($this->user);

        $requirement_finished   = $user_finished->filter(function ($course) use ($requirements) {
            return $requirements->search(function ($requirement) use ($course) {
                return $requirement->id === $course->id;
            });
        });

        $be_requirements = $requirements->diff($requirement_finished);

        return $be_requirements;
    }

    public function getPopular($limit = 20)
    {
        return $this->model->popular()->take($limit)->get();
    }

    public function getLatest($limit = 20)
    {
        return $this->model->latest()->take($limit)->get();
    }

    public function getByMemberActive($member)
    {
        return $this->getByMember($member, 'active');
    }

    public function getByMemberPending($member)
    {
        return $this->getByMember($member, 'pending');
    }

    public function getByMemberFinished($member)
    {
        return $this->getByMember($member, 'finished');
    }

    public function getByMember($member, $status = 'all')
    {
        if (!($member instanceof User)) {
            $member = User::findOrFail($member);
        }

        $courses = $this->model->newQuery();
        $courses->select('courses.*', 'course_member.status', 'course_member.joined_at');
        $courses->join('course_member', 'course_member.course_id', '=', 'courses.id');
        $courses->where('course_member.user_id', $member->id);

        if ($status && $status !== 'all')
            $courses->where('course_member.status', $status);

        return $courses->get();
    }

    public function search($term)
    {
        $course     = $this->model->newQuery();
        $result     = $course->search($term);

        return $result;
    }

    public function searchWithCategory($term, $category)
    {
        $this->setCategory($category);

        $course     = $this->model->newQuery();

        $course->whereHas('category', function ($query) {
            return $query->where('id', $this->category->id);
        });

        $result     = $course->search($term);

        return $result;
    }

    public function hasFeaturedImage()
    {
        return $this->model->hasFeatured();
    }

    public function memberHasFinishedChapter(Chapter $chapter, $attempt = 1)
    {
        return $chapter->memberHasFinished($this->user, $attempt);
    }

    public function memberAllowChapter(Chapter $chapter)
    {
        $chapters   = $this->model->chapters;
        $first      = $chapters->first();

        if ($first->id === $chapter->id) {
            return true;
        } else {
            $before = $first;

            foreach ($chapters as $item) {
                if ($item->id === $chapter->id)
                    return $before->memberHasFinished($this->user);

                $before = $item;
            }

            return false;
        }
    }

    public function startQuiz(Quiz $quiz, $attempt = 1)
    {
        if (!$this->checkQuizMember($quiz)) {
            $quiz->members()->create([
                'user_id'       => $this->user->id,
                'attempt'       => $attempt,
                'started_at'    => Carbon::now(),
                'finished_at'   => Carbon::now()->addMinutes($quiz->time),
            ]);
        }
    }

    public function getMemberSessionCourse(Course $course)
    {
        $member = $course->members()->where('user_id', $this->user->id)->first();

        return $member;
    }

    public function getMemberSessionQuiz(Quiz $quiz)
    {
        if ($this->checkQuizTimeout($quiz)) {
            $member = $quiz->members()->where('user_id', $this->user->id)->first();

            return $member;
        } else {
            return null;
        }
    }

    public function getMemberQuiz(Quiz $quiz)
    {
        return $quiz->members;
    }

    public function checkQuizMember(Quiz $quiz, User $user = null)
    {
        if ($user) $this->setUser($user);

        $search = $this->getMemberQuiz($quiz)->search(function ($member) {
            return $member->user_id === $this->user->id;
        });

        if ($search !== FALSE) {
            return true;
        } else {
            return false;
        }
    }

    public function checkQuizTimeout(Quiz $quiz, $attempt = 1)
    {
        $member = $quiz->members()->where('user_id', $this->user->id)->first();

        if ($member) {
            if ($member->finished_at->diffInSeconds(Carbon::now(), false) < 0) {
                return true;
            }
        }

        return false;
    }

    public function submitQuizMember(Quiz $quiz, $answers = [])
    {
        $member = $this->getMemberSessionQuiz($quiz);

        if ($member) {
            $answers = collect($answers)->map(function ($answer, $question_id) {
                $question = QuizQuestion::find($question_id);

                return new QuizAnswer([
                    'question_id'   => $question_id,
                    'answer'        => $answer,
                    'is_correct'    => $question->correct == $answer,
                ]);
            });

            $member->answers()->saveMany($answers);
            $member->update(['submited' => true]);

            return $member->answers;
        }

        return null;
    }

    public function getScoreQuizAnswer($chapter_id)
    {
        $quiz           = Quiz::where('chapter_id', $chapter_id)->get(); // get quiz id
        
        $quiz_id = '';
        foreach ($quiz as $key => $value) 
        {
            
            if ($value->id != '') 
            {
                $quiz_id = $value->id;
            }

        }
        
        $quizMember     = QuizMember::where('quiz_id', $quiz_id)->where('user_id', sentinel()->getUser()->id)->get();
        // get quiz member id

        $quiz_member_id = '';
        foreach ($quizMember as $key => $value) 
        {
            
            if ($value->id != '') 
            {
                $quiz_member_id = $value->id;
            }

        }

        $quizAnswer     = QuizAnswer::where('member_quiz_id',$quiz_member_id)->get();
        // get quiz answer


        return $quizAnswer;

    }

    public function checkExamTimeout(Exam $exam, $attempt = 1)
    {
        $member = $exam->members()->where('user_id', $this->user->id)->first();

        if ($member) {
            if ($member->finished_at->diffInSeconds(Carbon::now(), false) < 0) {
                return true;
            }
        }

        return false;
    }

    public function getMemberSessionExam(Exam $exam)
    {
        if ($this->checkExamTimeout($exam)) {
            $member = $exam->members()->where('user_id', $this->user->id)->first();

            return $member;
        } else {
            return null;
        }
    }

    public function getMemberExam(Exam $exam)
    {
        return $exam->members;
    }

    public function checkExamMember(Exam $exam, User $user = null)
    {
        if ($user) $this->setUser($user);

        $search = $this->getMemberExam($exam)->search(function ($member) {
            return $member->user_id === $this->user->id;
        });

        if ($search !== FALSE) {
            return true;
        } else {
            return false;
        }
    }

    public function startExam(Exam $exam, $attempt = 1)
    {
        if (!$this->checkExamMember($exam)) {
            $exam->members()->create([
                'user_id'       => $this->user->id,
                'attempt'       => $attempt,
                'started_at'    => Carbon::now(),
                'finished_at'   => Carbon::now()->addMinutes($exam->time),
            ]);
        }
    }

    public function submitExamMember(Exam $exam, $answers = [])
    {
        $memberExam     = $this->getMemberSessionExam($exam);
        $course         = $exam->course;

        if ($memberExam) {
            $answers = collect($answers)->map(function ($answer, $question_id) {
                $question = ExamQuestion::find($question_id);

                return new ExamAnswer([
                    'question_id'   => $question_id,
                    'answer'        => $answer,
                    'is_correct'    => $question->correct == $answer,
                ]);
            });

            $memberExam->answers()->saveMany($answers);

            $course->updateStatus($memberExam->user, 'finished');

            return $memberExam->answers;
        }

        return null;
    }

    public function courseMemberStatus($slug)
    {
        $course_id          = Course::findBySlug($slug);

        $member_course      = CourseMember::with('user')->where('course_id', $course_id->id)
                                                ->where('user_id', $this->user->id)
                                                ->get();

        return $member_course;
    }

    public function getMemberExpired()
    {
        $course = $this->model;
        $member = $this->findMemberCourse($this->user, $course);

        if (!empty($member)) {
            $joined_at  = Carbon::parse($member->pivot->joined_at);
            $expired    = $joined_at->copy()->addDays($course->days);

            return $expired->endOfDay();
        }

        return null;
    }

    public function memberAllowCourse(Course $course)
    {
        $member = $this->findMemberCourse($this->user, $course);

        if (!empty($member)) {
            if ($member->status === 'finished')
                return true;

            $joined_at  = Carbon::parse($member->pivot->joined_at);
            $expired    = $joined_at->copy()->addDays($course->days);
            $diff       = Carbon::today()->endOfDay()->diffInDays($expired, false);

            if ($diff >= 0)
                return true;
        }
        
        return false;
    }

    public function findMemberCourse(User $user, Course $course)
    {
        $member = $course->members()->find($user->id);

        return $member;
    }

    public function memberAllowExam(Course $course)
    {
        $chapters   = $course->chapters;

        $finished   = $chapters->filter(function ($chapter) {
            return $this->memberHasFinishedChapter($chapter);
        });

        return $chapters->count() === $finished->count();
    }

    public function relevance()
    {
        $course     = $this->model;
        $relevances = $this->model->category->courses()->whereNotIn('id', [$this->model->id]);

        return $relevances->get();
    }

    public function examByCourse($courseid)
    {
        $exam = Exam::with('members')->where('course_id',$courseid)->get();

        return $exam;
    }


    public function questionList($examid)
    {
        $question = ExamQuestion::where('exam_id', $examid)->get();

        return $question;
    }

    public function learnerAnswer($examMemberId, $questionId)
    {
        $answer = ExamAnswer::where('member_exam_id', $examMemberId)
                            ->where('question_id', $questionId)
                            ->get();

        return $answer;
    }

    
   
    public function chapterById($id)
    {
        $chapter = Chapter::where('id', $id)->first();

        return $chapter;
    }

    public function quizByChapterId($chapterid)
    {
        $quiz = Quiz::with('members')->where('chapter_id', $chapterid)->get();

        return $quiz;
    }


    public function quizQuestionList($quizid)
    {
        $quizQuestion = QuizQuestion::where('quiz_id', $quizid)->get();

        return $quizQuestion;
    }

    public function learnerQuizAnswer($quizMemberId, $quesId)
    {
        $answer = QuizAnswer::where('member_quiz_id', $quizMemberId)
                            ->where('question_id', $quesId)
                            ->get();

        return $answer;
    }

    
    public function quizLearnerByChapterId($chapterid)
    {
        $quiz = Quiz::with('members')
                    ->whereHas('members', function ($member) {
                        return $member->where('user_id', $this->user->id);
                    })
                    ->where('chapter_id', $chapterid)->get();

        return $quiz;
    }

    public function examLearnerByCourse($courseid)
    {
        $exam = Exam::with('members')
                    ->whereHas('members', function ($member) {
                        return $member->where('user_id', $this->user->id);
                    })
                    ->where('course_id',$courseid)->get();

        return $exam;
    }

    public function approveReview($course, $review)
    {
        $course = $this->model->find($course);
        $review = $course->comments()->withDrafts()->get()->first(function ($key, $comment) use ($review) {
            return $comment->id == $review;
        });

        $review->update(['status' => 'publish']);

        return $this;
    }

    public function deleteReview($course, $review)
    {
        $course = $this->model->find($course);
        $review = $course->comments()->withDrafts()->get()->first(function ($key, $comment) use ($review) {
            return $comment->id == $review;
        });

        $review->replies()->delete();
        $review->delete();

        return $this;
    }

    public function approveChapterComment($id)
    {
        $comment = ChapterComment::withDrafts()->find($id);
        $comment->update(['status' => 'publish']);

        return $this;
    }

    public function deleteChapterComment($id)
    {
        $comment = ChapterComment::withDrafts()->find($id);
        $comment->delete();

        return $this;
    }

    public function examScore($courseid)
    {
        
        $exam = $this->examLearnerByCourse($courseid);
        
        $correct    = 0;
        $scores     = 0;

        foreach ($exam as $key => $value) {
            
            $question = $this->questionList($value->id); //get questioin
            $no=1;
            foreach ($question as $key => $vq) {
            
                $learneranswer = $this->learnerAnswer($value->members[0]->id, $vq->id);
                // Start Learner Answer Foreach 
                foreach ($learneranswer as $key => $vAns) {

                    if ($vAns->is_correct == '1') {
                        $correct   = $correct + 1;
                        $scores    = $scores + 10;
                    } else {
                        $scores    = $scores;
                    }
                                
                                
                                
                }
                // End Learner Answer Foreach



                        
            $no++;
            }
                    // End Question Foreach


            $jumlahsoal = $no-1;
            $soal = 100/$jumlahsoal;
            $scores = $soal*$correct;


        }


        return $scores;
        


    }



}