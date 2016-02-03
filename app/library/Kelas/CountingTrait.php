<?php

namespace Library\Kelas;

use Model\Kelas\Chapter;

trait CountingTrait
{
	public function countRequirements()
	{
		return $this->model->requirements->count();
	}

	public function countMemberActive()
	{
		return $this->model->memberActive->count();
	}

	public function countMemberPending()
	{
		return $this->model->memberPending->count();
	}

	public function countMemberFinished()
	{
		return $this->model->memberFinished->count();
	}

	public function countMembers()
	{
		return $this->model->members->count();
	}

	public function countNewReviews()
	{
		return $this->model->comments()->onlyDrafts()->count();
	}

	public function countReviews()
	{
		return $this->model->comments()->count();
	}

	public function countNewChapterComment(Chapter $chapter = null)
	{
		if ($chapter)
			return $chapter->comments()->onlyDrafts()->count();

		$total = 0;

		foreach ($this->model->chapters as $chapter) {
			$total += $chapter->comments()->onlyDrafts()->count();
		};

		return $total;
	}

	public function countChapterComment(Chapter $chapter = null)
	{
		if ($chapter)
			return $chapter->comments()->count();

		$total = 0;

		foreach ($this->model->chapters as $chapter) {
			$total += $chapter->comments()->count();
		};

		return $total;
	}

	public function countChapters()
	{
		return $this->model->chapters->count();
	}

	public function countExams()
	{
		return $this->model->exam->questions->count();
	}
}