<?php

namespace Library\Kelas;

use Carbon\Carbon;
use Model\User;
use Model\Kelas\ExamMember;
use Model\Kelas\QuizMember;

trait MemberTrait
{
    public function addMember($user, $status = 'pending')
    {
        if (!$this->isMember())
            $this->model->members()->attach($user, ['status' => $status, 'joined_at' => Carbon::now()]);

        return $this;
    }

    public function isMember($status = null)
    {
        if ($status) {
            $member = $this->getMemberStatus($status)->filter(function ($member) {
                return $member->id === $this->user->id;
            })->first();
        } else {
            $member = $this->getMembers()->filter(function ($member) {
                return $member->id === $this->user->id;
            })->first();
        }

        return $member;
    }

    public function approveMember($user)
    {
        $this->model->members()->updateExistingPivot($user, ['status' => 'active']);

        return $this;
    }

    public function deleteMember($user = null)
    {
        if ($user)
            $this->setUser($user);

        ExamMember::where('user_id', $this->user->id)->whereHas('exam', function ($query) {
            $query->where('course_id', $this->model->id);
        })->delete();

        QuizMember::where('user_id', $this->user->id)->whereHas('quiz', function ($quiz) {
            $quiz->whereHas('chapter', function ($chapter) {
                $chapter->where('course_id', $this->model->id);
            });
        })->delete();

        $this->model->members()->detach($user);

        return $this;
    }

    public function getMembers()
    {
        return $this->model->members;
    }

    public function getMemberActive()
    {
        return $this->getMemberStatus('active');
    }
    
    public function getMemberStatus($status)
    {
        if ($status === 'all')
            return $this->getMembers();

        return $this->model->members->filter(function ($member) use ($status) {
            return $member->pivot->status === $status;
        });
    }

    public function allowExam()
    {
        return false;
    }

    public function hasCertificate()
    {
        return false;
    }
}