<?php

namespace Model\Portal;

class Ads extends Model
{
	protected $table = 'ads';
	protected $size_defeault = [300, 70];
	protected $appends = ['width', 'height', 'image_url'];
	protected $guarded = [];

	public function getWidthAttribute()
	{
		$size = explode('x', $this->size);

		if (array_key_exists(0, $size)) {
			return $size[0];
		} else {
			return $this->size_defeault[0];
		}
	}

	public function getHeightAttribute()
	{
		$size = explode('x', $this->size);

		if (array_key_exists(1, $size)) {
			return $size[1];
		} else {
			return $this->size_defeault[1];
		}
	}

	public function getImageUrlAttribute()
	{
		return asset('ads/'.$this->image);
	}
}