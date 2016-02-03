<?php

namespace Library\Article;

use Model;
use Carbon\Carbon;
use Hashids\Hashids;
use Intervention\Image\ImageManager;

class Article
{
    protected $model;
    protected $imageManager;
    protected $user;
    protected $categories;
    protected $tags;

    public function __construct()
    {
        $this->model            = new Model\Portal\Article;
        $this->imageManager     = new ImageManager;
        $this->user             = sentinel()->check();
        $this->categories       = [];
        $this->tags             = [];
    }

    public function submit($article, $name, $email, $desa = 0, $categories = [], $featured = null, $custom_avatar = null)
    {
        $this->set($article, $categories);

        $this->model->nama      = $name;
        $this->model->email     = $email;
        $this->model->desa_id   = $desa;

        $this->saveToDraft();

        if ($featured)
            $this->setFeaturedImage($featured);

        if ($custom_avatar)
            $this->setCustomAvatar($custom_avatar);

        return $this;
    }

    public function set($article, $categories = [], $tags = [])
    {
        if ($article instanceof Model\Portal\Article) {
            $this->model        = $article;
            $this->categories   = $article->categories->pluck('id')->toArray();
            $this->tags         = $article->categories->pluck('id')->toArray();
        } elseif (is_numeric($article)) {
            $this->model = $this->model->withDrafts()->withPrivate()->findOrFail($article);
        } else {
            $this->model->fill($article);
        }

        $this->categories   = $categories;
        $this->tags         = $tags;

        return $this;
    }

    public function setFeaturedImage($imageData, $description = '')
    {
        $filename = $this->setImage($imageData, 'featured');

        $this->model->update([
            'featured_image'        => $filename,
            'featured_description'  => $description,
        ]);

        return $this;
    }

    public function updateFeaturedDescription($description)
    {
        $this->model->update([
            'featured_description'  => $description,
        ]);

        return $this;
    }

    public function setSliderImage($imageData)
    {
        $filename = $this->setImage($imageData, 'slider');

        $this->model->update(['slider' => $filename]);

        return $this;
    }

    public function setCustomAvatar($imageData)
    {
        $filename = $this->setImage($imageData, 'custom-avatar', 'customavatar_');

        $this->model->update(['custom_avatar' => $filename]);

        return $this;
    }

    public function setImage($imageData, $subfolder = 'featured', $prefix = 'article_')
    {
        $image = $this->imageManager->make($imageData);

        if ($image->mime == 'image/jpeg')
            $extension = '.jpg';
        elseif ($image->mime == 'image/png')
            $extension = '.png';
        elseif ($image->mime == 'image/gif')
            $extension = '.gif';
        else
            $extension = '';

        $subfolder  = $subfolder ? trim($subfolder, '\\/') . '/' : '';
        $filename   = $prefix . $this->getHashids() . '_' . $this->model->slug . $extension;

        $image->save(PATH_PORTAL_CONTENT.'/'.$subfolder.$filename);

        return $filename;
    }

    public function getHashids()
    {        
        $hashids    = new Hashids(HASHIDS_SALT);
        $id         = $hashids->encode($this->model->id);

        return $id;
    }

    public function removeFeaturedImage()
    {
        $filename   = $this->model->featured_image_original;

        $this->removeImage($filename, 'featured');

        $this->model->update([
            'featured_image'        => '',
            'featured_description'  => '',
        ]);

        return $this;
    }

    public function removeSliderImage()
    {
        $filename   = $this->model->slider;

        $this->removeImage($filename, 'slider');

        $this->model->update(['slider' => '']);

        return $this;
    }

    public function removeCustomAvatar()
    {
        $filename   = $this->model->custom_avatar;

        $this->removeImage($filename, 'custom-avatar');

        $this->model->update(['custom_avatar' => '']);

        return $this;
    }

    public function removeImage($filename, $subfolder = 'featured')
    {
        $subfolder  = $subfolder ? trim($subfolder, '\\/') . '/' : '';
        $filepath   = PATH_PORTAL_CONTENT.'/'.$subfolder.$filename;

        if (is_file($filepath))
            unlink($filepath);
    }

    public function setPrivate()
    {
        $this->model->type = 'private';

        return $this;
    }

    public function saveToPublish($type = 'public')
    {
        return $this->save('publish', $type);
    }

    public function saveToDraft($type = 'public')
    {
        return $this->save('draft', $type);
    }

    public function save($status = 'publish', $type = 'public')
    {
        if ($this->user)
            $this->model->contributor()->associate($this->user);

        $this->model->status    = $status;
        $this->model->type      = $type;

        if (!$this->model->exists)
            $this->model->date      = Carbon::now();
        
        $this->model->save();

        $this->model->categories()->sync($this->categories);
        $this->model->tags()->sync($this->tags);

        return $this->model;
    }

    public function filterAllowEditor($articles)
    {
        $user       = auth()->getUser();
        $allows     = $user->editorcategory;

        return $articles->filter(function ($article) use ($allows) {
            if ($article->categories->isEmpty())
                return true;
            
            foreach ($article->categories as $category) {
                if (in_array($category->id, $allows->pluck('id')->toArray())) {
                    return true;
                }
            }

            return false;
        });
    }
}