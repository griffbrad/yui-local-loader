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

namespace YuiLocalLoader;

/**
 * The primary class used for generating combo-loader responses and sending
 * them to the browser.  This class will handle YUI combo-loader requests
 * in the standard manner, optionally using APC for server-side caching and
 * gzip for response content encoding.
 *
 * @package YuiLocalLoader
 * @author Brad Griffith <bgriffith@deltasys.com>
 */
class Combo
{
    /**
     * @var Options
     */
    private $_options;

    /**
     * Kick things off with an Options object
     *
     * @param Options $options
     */
    public function __construct(Options $options = null)
    {
        $this->_options = $options;
    }

    /**
     * Re-assign the options object at runtime.
     *
     * @param Options $options
     */
    public function setOptions(Options $options)
    {
        $this->_options = $options;

        return $this;
    }

    /**
     * Render the HTTP response to the browser.  This method can optionally
     * use APC to retrieve and store responses in a memory cache to avoid slow
     * filesystem access.  It can also optionally gzip the response's content.
     * After generating the response, the script will stop execution.
     */
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

    /**
     * This method is responsible for generating the contents of the response,
     * with all the requested modules concatenated together.
     *
     * @param Request The request object allowing access to the list of files.
     *
     * @return string
     */
    private function _buildContent(Request\Http $request)
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

    /**
     * Display the HTTP response headers and content.  This function exits
     * after it completes.
     *
     * @param string $content The actual Javascript content.
     * @param boolean $gzip Whether to send gzip encoding headers.
     */
    private function _display($content, $gzip)
    {
        $this->_displayHeaders($gzip);

        echo $content;
        exit;
    }

    /**
     * Display the HTTP response headers.
     *
     * @param boolean $gzip Whether to send gzip encoding header.
     */
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
