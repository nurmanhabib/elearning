<?php

namespace Model\Kelas;

class ExamAnswer extends Model
{
    protected $table = 'member_exam_answers';
    public $timestamps = false;
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(ExamMember::class, 'member_exam_id');
    }

    public function question()
    {
    	return $this->belongsTo(ExamQuestion::class);
    }
}