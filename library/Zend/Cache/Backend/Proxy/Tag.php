<?php
namespace Zend\Cache\Backend\Proxy;

class Tag extends AbstractBackend
{
    protected $_backend = null;
    protected $_tagKeyPrefix = '_tag:';


    public function setBackend(AbstractBackend $backend)
    {
        $this->_backend = $backend;
    }

    public function getBackend()
    {
        return $this->_backend;
    }

    public function save($data, $id, $tags = array(), $specificLifetime = false)
    {
        $result = $this->_backend->save($data, $id, array(), $specificLifetime);
        if (empty($result)) return $result;

        // add code for support saving tags in storage
        foreach ($tags as $tag) {
            $name = $this->_getTagCacheKey($tag);
            if ($this->_backend->test($name)) {
                $list = $this->_backend->load($name);
                $list[] = $id;
                $this->_backend->save($list, $name);
            } else {
                $this->_backend->save(array($id), $name);
            }
        }
    }

    public function remove($id)
    {
        return $this->_backend->remove($id);
    }

    public function test($id)
    {
        return $this->_backend->test($id);
    }

    public function load($id, $doNotTestCacheValidity = false)
    {
        return $this->_backend->load($id);
    }

    public function clean($mode = \Zend\Cache\Cache::CLEANING_MODE_ALL, $tags = array())
    {
        switch ($mode) {
            case \Zend\Cache\Cache::CLEANING_MODE_ALL:
                throw new \Exception('Must emulate');
                break;
            case \Zend\Cache\Cache::CLEANING_MODE_OLD:
                throw new \Exception('Must emulate');
                break;
            case \Zend\Cache\Cache::CLEANING_MODE_MATCHING_TAG:
                $name = $this->_getTagCacheKey(reset($tag));
                $ids = $this->_backend->load($name);
                foreach (array_slice($tags, 1) as $tag) {
                    $name = $this->_getTagCacheKey($tag);
                    $list = $this->_backend->load($name);
                    $_ids = array();
                    foreach ($list as $id) {
                        if (in_array($id, $ids)) {
                            $_ids[] = $id;
                        }
                    }
                    $ids = $_ids;
                }
                foreach ($ids as $id) {
                    $this->_backend->remove($id);
                }
                break;
            case \Zend\Cache\Cache::CLEANING_MODE_NOT_MATCHING_TAG:
                throw new \Exception('Must emulate');
                break;
            case \Zend\Cache\Cache::CLEANING_MODE_MATCHING_ANY_TAG:
                foreach ($tags as $tag) {
                    $name = $this->_getTagCacheKey($tag);
                    $list = $this->_backend->load($name);
                    foreach ($list as $id) {
                        $this->_backend->remove($id);
                    }
                }
                break;
               default:
                throw new \Exception('Invalid mode for clean() method');
                   break;
        }
    }

    protected function _getTagCacheKey($tag)
    {
        return $this->_tagKeyPrefix . $tag;
    }
}

