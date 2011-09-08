<?php

require_once __DIR__ . '/bootstrap.php';

require_once __DIR__ . '/ApcTest.php';
require_once __DIR__ . '/HttpTest.php';
require_once __DIR__ . '/ComboTest.php';
require_once __DIR__ . '/OptionsTest.php';

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite();

        $suite->addTestSuite('ApcTest');
        $suite->addTestSuite('HttpTest');
        //$suite->addTestSuite('ComboTest');
        $suite->addTestSuite('OptionsTest');

        return $suite;
    }
}
