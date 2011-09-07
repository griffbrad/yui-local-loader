<?php

namespace YuiLocalLoader\Request;

class Http 
{
    public function getQueryFiles()
    {
        return array_keys($_GET);
    }

    public function getCacheId()
    {
        return sha1($_SERVER['QUERY_STRING'] . ':' . (int) $this->acceptsGzip());
    }

    public function acceptsGzip()
    {
        return true;
    }
}
