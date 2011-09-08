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

namespace YuiLocalLoader\Request;

/**
 * A class to encapsulte access to HTTP request information.  Primarily useful
 * to allow mock object API usage during testing but also isolates knowledge
 * of HTTP headers, etc., in one spot.
 *
 * @package YuiLocalLoader
 * @author  Brad Griffith <bgriffith@deltasys.com>
 */
class Http 
{
    /**
     * Whether gzip responses are allowed.  This option is arbitrarily set
     * by the user of the library rather than being derived from the request
     * headers.
     *
     * @var boolean
     */
    private $_gzip;

    /**
     * @param boolean $gzip
     */
    public function __construct($gzip)
    {
        $this->_gzip = (boolean) $gzip;
    }

    /**
     * Adjust gzip encoding setting.
     *
     * @param boolean $gzip
     *
     * @return Http
     */
    public function setGzip($gzip)
    {
        $this->_gzip = $gzip;

        return $this;
    }

    /**
     * Get the list of files to load for the request.  YUI requests files as
     * query string variables with no value (e.g. 
     * loader?yui/yui-min.js&node/node-min.js).
     *
     * @return array
     */
    public function getQueryFiles()
    {
        return array_keys($_GET);
    }

    /**
     * Generate an ID the server-side cache can use to identify this response.
     * We use a sha1() hash of the query string and whether the user supports
     * gzip encoding.  This way we can cache both the gzipped and uncompressed
     * responses.  Many people assume gzip encoding is universal, but some
     * anti-virus and firewall applications strip the support for HTTP requests.
     *
     * @return string
     */
    public function getCacheId()
    {
        return sha1($_SERVER['QUERY_STRING'] . ':' . (int) $this->acceptsGzip());
    }

    /**
     * Determine whether the client supports gzip encoded responses.  First,
     * we see if the user of the library wants to allow gzip at all (defaults to
     * true) and then we check to see if "gzip" is present in the
     * ACCEPT_ENCODING HTTP request header.
     *
     * @return boolean
     */
    public function acceptsGzip()
    {
        return $this->_gzip
            && isset($_SERVER['ACCEPT_ENCODING']) 
            && false !== strpos($_SERVER['ACCEPT_ENCODING'], 'gzip');
    }
}
