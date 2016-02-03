<?php

namespace Model\Kelas;

class QuizAnswer extends Model
{
	protected $table = 'member_quiz_answers';
	protected $guarded = [];

	public $timestamps = false;

	public function member()
	{
		return $this->belongsTo(QuizMember::class, 'member_quiz_id');
	}

	public function question()
	{
		return $this->belongsTo(QuizQuestion::class);
	}
}