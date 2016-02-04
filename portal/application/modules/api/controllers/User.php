<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$data['users'] = Model\User::all()->load('roles');

		$this->output
			->set_header('Access-Control-Allow-Credentials: true')
			->set_header('Access-Control-Allow-Origin: *')
			->set_header('Access-Control-Allow-Headers: POST, GET, PUT, DELETE, OPTIONS')
			->set_header('Access-Control-Allow-Headers: Content-Type,X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5,  Date, X-Api-Version, X-File-Name')
			->set_header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS')
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	public function login()
	{
		$email		= $this->input->post('email');
		$password	= $this->input->post('password');

		$login		= auth()->authenticate(compact('email', 'password'));

		if ($login) {
			$user = auth()->getUser()->load('roles');

			$data = [
				'success'	=> 'TRUE',
				'data'		=> $user,
			];
		} else {
			$data = [
				'success'	=> 'FALSE',
				'data'		=> [],
			];
		}

		$this->output
			->set_header('Access-Control-Allow-Credentials: true')
			->set_header('Access-Control-Allow-Origin: *')
			->set_header('Access-Control-Allow-Headers: POST, GET, PUT, DELETE, OPTIONS')
			->set_header('Access-Control-Allow-Headers: Content-Type,X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5,  Date, X-Api-Version, X-File-Name')
			->set_header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS')
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

}

/* End of file User.php */
/* Location: ./application/modules/api/controllers/User.php */