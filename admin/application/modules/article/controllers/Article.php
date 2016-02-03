<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Article extends Admin {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Mod_artikel');
        $this->load->model('tags/M_tags');
        $this->load->model('kategori/M_kategori');

        $this->status = array(
            'publish'   => 'Publish',
            'draft'     => 'Draft',
        );

        $this->repository   = new Library\Article\Article;

        $this->load->library('pagination');
    }

    public function index()
    {
        $request    = Request::createFromGlobals();
        $articles   = Model\Portal\Article::withPrivate()->latest('date');
        $status     = 'publish';

        if ($request->query->has('status')) {
            $status = $request->query->get('status');
            
            if ($status === 'draft') {
                $this->indexDraft();

                return;
            } elseif ($status === 'schedule') {
                $articles   = Model\Portal\Article::withDrafts()->withPrivate()->scheduled()->latest('date');
            } elseif ($status === 'all') {
                $articles   = Model\Portal\Article::withDrafts()->withPrivate()->latest('date');
            }
        }

        if (sentinel()->inRole(['edt'])) {
            $articles = $this->repository->filterAllowEditor($articles->get());
        } else {
            $articles = $articles->get();
        }

        $data['artikel']    = $articles;
        $data['status']     = $status;

        $this->template->build('index', $data);
    }

    protected function indexDraft()
    {
        $status     = 'draft';
        $articles   = Model\Portal\Article::withPrivate()->withDrafts()->status($status)->latest('date');

        if (sentinel()->inRole(['edt'])) {
            $articles = $this->repository->filterAllowEditor($articles->get());
        } else {
            $articles = $articles->get();
        }

        $data['artikel']    = $articles;
        $data['status']     = $status;

       $this->template->build('index_draft', $data);
    }

    public function add()
    {
        $user = auth()->getUser();

        $this->form_validation->set_rules('title', 'Title', 'trim|required', array('required' => '<div class="alert alert-danger">Judul Artikel Wajib diisi</div>'));
        $this->form_validation->set_rules('content', 'Content', 'required', array('required' => '<div class="alert alert-danger">Content Artikel Wajib diisi</div>'));

        if ($this->form_validation->run() == FALSE) {            
            $data['categories_checkbox']    = $this->M_kategori->generateCheckbox();
            $data['status']                 = $this->status;
            
            $this->template->build('create', $data);
        } else {
            $artikel    = array(
                'title'             => set_value('title'),
                'description'       => set_value('description'),
                'content'           => set_value('content', '', FALSE),
                'published'         => set_value('published', '0000-00-00 00:00:00'),
                'type'              => set_value('type', 'public'),
                'contributor_id'    => $user->id,
                'editor_id'         => $user->id,
            );

            $categories = set_value('categories', array());
            $tags       = set_value('tags', array());
            $status     = set_value('status', 'publish');

            if (set_value('private', 0)) {
                $artikel['type'] = 'private';
            } else {
                $artikel['type'] = 'public';
            }

            if (set_value('with_schedule', 0)) {
                $artikel['published'] = set_value('published');
            } else {
                $artikel['published'] = '0000-00-00 00:00:00';
            }

            $id = $this->Mod_artikel->create($artikel, $categories, $tags, $status);

            $repo_library = new Library\Article\Article;
            $repo_library->set($id);

            if ($this->input->post('featured[src]'))
                $repo_library->setFeaturedImage($this->input->post('featured[src]'), $this->input->post('featured[description]'));

            if ($this->input->post('slider[src]'))
                $repo_library->setSliderImage($this->input->post('slider[src]'));

            set_message_success('Artikel berhasil dibuat.');

            redirect('article/edit/'.$id, 'refresh');
        }
    }

    public function edit($id)
    {
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('content', 'Content', 'required');
        $this->form_validation->set_rules('categories[]', 'Category', 'required');

        if ($this->form_validation->run() == FALSE) {   
            $artikel = Model\Portal\Article::withDrafts()->withPrivate()->findOrFail($id);

            keepValidationErrors();

            $cat_ids = array_map(function ($cat) {
                return $cat->kategori_id;
            }, $this->M_kategori->getByArticle($id));

            $tag_ids = array();

            foreach ($this->M_tags->getByArticle($id) as $row) {
                $tag_ids[$row->tag] = $row->tag;
            }
            
            $data['artikel']                = $artikel;
            $data['categories_checkbox']    = $this->M_kategori->generateCheckbox(0, $cat_ids);
            $data['tags']                   = $tag_ids;
            $data['status']                 = $this->status;

            $this->template->build('edit', $data);
        } else {
            $artikel    = array(
                'title'             => set_value('title'),
                'description'       => set_value('description'),
                'content'           => set_value('content', '', FALSE),
                'status'            => set_value('status'),
            );

            $article = Model\Portal\Article::withDrafts()->withPrivate()->find($id);

            if ($article->editor_id == 0)
                $artikel['editor_id'] = auth()->getUser()->id;

            if (set_value('with_schedule', 0)) {
                $artikel['published'] = set_value('published');
            } else {
                $artikel['published'] = '0000-00-00 00:00:00';
            }

            $categories = set_value('categories', array());
            $tags       = set_value('tags', array());

            $repo_library = new Library\Article\Article;
            $repo_library->set($article);


            if ($this->input->post('featured[src]') && $this->input->post('featured[action]') == 'upload')
                $repo_library->setFeaturedImage($this->input->post('featured[src]'), $this->input->post('featured[description]'));
            elseif ($this->input->post('featured[action]') == 'remove')
                $repo_library->removeFeaturedImage();

            $description     = $this->input->post('featured[description]');

            $repo_library->updateFeaturedDescription($description);

            if ($this->input->post('slider[src]') && $this->input->post('slider[action]') == 'upload')
                $repo_library->setSliderImage($this->input->post('slider[src]'));
            elseif ($this->input->post('slider[action]') == 'remove')
                $repo_library->removeSliderImage();

            $id = $this->Mod_artikel->update($id, $artikel, $categories, $tags);

            set_message_success('Artikel berhasil diperbarui.');

            redirect('article/edit/'.$id, 'refresh');
        }
    }

    public function delete($id)
    {
        $delete = $this->Mod_artikel->delete($id);

        if ($delete) {
            set_message_success('Artikel berhasil dihapus.');
        } else {
            set_message_error('Artikel gagal dihapus.');
        }

        redirect('article', 'refresh');
    }

    public function choice($id)
    {
        try {
            $article = Model\Portal\Article::withPrivate()->findOrFail($id);
            $article->setEditorChoice(sentinel()->getUser());

            set_message_success('Artikel berhasil diperbarui sebagai Pilihan Editor.');

            redirect('article/edit/'.$article->id, 'refresh');
        } catch (ModelNotFoundException $e) {
            set_message_error('Artikel tidak dapat dijadikan sebagai Pilihan Editor.');

            redirect('article/edit/'.$article->id, 'refresh');
        }
    }

    public function unchoice($id)
    {
        try {
            $article = Model\Portal\Article::withPrivate()->findOrFail($id);
            $article->removeEditorChoice();

            set_message_success('Artikel berhasil diperbarui.');

            redirect('article/edit/'.$article->id, 'refresh');
        } catch (ModelNotFoundException $e) {
            set_message_error('Artikel tidak dapat diperbarui.');

            redirect('article/edit/'.$article->id, 'refresh');
        }
    }
}

/* End of file Artikel.php */
/* Location: ./application/modules/artikel/controllers/Artikel.php */