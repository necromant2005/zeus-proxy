<?php

namespace Test;

use Zend\Cache\Backend as CacheBackend;

class TagTest extends \PHPUnit_Framework_TestCase
{
    protected $_instance = null;

    public function setUp()
    {
        $this->_instance = new CacheBackend\Proxy\Tag;
    }

    public function testInit()
    {
        $backend = new CacheBackend\Proxy\Tag;
    }

    public function testTest()
    {
        $mock = $this->getMockForAbstractClass('Zend\\Cache\\Backend\\Proxy\\AbstractBackend');

    }

    public function testLoad()
    {

    }

    public function testSave()
    {

    }

    public function testRemove()
    {

    }

}

