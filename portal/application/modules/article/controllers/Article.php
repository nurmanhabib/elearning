<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Article extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('article');

        saveVisitor();
    }

    public function show($slug = null)
    {
        try {

            $article                    = Model\Portal\Article::with('contributor', 'editor')->slug($slug);
            $data['article']            = $article;
            $data['contributor']        = $article->contributor;
            $data['editor']             = $article->editor;
            $data['comments']           = $article->comments;
            $data['links']              = $this->Mod_link->read();
            
            $article->resolveVisitorUnique();

            if ($article->categories->count()) {
                $data['relevance_title']    = $article->categories->first()->name;
                $data['relevance']          = Model\Portal\Article::categoryId(
                                                $article->categories->first()->id)
                                                ->where('id', '!=', $article->id)
                                                ->take(6)
                                                ->latest('date')
                                                ->get();
            } else {
                $category_id    = config('homepage_category_1');
                $category       = Model\Portal\Category::find($category_id);

                if ($category) {
                    $data['relevance_title']    = $category->name;
                    $data['relevance']          = $category->articles()->take(9)->latest('date')->get();
                } else {
                    $data['relevance_title']    = 'No Category';
                    $data['relevance']          = collect();
                }
            }

            $this->template->set('active', $article->categories->first() ? $article->categories->first()->id : '');
            $this->template->set('single', TRUE);
            $this->template->set('sidebarCategory', TRUE);
            $this->template->set('railnews', FALSE);
            $this->template->set('sidebar', FALSE);
            $this->template->title($article->title);
            $this->template->build('show', $data);
        } catch (ModelNotFoundException $e) {
            $data['message'] = 'Artikel tidak ditemukan.';

            $this->template->set('single', TRUE);
            $this->template->set('sidebarCategory', TRUE);
            $this->template->set('railnews', FALSE);
            $this->template->set('sidebar', FALSE);
            $this->template->build('errors/404', $data);
        }
    }
}

/* End of file Article.php */
/* Location: ./application/modules/article/controllers/Article.php */