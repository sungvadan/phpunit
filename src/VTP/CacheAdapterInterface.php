<?php
namespace VTP;

interface CacheAdapterInterface{
    public function get($key);
    public function set($key, $value);


} 