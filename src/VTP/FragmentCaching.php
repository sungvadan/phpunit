<?php


namespace VTP;


class FragmentCaching
{
    private $cache;

    public function __construct(CacheAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    private function hashKeys($key)
    {
        if (is_array($key)) {
            $return = array();
            foreach ($key as $k) {
                array_push($return, $this->hashKey($k));
            }
            return implode('-', $return);
        } else {
            return $key;
        }

    }

    private function hashKey($key)
    {
        if (is_bool($key)) {
            return $key ? 1 : 0;
        } elseif (is_object($key)) {
            return $key->cache_key();
        } else {
            return $key;
        }

    }

    public function cache($key, callable $callback)
    {
        $key = $this->hashKeys($key);
        $value = $this->cache->get($key);
        if ($value) {
            echo $value;
        } else {
            ob_start();
            $callback();
            $content = ob_get_clean();
            $this->cache->set($key, $content);
            echo $content;
        }
    }

    public function cacheIf($condition, $key, callable $callback)
    {
        if($condition == false){
            $callback();
        } else {
            $this->cache($key, $callback);
        }
    }

}