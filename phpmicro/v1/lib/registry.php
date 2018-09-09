<?php

namespace Resgef\SyncList\Lib\Registry;
class Registry
{
    private $storage = [];

    function get($name)
    {
        if (!array_key_exists($name, $this->storage)) {
            debug_print_backtrace();
            trigger_error("dependency $name not loaded", E_USER_ERROR);
        }
        $dep = $this->storage[$name];
        if (is_callable($dep)) {
            return $dep();
        } else {
            return $dep;
        }
    }

    function set($key, $val)
    {
        $this->storage[$key] = $val;
    }
}