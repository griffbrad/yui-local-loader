<?php

namespace YuiLocalLoader\Cache;

class Apc
{
    private $_cacheId;

    public function __construct($cacheId)
    {
        $this->_cacheId = $cacheId;
    }

    public function load()
    {
        $success = false;
        $content = apc_fetch($this->_cacheId, $success);

        if (false === $success) {
            return false;
        }

        return $content;
    }

    public function store($content, $ttl)
    {
        apc_store($this->_cacheId, $content, $ttl);
    }
}
