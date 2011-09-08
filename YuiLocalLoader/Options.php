<?php

namespace YuiLocalLoader;

class Options
{
    protected $_apc;

    protected $_basePath;

    protected $_cacheTtl = 3600;

    protected $_request;

    protected $_gzip = true;

    public function __construct($values = null)
    {
        if ($values) {
            $this->setValues($values);
        }
    }

    public function setValues(array $values)
    {
        foreach ($values as $methodSuffix => $value) {
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

}
