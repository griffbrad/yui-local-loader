<?php

namespace YuiLocalLoader;

class Combo
{
    private $_options;

    public function __construct(Options $options = null)
    {
        $this->_options = $options;
    }

    public function setOptions(Options $options)
    {
        $this->_options = $options;

        return $this;
    }

    public function renderResponse()
    {
        $cache   = null;
        $request = $this->_options->getRequest();

        if ($this->_options->getApc()) {
            $cache   = new Cache\Apc($request->getCacheId());
            $content = $cache->load();

            if (false !== $content) {
                $this->_display($content, $this->_options->getGzip());
            }
        }

        $content = $this->_buildContent($request);

        if ($this->_options->getGzip()) {
            $content = gzencode($content, 9);
        }

        if ($cache) {
            $cache->store($content, $this->_options->getCacheTtl());
        }

        $this->_display($content, $this->_options->getGzip());
    }

    private function _buildContent(Request $request)
    {
        $modules  = $request->getQueryFiles();
        $basePath = $this->_options->getBasePath();
        $out      = '';
        
        foreach ($modules as $module) {
            $file = preg_replace('/_js$/', '.js', $module);
            $file = $basePath . '/' . $file;
            $file = realpath($file);

            if (0 !== strpos($file, $basePath)) {
                throw new Exception(
                    "Module file is not within the base path: "
                  . "{$basePath}/{$module}"
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
