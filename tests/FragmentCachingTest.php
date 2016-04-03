<?php

class FakeCacheAdapter implements \VTP\CacheAdapterInterface
{

    public function get($key)
    {
    }

    public function set($key, $value)
    {
    }
}

class FakeModel{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }
    public function cache_key()
    {
        return $this->key;
    }
}

class FragmentCachingTest extends PHPUnit_Framework_TestCase
{
    /**
     * $cache = new FragmentCaching($cacheAdaptater)
     * $cache->cache('test', function(){...})
     */
    public function testConstructorWithoutInterface()
    {
        $this->expectException(PHPUnit_Framework_Error::class);
        new \VTP\FragmentCaching(new stdClass());
    }

    public function testConstructorWithInterface()
    {
        new \VTP\FragmentCaching(new FakeCacheAdapter());
    }

    public function testCacheWithCache()
    {
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
            ->setMethods(['get'])
            ->getMock();
        $cacheAdapter->method('get')->willReturn('en cache');
        $cache = new \VTP\FragmentCaching($cacheAdapter);
        $this->expectOutputString('en cache');
        $cache->cache('test', function () {
            echo 'salut';
        });
    }
    public function testCacheWithoutCache()
    {
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
            ->setMethods(['get'])
            ->getMock();
        $cacheAdapter->method('get')->willReturn(false);
        $cache = new \VTP\FragmentCaching($cacheAdapter);
        $this->expectOutputString('salut');
        $cache->cache('test', function () {
            echo 'salut';
        });
    }


    public function testCacheWithoutCacheSetCache()
    {
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
            ->setMethods(['get','set'])
            ->getMock();
        $cacheAdapter->method('get')->willReturn(false);
        $cacheAdapter->expects($this->once())->method('set')->with('test', 'salut');
        $cache = new \VTP\FragmentCaching($cacheAdapter);
        $this->expectOutputString('salut');
        $cache->cache('test', function () {
            echo 'salut';
        });
    }


    public function testKeyWithArray()
    {
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
            ->setMethods(['get'])
            ->getMock();
        $cacheAdapter->expects($this->once())->method('get')->with('test-je-suis');
        $cache = new \VTP\FragmentCaching($cacheAdapter);
        $cache->cache(['test', 'je', 'suis'], function(){return false;});
    }

    public function testKeyWithString()
    {
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
            ->setMethods(['get'])
            ->getMock();
        $cacheAdapter->expects($this->once())->method('get')->with('test');
        $cache = new \VTP\FragmentCaching($cacheAdapter);
        $cache->cache('test', function(){return false;});
    }


    public function testKeyWithArrayWithBoolean()
    {
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
            ->setMethods(['get'])
            ->getMock();
        $cacheAdapter->expects($this->once())->method('get')->with('test-0-suis');
        $cache = new \VTP\FragmentCaching($cacheAdapter);
        $cache->cache(['test', false, 'suis'], function(){return false;});
    }


    public function testKeyWithArrayWithObject()
    {
        $fake = new FakeModel('model');
        $cacheAdapter = $this->getMockBuilder(FakeCacheAdapter::class)
            ->setMethods(['get'])
            ->getMock();
        $cacheAdapter->expects($this->once())->method('get')->with('test-model-suis');
        $cache = new \VTP\FragmentCaching($cacheAdapter);
        $cache->cache(['test', $fake , 'suis'], function(){return false;});
    }


    public function testCacheWithFalseCondition()
    {
        $cache = $this->getMockBuilder(\VTP\FragmentCaching::class)
            ->setConstructorArgs([new FakeCacheAdapter()])
            ->setMethods(['cache'])
            ->getMock();

        $cache->expects($this->never())->method('cache');
        $cache->cacheIf(false, 'key', function (){echo 'test';});

    }
}