<?php

/**
 * YUI Local Loader
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://deltasys.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to bgriffith@deltasys.com so we can send you a copy immediately.
 *
 * @package    YuiLocalLoader
 * @copyright  Copyright (c) 2011 Delta Systems Group (http://www.deltasys.com)
 * @license    http://deltasys.com/license/new-bsd     New BSD License
 */

set_include_path(__DIR__);

require 'YuiLocalLoader/Exception.php';
require 'YuiLocalLoader/Request/Http.php';
require 'YuiLocalLoader/Cache/Apc.php';
require 'YuiLocalLoader/Options.php';
require 'YuiLocalLoader/Combo.php';

$options = new YuiLocalLoader\Options(array(
    'basePath' => __DIR__ . '/yui3',
    'apc'      => false
));

$combo = new YuiLocalLoader\Combo($options);

$combo->renderResponse();
