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
 * A container to manage the options related to the combo request.  This class
 * encapsulates the options to ensure that when the combo loader is accessing
 * them, the Options class has the opportunity to generate default objects, etc.
 * If the defaults are not desired for any reason, though, you're free to inject
 * alternatives.
 *
 * @package YuiLocalLoader
 * @author Brad Griffith <bgriffith@deltasys.com>
 */
class Options
{
    /**
     * Whether to use APC for server-side caching of responses.  If not
     * specified, APC will be used if the apc_store() function is available.
     *
     * @var boolean
     */
    protected $_apc;

    /**
     * The base filesystem path where the YUI files can be found.  The loader
     * expects the base path to be populated with folders named after the YUI
     * version (e.g. "3_4_0") the folder contains.  The version folders should
     * have the full source distribution, including most importantly the "build"
     * sub-folder.
     *
     * @var string
     */
    protected $_basePath;

    /**
     * How long the server-side cache entries should be preserved in seconds.
     *
     * @var integer
     */
    protected $_cacheTtl = 3600;

    /**
     * A request object that allows retrieving the list of requested files,
     * a key to use when loading and storing server-side cache entries,
     * and whether the client accepts gzip encoded responses.
     *
     * @var YuiLocalLoader\Request
     */
    protected $_request;

    /**
     * Whether to gzip responses.  Regardless of this setting, gzip responses
     * will not be sent if the request indicates gzip encoding is not allowed.
     * If this options is set to false, no gzip will be performed regardless
     * of request headers.
     *
     * @var boolean
     */
    protected $_gzip = true;

    /**
     * @param array $values An array of key-value pairs for initial option
     *                      values.
     */
    public function __construct($values = null)
    {
        if ($values) {
            $this->setValues($values);
        }
    }

    /**
     * Set multiple options values using an array of key-value pairs.  The
     * array's keys should be in the "optionName" style and the values will
     * be passed directly to the option's setter method.
     *
     * @param array $values
     *
     * @return Options
     */
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

    /**
     * Whether to use APC for server-side caching.
     *
     * @param boolean $apc
     *
     * @return Options
     */
    public function setApc($apc)
    {
        $this->_apc = $apc;

        return $this; 
    }

    /**
     * @return boolean
     */
    public function getApc()
    {
        if (null === $this->_apc && function_exists('apc_store')) {
            $this->_apc = true;
        }

        return $this->_apc = true;
    }

    /**
     * The base path for YUI distribution files can be found.
     *
     * @param string $basePath
     *
     * @return $options
     */
    public function setBasePath($basePath)
    {
        $this->_basePath = $basePath;

        return $this->_basePath;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->_basePath;
    }

    /**
     * How long to preserve server-side cache entries.  In seconds.
     *
     * @param integer $cacheTtl
     *
     * @return Options
     */
    public function setCacheTtl($cacheTtl)
    {
        $this->_cacheTtl = $cacheTtl;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCacheTtl()
    {
        return $this->_cacheTtl;
    }

    /**
     * Whether to gzip responses.  Will only ever gzip if the request indicates
     * the gzip encoding is accepted by the client.
     *
     * @param boolean $gzip
     *
     * @return Options
     */
    public function setGzip($gzip)
    {
        $this->_gzip = $gzip;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getGzip()
    {
        if (null === $this->_gzip) {
            $this->_gzip = $this->getRequest()->acceptsGzip();
        }

        return $this->_gzip;
    }

    /**
     * An object allowing access to request information.
     *
     * @param Request $request
     *
     * @return Options
     */
    public function setRequest(Request $request)
    {
        $this->_request = $request;

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (! $this->_request) {
            $this->_request = new Request\Http();
        }

        return $this->_request;
    }
}
