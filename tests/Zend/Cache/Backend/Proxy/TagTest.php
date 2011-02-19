<?php

namespace Test;

use Zend\Cache\Backend as CacheBackend;

class TagTest extends \PHPUnit_Framework_TestCase
{
    protected $_instance = null;

    public function setUp()
    {
        $this->_instance = new CacheBackend\Proxy\Tag;
        $this->_instance->setBackend(new CacheBackend\TestBackend);
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
        $this->assertEquals($this->_instance->test('123'), 123456);
    }

    public function testLoad()
    {
        $this->assertEquals($this->_instance->load('123'), 'foo');
    }

    public function testSave()
    {
        $this->assertNull($this->_instance->save('data', '123'));
    }

    public function testRemove()
    {
        $this->assertTrue($this->_instance->remove('123'));
    }

    public function testSaveTags()
    {
        $this->assertNull($this->_instance->save('data', '123', array('tag1')));
        $this->assertEquals($this->_instance->getBackend()->getLastLog(), array(
            'methodName'=>'save',
            'args' => array(array('foo', '123'), '_tag:tag1', array()),
        ));
    }

    public function testCleanMatchingTag()
    {
        $this->_instance->clean(\Zend\Cache\Cache::CLEANING_MODE_MATCHING_TAG, array('tag1', 'tag2'));
        $this->assertEquals($this->_instance->getBackend()->getAllLogs(), array(
            array(
                'methodName'=>'construct',
                'args' => array(array()),
            ),
            array(
                'methodName'=>'get',
                'args' => array('_tag:tag1', false),
            ),
            array(
                'methodName'=>'get',
                'args' => array('_tag:tag2', false),
            ),
            array(
                'methodName'=>'remove',
                'args' => array('foo'),
            ),
        ));
    }

    public function testCleanMatchingAnyTag()
    {
        $this->_instance->clean(\Zend\Cache\Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('tag1', 'tag2'));
        $this->assertEquals($this->_instance->getBackend()->getAllLogs(), array(
            array(
                'methodName'=>'construct',
                'args' => array(array()),
            ),
            array(
                'methodName'=>'get',
                'args' => array('_tag:tag1', false),
            ),
            array(
                'methodName'=>'remove',
                'args' => array('foo'),
            ),
            array(
                'methodName'=>'get',
                'args' => array('_tag:tag2', false),
            ),
            array(
                'methodName'=>'remove',
                'args' => array('foo'),
            ),
        ));
    }
}

