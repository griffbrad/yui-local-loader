<?php

set_include_path(dirname(__DIR__) . PATH_SEPARATOR . get_include_path());

require_once 'PHPUnit/Autoload.php';

require 'YuiLocalLoader/Exception.php';
require 'YuiLocalLoader/Request/Http.php';
require 'YuiLocalLoader/Cache/Apc.php';
require 'YuiLocalLoader/Options.php';
require 'YuiLocalLoader/Combo.php';
