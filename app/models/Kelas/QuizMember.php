<?php

namespace Model\Kelas;

use Model\User;

class QuizMember extends Model
{
	protected $table = 'member_quiz';
	protected $dates = ['started_at', 'finished_at'];
	protected $guarded = [];
	public $timestamps = false;

	public static function boot()
	{
		parent::boot();

		QuizMember::deleting(function ($quiz_member) {
			$quiz_member->answers()->delete();
		});
	}

	public function quiz()
	{
		return $this->belongsTo(Quiz::class);
	}

	public function answers()
	{
		return $this->hasMany(QuizAnswer::class, 'member_quiz_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function getScore()
	{
		$quiz		= $this->quiz;
		$answers 	= $this->answers;
		$correct	= $answers->sum('is_correct');

		$point		= 100 / $quiz->questions->count();
		$score		= $point * $correct;

		return $score;
	}
}