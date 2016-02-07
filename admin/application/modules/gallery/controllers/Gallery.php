<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends Admin {

	public function index()
	{
		$this->template->build('index');
	}

}

/* End of file Gallery.php */
/* Location: ./application/modules/gallery/controllers/Gallery.php */