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

namespace YuiLocalLoader\Cache;

/**
 * A small class to encapsulate APC function calls.  Primary useful for mocking
 * APC during unit testing.
 *
 * @package YuiLocalLoader
 * @author  Brad Griffith <bgriffith@deltasys.com>
 */
class Apc
{
    /**
     * The key to use when loading and storing cache values.
     *
     * @var string
     */
    private $_cacheId;

    /**
     * @param string $cacheId
     */
    public function __construct($cacheId)
    {
        $this->_cacheId = $cacheId;
    }

    /**
     * Retrieve response contents from APC, if available.  If a matching
     * response is not found, this method will return false.
     *
     * @return boolean | string
     */
    public function load()
    {
        $success = false;
        $content = apc_fetch($this->_cacheId, $success);

        if (false === $success) {
            return false;
        }

        return $content;
    }

    /**
     * Store response contents in APC with the specified time to live (i.e. TTL)
     * values.
     *
     * @param string $content
     * @param integer $ttl
     */
    public function store($content, $ttl)
    {
        apc_store($this->_cacheId, $content, $ttl);
    }
}
