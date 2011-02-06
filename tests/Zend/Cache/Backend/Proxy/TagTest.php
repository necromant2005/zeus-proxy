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
        $this->assertTrue(is_object(new CacheBackend\Proxy\Tag));
    }

    public function testSetBackend()
    {
        $backend = new CacheBackend\TestBackend;
        $this->_instance->setBackend($backend);
        $this->assertSame($backend, $this->_instance->getBackend());
    }

    public function testTest()
    {
        $this->_instance->setBackend(new CacheBackend\TestBackend);
        $this->assertEquals($this->_instance->test('123'), 123456);
    }

    public function testLoad()
    {
        $this->_instance->setBackend(new CacheBackend\TestBackend);
        $this->assertEquals($this->_instance->load('123'), 'foo');
    }

    public function testSave()
    {
        $this->_instance->setBackend(new CacheBackend\TestBackend);
        $this->assertNull($this->_instance->save('data', '123'));
    }

    public function testRemove()
    {
        $this->_instance->setBackend(new CacheBackend\TestBackend);
        $this->assertTrue($this->_instance->remove('123'));
    }
}

