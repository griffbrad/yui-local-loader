YUI Local Loader
================

A simple YUI 3 combo loader written in PHP 5.3 with minimal dependencies.  The 
loader should work in a friendly manner with existing applications and
frameworks.  APC is optionally used for caching responses.

Why
---

It's sometimes necessary to load YUI3 modules locally rather than using Yahoo's 
CDN.  For example, Yahoo's CDN does not support SSL connections, so loading
YUI from the CDN on an SSL encrypted site will result in mixed content errors.

You can manually load each module that you need with individual script tags.
However, this makes dependency management very cumbersome and increases the 
number of HTTP requests need to complete page loading, which has a major 
negative impact on site performance.

YUI Local Loader implements the YUI combo loader logic so that you can continue
to have those benefits while hosting YUI on your own server.

Remaining Tasks
---------------

* Add http://deltasys.com/license/new-bsd page
* Support alternate module sources for combo-loading of application Javascript
* Some brief end-user documentation
* Unit tests
