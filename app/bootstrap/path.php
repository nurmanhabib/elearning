<?php

define('PATH_ROOT',                     dirname(dirname(__DIR__)));

define('PATH_AVATAR',                   PATH_ROOT.'/'.getenv('PATH_AVATAR')                 ?: 'public/user/avatar');
define('PATH_PORTAL_CONTENT',           PATH_ROOT.'/'.getenv('PATH_PORTAL_CONTENT')         ?: 'public/portal-content');
define('PATH_KELASONLINE_CONTENT',      PATH_ROOT.'/'.getenv('PATH_KELASONLINE_CONTENT')    ?: 'public/kelas-content');
define('PATH_ADS',      				PATH_ROOT.'/'.'public/ads');

define('PATH_ELIBRARY_UPLOAD',          PATH_ROOT.'/'.getenv('PATH_ELIBRARY_UPLOAD')        ?: 'app/files/elibrary');
define('PATH_FORUM_ATTACHMENT',         PATH_ROOT.'/'.getenv('PATH_FORUM_ATTACHMENT')       ?: 'app/files/forum-attachment');
define('PATH_KONSULTASI_ATTACHMENT',    PATH_ROOT.'/'.getenv('PATH_KONSULTASI_ATTACHMENT')  ?: 'app/files/konsultasi-attachment');
define('PATH_KELASONLINE_ATTACHMENT',   PATH_ROOT.'/'.getenv('PATH_KELASONLINE_ATTACHMENT') ?: 'app/files/kelas-online');

define('HASHIDS_SALT',   				getenv('HASHIDS_SALT') ?: 'yogyakarta');
