<?php

require_once __DIR__ . '/bootstrap.php';

class OptionsTest extends PHPUnit_Framework_TestCase
{
    protected $_options;

    public function setUp()
    {
        $this->_options = new YuiLocalLoader\Options();
    }

    public function tearDown()
    {
        unset($this->_options);
    }

    public function testGetDefaultResponse()
    {
        $this->assertInstanceOf(
            'YuiLocalLoader\Request\Http', 
            $this->_options->getRequest()
        );
    }
}
