<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Symfony\Component\HttpFoundation\Request;
use Nurmanhabib\WilayahIndonesia\Sources\DatabaseSource;

class Dashboard extends Admin {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('SBBCodeParser');
        $this->load->helper(array('article', 'bbcodeparser'));
        $this->load->model('Mod_user', 'model');
        $this->load->model('Mod_sendarticle');
        $this->load->model('category/Mod_category');
        $this->load->model('Mod_konsultasi');
        $this->load->model('Mod_forum');
        $this->load->model('Mod_artikel');

        $this->medialib = new Library\Media\Media;

        $this->template->set('active', 'dashboard');

        $this->status = array(
            'publish'   => 'Publish',
            'draft'     => 'Draft',
        );

        $this->type = array(
            'public'        => 'Public',
            'private'       => 'Private',
        );
        
        $this->load->library('WilayahIndonesia', null, 'wilayah');

        $hostname = getenv('AUTH_DB_HOST') ?: 'localhost';
        $username = getenv('AUTH_DB_USERNAME') ?: 'root';
        $password = getenv('AUTH_DB_PASSWORD') ?: '';
        $database = getenv('AUTH_DB_DATABASE') ?: 'portal_learning';

        $source = new DatabaseSource($hostname, $username, $password, $database);
        $this->wilayah->setSource($source);
    }

    public function index()
    {
        $user       = auth()->getUser();
        $request    = Request::createFromGlobals();
        $articles   = Model\Portal\Article::withPrivate()->published()
                        ->contributor($user->id)
                        ->latest('date')
                        ->get();

        $drafts     = Model\Portal\Article::withPrivate()->onlyDrafts()
                        ->contributor($user->id)
                        ->latest('date')
                        ->get();


        $toptenarticles = Model\Portal\Article::groupBy('contributor_id')->orderBy('contributor_id','desc')->take(10)->get();
        
        $data['toptenarticlecount'] = Model\Portal\Article::selectRaw('count(`contributor_id`) as `occurences`')->groupBy('contributor_id')->orderBy('contributor_id','desc')->take(10)->get();
    

        $draftcount = $drafts->count();

        $courses        = Model\Kelas\Course::latest()->get();
        $myclasscourse  = Model\Kelas\CourseMember::where('user_id', $user->id)->has('course')->get();
        $coursecomments = Model\Kelas\CourseComment::where('parent', '0')->has('course')->get();
        
        $toptenactiveclass  = Model\Kelas\CourseMember::groupBy('user_id')->orderBy('user_id','desc')->take(10)->get();
        $data['toptenactiveclasscount'] = Model\Kelas\CourseMember::selectRaw('count(`user_id`) as `counttotal`')->groupBy('user_id')->orderBy('user_id','desc')->take(10)->get();
    
        /* Start Activity Konsultasi */
        $data['konsultasiCat']          = $this->Mod_konsultasi->getKonsultasiKategori();
        $data['latestReply']            = $this->Mod_konsultasi->getLatestReply($user->id);
        $konsultasi                     = collect($this->Mod_konsultasi->getKonsultasi($user->id));        
        $data['latestKonsultasi']       = $konsultasi;        
        /* End Activity Konsultasi */

        $category   = $this->medialib->getCategory();
        $categories = $category->with(['media' => function ($query) {
            $query->userId(sentinel()->getUser()->id)->withDrafts();
        }])->get();

        $data['categories']             = $categories;

        $data['categories_checkbox']    = (new Model\Portal\Category)->generateCheckbox();
        $data['artikel']                = pagination($articles, 4, 'dashboard/my-article');
        $data['drafts']                 = $drafts;
        $data['draftcount']             = $draftcount;
        $data['links']                  = $this->Mod_link->read();
        $data['toptenarticles']         = $toptenarticles;

        $data['toptenactiveclass']      = $toptenactiveclass;

        $data['courses']                = $courses;
        $data['myclasscourse']          = $myclasscourse;
        $data['coursecomments']         = $coursecomments;

        $data['forumNotif']             = $this->Mod_forum->getMemberNotif($user->id);
        $data['forumCategories']        = $this->Mod_forum->getCategoryMember($user->id);
        $latestComment                  = $this->Mod_forum->getLatestComment($user->id);
        $data['forumLatestComment']     = $latestComment;
        if(!empty($latestComment)){
            foreach($latestComment as $latest){
                $data['threadLatestComment']    = $this->Mod_forum->threadLatestComment($latest->reply_to);
            }
        }
        $data['allThreads']             = $this->Mod_forum->allThreads($user->id);
        $listNewThreadComments          = array();
        if(!empty($data['allThreads'])){
            foreach ($data['allThreads']  as $thr) {
                $listNewThreadComments[]    = $thr->id;
            }
            $data['newThreadComments']      = $this->Mod_forum->newComments($listNewThreadComments);
        }

        // START: Recent activity elibrary
        $data['recentMedia']    = $this->medialib->onlyUserId($user->id)->latestById()->slice(0, 5);
        // END: Recent activity elibrary
        
        
        $data['recentMedia']            = $this->medialib->onlyUserId($user->id)->latestById()->slice(0, 5);

        $data['recentArticleComment']   = $this->Mod_artikel->getRecentComment($user->id);
            
        $this->template->set('sidebar');
        $this->template->set_layout('privatepage');              
        $this->template->build('index', $data);
    }

    public function my_article()
    {
        $user       = auth()->getUser();
        $status     = $this->input->get('status') ?: 'publish';

        $articles   = Model\Portal\Article::withPrivate()->withDrafts()
                        ->status($status)
                        ->contributor($user->id)
                        ->latest('date')
                        ->get();

        $this->template->set('sidebar');
        $this->template->set_layout('privatepage');
        $this->template->build('index_draft', compact('articles'));
    }

    public function sendArticle()
    {
        $this->form_validation->set_rules('title', 'Title', 'required', array('required' => '<div class="alert alert-danger">Judul Artikel Wajib diisi</div>'));
        $this->form_validation->set_rules('content', 'Content', 'required', array('required' => '<div class="alert alert-danger">Content Artikel Wajib diisi</div>'));

        if ($this->form_validation->run() == FALSE) {
            $data['categories_checkbox']    =(new Model\Portal\Category)->generateCheckbox();
            $data['status'] = $this->status;

            $this->template->set('sidebar');
            $this->template->set_layout('privatepage');
            $this->template->build('create', $data);
        } else {
            $data   = array(
                'title'             => set_value('title'),
                'description'       => set_value('description'),
                'content'           => $this->input->post('content'),
                'contributor_id'    => auth()->getUser()->id,
            );

            $categories = set_value('categories', array());
            $status     = set_value('status', 'draft');
            $type       = set_value('type', 'private');

            $id = $this->Mod_sendarticle->send($data, $status, $type, null, $categories);

            $articleLib = new Library\Article\Article;
            $articleLib->set($id);

            if ($featured_image = set_value('featured[src]')) {
                $description = set_value('featured[description]');

                $articleLib->setFeaturedImage($featured_image, $description);
            }

            set_message_success('Artikel berhasil dibuat.');

            redirect('dashboard/editArticle/' . $id,  'refresh');
        }
    }

    public function editArticle($id)
    {
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('content', 'Content', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $artikel = Model\Portal\Article::withDrafts()->withPrivate()->findOrFail($id);

            $cat_ids = array_map(function ($cat){
                return $cat->kategori_id;
            }, $this->Mod_category->getByArticle($id));

            $data['artikel']                = $artikel;
            $data['categories_checkbox']    = (new Model\Portal\Category)->generateCheckbox(0, $cat_ids);
            $data['status']                 = $this->status;

            $this->template->set('sidebar');            
            $this->template->set_layout('privatepage');            
            $this->template->build('edit', $data);
        } else {
            $artikel    = array(
                'title'     => set_value('title'),
                'description'       => set_value('description'),
                'content'   => set_value('content', '', FALSE)
            );

            $categories = set_value('categories', array());
            $status     = set_value('status', 'draft');

            $id = $this->Mod_sendarticle->edit($id, $artikel, $categories);

            $articleLib = new Library\Article\Article;
            $articleLib->set($id);

            $featured_action = $this->input->post('featured[action]');
            $description     = $this->input->post('featured[description]');

            switch ($featured_action) {
                case 'upload':
                    $featured_image = $this->input->post('featured[src]');
                    $desciption     = $this->input->post('featured[description]');

                    $articleLib->setFeaturedImage($featured_image, $description);
                    break;

                case 'remove':
                    $articleLib->removeFeaturedImage();
                
                default:
                    $desciption     = $this->input->post('featured[description]');
                    
                    $articleLib->updateFeaturedDescription($description);
                    break;
            }

            set_message_success('Artikel berhasil diperbarui.');

            redirect('dashboard/editArticle/'.$id, 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->Mod_sendarticle->delete($id);

        if ($delete) {
            set_message_success('Artikel berhasil dihapus.');
        } else {
            set_message_error('Artikel gagal dihapus.');
        }

        redirect('dashboard', 'refresh');
    }

    public function wilayah()
    {
        echo $this->wilayah->ajax();
    }

    public function profile()
    {
        $id     = auth()->getUser()->id;
        $source = $this->wilayah->getSource();

        $user               = sentinel()->getUser();
        $user->wilayah      = $source->getParentByDesa($user->profile->desa_id);
        
        $data['user']       = $user;
        $data['profile']    = $user->profile;

        $this->template->set('sidebar');
        $this->template->set_layout('privatepage');
        $this->template->inject_partial('script', $this->wilayah->script(site_url('user/wilayah')));
        $this->template->build('profile', $data);
    }

    public function update()
    {
        $id         = auth()->getUser()->id;        
        $user       = array('email' => $this->input->post('email'));        
        $profile    = array(
            'first_name'         => set_value('first_name'),
            'last_name'          => set_value('last_name'),
            'gender'             => set_value('gender'),
            'tempat_lahir'       => set_value('tempat_lahir'),
            'tanggal_lahir'      => set_value('tanggal_lahir'),
            'address'            => set_value('address'),
            'desa_id'            => set_value('desa'),
        );

        $res = $this->model->update($id, $user, $profile);

        $action = $this->input->post('avatar[action]');
        $avatar = $this->input->post('avatar[src]');

        if ($action === 'upload') {
            $this->model->setAvatar($id, $avatar);
        } elseif ($action === 'remove') {
            $this->model->removeAvatar($id);
        }

        if ($res) {
            set_message_success('User berhasil diperbarui.');

            redirect('dashboard/profile');
        } else {
            set_message_error('User gagal diperbarui.');

            redirect('dashboard/profile');
        }
    }

    public function changepassword()
    {   
        $user_id = auth()->getUser()->id;
        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirmation', 'New Password Confirmation', 'required|min_length[6]|matches[password]');
        $this->form_validation->set_rules('password_old', 'Old Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['user'] = sentinel()->findById($user_id);

            keepValidationErrors();
            
            $this->template->set('sidebar');
            $this->template->set_layout('privatepage');  

            $this->template->build('changePassword', $data);
        } else {
            $hasher         = sentinel()->getHasher();

            $password               = set_value('password');
            $password_old           = set_value('password_old');
            $password_confirmation  = set_value('password_confirmation');

            $user = sentinel()->getUser($user_id);

            if (!$hasher->check($password_old, $user->password) || $password != $password_confirmation) {
                set_message_error('Password lama tidak sesuai.');

                redirect('dashboard/profile/'.$user_id, 'refresh');
            } else {
                sentinel()->update($user, array('password' => $password));

                set_message_success('Password berhasil diperbarui.');

                redirect('dashboard/profile/'.$user_id, 'refresh');
            }
        }
    }

    public function viewThread($id)
    {
        $user   = auth()->getUser();
        $data   = array(
            'notif_status' => '0'
        );
        $where  = array(
            'thread_id' => $id,
            'user_id'   => $user->id
        );
        $this->Mod_forum->confirm($where, $data);
        redirect('forum/thread/view/'.$id);
    }

    public function viewThreadCommentar($id)
    {
        $user   = auth()->getUser();
        $data   = array(
            'notif_status'  => '0'
        );
        $where  = array(
            'reply_to'      => $id
        );
        $this->Mod_forum->newCommentChecked($where, $data);
        redirect('forum/thread/view/'.$id);
    }

    public function _remap($method, $params = array())
    {
        switch ($method) {
            case 'my-article':
                return $this->my_article();
                break;
            
            default:
                return call_user_func_array(array($this, $method), $params);
                break;
        }
    }
}

/* End of file Privatepage.php */
/* Location: ./application/modules/privatepage/controllers/Privatepage.php */