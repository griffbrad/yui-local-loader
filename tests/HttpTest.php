<?php

require_once __DIR__ . '/bootstrap.php';

class HttpTest extends PHPUnit_Framework_TestCase
{
    public function testGlobalGzipDisabled()
    {
        $_SERVER['ACCEPT_ENCODING'] = 'gzip';

        $request = new YuiLocalLoader\Request\Http(false);

        $this->assertFalse($request->acceptsGzip());
    }

    public function testGzipHeaderDetection()
    {
        $_SERVER['ACCEPT_ENCODING'] = 'gzip';

        $request = new YuiLocalLoader\Request\Http(true);

        $this->assertTrue($request->acceptsGzip());
    }

    public function testGetCacheIdGzipSuffix()
    {
        $_SERVER['ACCEPT_ENCODING'] = 'gzip';
        $_SERVER['QUERY_STRING']    = 'ARBITRARY VALUE';

        $request = new YuiLocalLoader\Request\Http(true);

        $gzipCacheId = $request->getCacheId();

        $request->setGzip(false);

        $plainCacheId = $request->getCacheId();

        $this->assertNotEquals($gzipCacheId, $plainCacheId);
    }
}
