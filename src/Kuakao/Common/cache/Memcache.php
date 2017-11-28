<?php

namespace Kuakao\Common\cache;

class Memcache
{

    private $memcache = null;

    public $host = '127.0.0.1';

    public $port = 11211;


    public function __construct() {
        $this->memcache = new Memcache;
        $this->memcache->connect($this->host, $this->port);
    }

    public function memcache() {
        $this->__construct();
    }

    public function get($name) {
        $value = $this->memcache->get($name);
        return $value;
    }

    public function set($name, $value, $ttl = 0, $ext1='', $ext2='') {
        return $this->memcache->set($name, $value, MEMCACHE_COMPRESSED, $ttl);
    }

    public function delete($name) {
		return $this->memcache->delete($name);
	}

    public function flush() {
        return $this->memcache->flush();
    }
}
