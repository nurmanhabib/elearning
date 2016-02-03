<?php

namespace Model\Kelas;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Model\User;
use Carbon\Carbon;

class Chapter extends Model
{
	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function quiz()
	{
		return $this->hasOne(Quiz::class);
	}

	public function attachments()
	{
		return $this->hasMany(Attachment::class);
	}

    public function comments()
    {
        return $this->hasMany(ChapterComment::class);
    }

    public function getBefore()
    {
        if ($this->order > 1) {
            return $this->newQuery()->where('course_id', $this->course_id)->where('order', $this->order-1)->first();
        }

        return null;
    }

    public function getNext()
    {
        try {
            return $this->newQuery()->where('course_id', $this->course_id)->where('order', $this->order+1)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function getContentPathAttribute()
    {
    	return $this->course->content_path . '/chapter_' . $this->id;
    }

    public function hasQuiz()
    {
    	return !$this->quiz->questions->isEmpty();
    }

    public function hasQuizMember(User $user, $attempt = 1)
    {
    	$member = $this->quiz->members->filter(function ($member) use ($user, $attempt) {
    		return $member->attempt == $attempt && $member->user_id == $user->id;
    	});

    	if ($member->count())
            return $member->first();
        else
            return null;
    }

    public function memberHasFinished(User $user, $attempt = 1)
    {
    	if ($this->hasQuiz()) {
    		if ($member = $this->hasQuizMember($user, $attempt)) {
                $quiz       = $member->quiz;
                $answers    = $member->answers;

                return (bool) $member->submited || Carbon::now()->diffInSeconds($member->finished_at, false) < 0;
            } else {
                return false;
            }
    	}

    	return true;
    }
}