<?php

require_once __DIR__ . '/bootstrap.php';

class ApcTest extends PHPUnit_Framework_TestCase
{
    protected $_apc;

    public function setUp()
    {
        $this->_apc = new YuiLocalLoader\Cache\Apc('xxx');
    }

    public function tearDown()
    {
        apc_clear_cache('user');
    }

    public function testLoadOfEmptyValue()
    {
        $this->assertFalse($this->_apc->load());
    }

    public function testStore()
    {
        $this->_apc->store('CORRECT', 3600);

        $this->assertEquals('CORRECT', apc_fetch('xxx'));
    }

    public function testLoadOfExistingValue()
    {
        $this->_apc->store('CONTENTS', 3600);

        $this->assertEquals('CONTENTS', $this->_apc->load());
    } 
}
