<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Symfony\Component\HttpFoundation\Request;

class Api extends CI_Controller
{
	protected $request;

	public function __construct()
	{
		parent::__construct();

		$this->request = Request::createFromGlobals();
	}
}

/* End of file Api.php */
/* Location: ./application/modules/api/controllers/Api.php */