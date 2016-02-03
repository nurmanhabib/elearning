<?php

namespace Model\Kelas;

use Model\User;

class ExamMember extends Model
{
    protected $table = 'member_exam';
    protected $dates = ['started_at', 'finished_at'];
    protected $guarded = [];
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        ExamMember::deleting(function ($exam_member) {
            $exam_member->answers()->delete();
        });
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'member_exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getScore()
    {
        $exam       = $this->exam;
        $answers    = $this->answers;
        $correct    = $answers->sum('is_correct');

        $point      = 100 / $exam->questions->count();
        $score      = $point * $correct;

        return $score;
    }
    public function isPassing()
    {
        $course = $this->exam->course;

        if ($this->getScore() < $course->passing_standards)
            return false;
            return true;
    }
}