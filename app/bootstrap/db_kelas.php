<?php

$capsule->addConnection(array(
   'driver'    => getenv('DRIVER_ELOQUENT') ?: 'mysql',
   'host'      => getenv('KELAS_DB_HOST') ?: 'localhost',
   'database'  => getenv('KELAS_DB_DATABASE') ?: 'portal_learning',
   'username'  => getenv('KELAS_DB_USERNAME') ?: 'root',
   'password'  => getenv('KELAS_DB_PASSWORD') ?: '',
   'charset'   => 'utf8',
   'collation' => 'utf8_unicode_ci',
   'prefix'    => getenv('KELAS_DB_PREFIX') ?: '',
), 'kelas');