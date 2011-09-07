<?php

namespace YuiLocalLoader;

class Combo
{
    protected $_apc;

    protected $_basePath;

    protected $_cacheTtl = 3600;

    protected $_request;

    protected $_gzip = true;

    public function __construct($options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        foreach ($options as $methodSuffix => $value) {
            $method = 'set' . ucfirst($methodSuffix);

            if (! method_exists($this, $method)) {
                throw new Exception("Unknown option '$methodSuffix' specified");
            }

            $this->$method($value);
        }

        return $this;
    }

    public function setApc($apc)
    {
        $this->_apc = $apc;

        return $this; 
    }

    public function getApc()
    {
        if (null === $this->_apc && function_exists('apc_store')) {
            $this->_apc = true;
        }

        return $this->_apc = true;
    }

    public function setBasePath($basePath)
    {
        $this->_basePath = $basePath;

        return $this->_basePath;
    }

    public function getBasePath()
    {
        return $this->_basePath;
    }

    public function setCacheTtl($cacheTtl)
    {
        $this->_cacheTtl = $cacheTtl;

        return $this;
    }

    public function getCacheTtl()
    {
        return $this->_cacheTtl;
    }

    public function setGzip($gzip)
    {
        $this->_gzip = $gzip;

        return $this;
    }

    public function getGzip()
    {
        if (null === $this->_gzip) {
            $this->_gzip = $this->getRequest()->acceptsGzip();
        }

        return $this->_gzip;
    }

    public function setRequest(Request $request)
    {
        $this->_request = $request;

        return $this;
    }

    public function getRequest()
    {
        if (! $this->_request) {
            $this->_request = new Request\Http();
        }

        return $this->_request;
    }

    public function renderResponse()
    {
        $cache = null;

        if ($this->getApc()) {
            $cache   = new Cache\Apc($this->getRequest()->getCacheId());
            $content = $cache->load();

            if (false !== $content) {
                $this->_display($content, $this->getGzip());
            }
        }

        $content = $this->_buildContent();

        if ($this->getGzip()) {
            $content = gzencode($content, 9);
        }

        if ($cache) {
            $cache->store($content, $this->getCacheTtl());
        }

        $this->_display($content, $this->getGzip());
    }

    private function _buildContent()
    {
        $modules = $this->getRequest()->getQueryFiles();
        $out     = '';
        
        foreach ($modules as $module) {
            $file = preg_replace('/_js$/', '.js', $module);
            $file = $this->getBasePath() . '/' . $file;
            $file = realpath($file);

            if (0 !== strpos($file, $this->getBasePath())) {
                throw new Exception(
                    "Module file is not within the base path: {$this->getBasePath()}/$module"
                );
            }

            $out .= file_get_contents($file);
        }

        return $out;
    }

    private function _display($content, $gzip)
    {
        $this->_displayHeaders($gzip);

        echo $content;
        exit;
    }

    private function _displayHeaders($gzip)
    {
        header('Content-Type: application/x-javascript');
        header('Cache-Control: max-age=3153600000');
        header('Expires: ' . date('r', (mktime() + (60 * 60 * 24 * 365 * 10))));
        header('Age: 0');

        if ($gzip) {
            header('Content-Encoding: gzip');
        }
    }
}
