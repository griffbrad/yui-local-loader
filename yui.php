<?php

set_include_path(__DIR__);

require 'YuiLocalLoader/Exception.php';
require 'YuiLocalLoader/Request/Http.php';
require 'YuiLocalLoader/Cache/Apc.php';
require 'YuiLocalLoader/Combo.php';

$options = array(
    'basePath' => __DIR__ . '/yui3',
    'apc'      => false
);

$combo = new YuiLocalLoader\Combo($options);

$combo->renderResponse();
