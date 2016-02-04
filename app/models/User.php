<?php

namespace Model;

use Cartalyst\Sentinel\Roles\RoleInterface;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;

class User extends SentinelUser
{
    protected $connection = 'user';
    protected $hidden = ['password'];

    public static function boot()
    {
        parent::boot();

        User::created(function ($user) {
            if ($user->profile) {
                // 
            } else {
                $profile                = new Profile;
                $profile->first_name    = $user->roles()->first() ? $user->roles()->first()->name : '';
                $profile->tempat_lahir  = 'Yogyakarta';
                $profile->tanggal_lahir = '1945-08-17';
                $profile->desa_id       = '34.71.11.1001';
                $profile->user_id       = $user->id;
                $profile->save();
            }
        });
    }
    
    public function getFullNameAttribute()
    {
        return $this->profile->full_name;
    }

    public function getAvatarAttribute()
    {
        return $this->profile->avatar ? home_url(getenv('PATH_AVATAR').'/'.trim($this->profile->avatar, '\\/')) : asset('images/default_avatar_male.jpg');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function editorcategory()
    {
        return $this->belongsToMany(Portal\Category::class, 'kategori_moderator', 'user_id', 'category_id');
    }

    public function inRole($role = [])
    {
        if (empty($role))
            return true;
        
        $roled = $this->roles->filter(function ($instance) use ($role) {
            if (is_array($role)) {
                foreach ($role as $slug_or_name) {
                    if ($instance->getRoleId() == $slug_or_name || $instance->getRoleSlug() == $slug_or_name) {
                        return true;
                    }
                }

                return false;
            }

            if ($role instanceof RoleInterface) {
                return ($instance->getRoleId() === $role->getRoleId());
            }

            if ($instance->getRoleId() == $role || $instance->getRoleSlug() == $role) {
                return true;
            }

            return false;
        });

        return (bool) $roled->count();
    }

    public function scopeGetByWilayah($query, $wilayah)
    {
        list($provinsi, $kota, $kecamatan, $desa) = explode('.', $wilayah);

        if ($kota == '00') {
            $where = $provinsi . '.';
        } elseif ($kecamatan == '00') {
            $where = $provinsi . '.' . $kota . '.';
        } elseif ($desa == '0000') {
            $where = $provinsi . '.' . $kota . '.' . $kecamatan;
        } else {
            $where = $wilayah;
        }

        return $query->whereHas('profile', function ($query) use ($where) {
            return $query->where('desa_id', 'like', $where . '%');
        })->with('profile')->get();
    }
}