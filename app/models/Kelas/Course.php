<?php

namespace Model\Kelas;

use Model\User;
use Model\Scopes\Published;
use Model\SearchableTrait;

class Course extends Model
{
    use Published;
    use SearchableTrait;

    protected $guarded = [];
    protected $content_path = PATH_KELASONLINE_CONTENT;
    protected $searchable = [
        'columns' => [
            'name'          => 15,
            'description'   => 5,
        ],
        'joins' => [
        ],
    ];

    public static function boot()
    {
        parent::boot();

        Course::creating(function ($course) {
            $course->code = $course->generateCode();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order', 'asc');
    }

    public function attachments()
    {
        return $this->hasManyThrough(Attachment::class, Chapter::class);
    }

    public function exam()
    {
        return $this->hasOne(Exam::class);
    }

    public function members()
    {
        $database = $this->getConnection()->getDatabaseName();

        return $this->belongsToMany(User::class, $database . '.course_member', 'course_id', 'user_id')->withPivot('status', 'joined_at');
    }

    public function updateStatus(User $user, $status)
    {
        $this->members()->updateExistingPivot($user->id, compact('status'));
    }

    public function memberFinished()
    {
        return $this->members()->wherePivot('status', 'finished');
    }

    public function memberActive()
    {
        return $this->members()->wherePivot('status', 'active');
    }

    public function memberPending()
    {
        return $this->members()->wherePivot('status', 'pending');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requirements()
    {
        return $this->belongsToMany(Course::class, 'course_requirements', 'course_id', 'requirement_course_id');
    }

    public function required()
    {
        return $this->belongsToMany(Course::class, 'course_requirements', 'requirement_course_id', 'course_id');
    }

    public function getExcerpt($max = 100, $trailing = '...')
    {
        $content = strip_tags($this->description);

        return truncate($content, $max, $trailing);
    }

    public function scopeWhereMember($query, User $member)
    {
        return $query->whereHas('members', function ($query) use ($member) {
            return $query->where('id', $member->id);
        });
    }

    public function scopeWhereMemberFinished($query, User $member)
    {
        return $query->whereHas('memberFinished', function ($query) use ($member) {
            return $query->where('id', $member->id);
        });
    }

    public function scopePublished($query)
    {
        $query = $query->where($this->getTable() . '.status', 'publish');

        return $query;
    }

    public function scopeUserId($query, $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeOnlyInstructor($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopePopular($query)
    {
        return $query;
    }

    protected function generateCode($i = 1)
    {
        if ($i > 999) {
            throw new \Exception("Maximum generate code");

            return;
        }
            
        $prefix         = 'COURSE';

        $user_id        = $this->instructor->id;
        $category_id    = $this->category_id;

        $suffix         = str_pad($i, 3, '0', STR_PAD_LEFT);
        $generated      = $prefix.'.'.$user_id.'.'.$category_id.'.'.$suffix;

        if ($this->withDrafts()->whereCode($generated)->count())
            return $this->generateCode($i+1);
            return $generated;
    }

    public function hasFeatured()
    {
        return (bool) $this->attributes['featured'];
    }

    public function getFeaturedImageAttribute()
    {
        return $this->hasFeatured() ? asset('kelas-content/'.$this->id.'/'.$this->featured) : asset('images/kelas_online/thumbnails-lg.jpg');
    }

    public function deleteFeatured()
    {
        if ($this->hasFeatured()) {
            $path = PATH_KELASONLINE_CONTENT . '/' . $this->id . '/' . $this->featured;

            if (file_exists($path))
                unlink($path);
        }

        $this->update(['featured' => '']);
    }

    public function hasThumbnail()
    {
        return (bool) $this->attributes['thumbnail'];
    }

    public function getThumbnailImageAttribute()
    {
        return $this->hasThumbnail() ? asset('kelas-content/'.$this->id.'/'.$this->thumbnail) : asset('images/kelas_online/thumbnails-md.jpg');
    }

    public function deleteThumbnail()
    {
        if ($this->hasThumbnail()) {
            $path = PATH_KELASONLINE_CONTENT . '/' . $this->id . '/' . $this->thumbnail;

            if (file_exists($path))
                unlink($path);
        }

        $this->update(['thumbnail' => '']);
    }

    public function getStatusLabelAttribute()
    {
        $status = ucwords($this->status);

        if ($this->status == 'draft')
            $type = 'info';
        else
            $type = 'success';

        $html = '<div class="label label-'.$type.'">'.$status.'</div>';

        return $html;
    }

    public function getLinkAttribute()
    {
        return kelas_url('course/show/' . $this->slug);
    }

    public function getLinkJoinAttribute()
    {
        return kelas_url('course/join/' . $this->slug);
    }

    public function getContentPathAttribute()
    {
        return $this->content_path . '/' . $this->id;
    }

    public function getSlugAttribute()
    {
        return str_replace('.', '-', $this->code);
    }

    public function scopeFindBySlug($query, $slug)
    {
        $code = str_replace('-', '.', $slug);

        return $query->where('code', $code)->firstOrFail();
    }

    public function hasExam()
    {
        return !empty($this->exam->questions);
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class);
    }
}