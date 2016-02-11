<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {    
    public function __construct()
    {
        parent::__construct();

        $this->template->set('slider', false);
        $this->template->set('railnews', false);
        $this->template->set('sidebar', false);
        $this->template->set('single', true);
        $this->template->set('sidebarCategory', false);
    }
}

class Admin extends CI_Controller
{

    protected $roles = array();

    public function __construct()
    {
        parent::__construct();

        $this->check();
    }

    public function check()
    {
        if (!sentinel()->check()) {
            redirect(login_url(), 'refresh');
        }

        if (!sentinel()->inRole($this->roles)) {
            set_message_error('Anda tidak mempunyai hak akses.');

            redirect(login_url(), 'refresh');
        }
    }

    public function checkRole()
    {
        
    }
}

class Guest extends Admin {
    public function check()
    {
        if (sentinel()->check()) {
            redirect(dashboard_url(), 'refresh');
        }
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */