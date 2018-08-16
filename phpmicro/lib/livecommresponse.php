<?php

namespace Resgef\SyncList\Lib\Livecommresponse;

class Livecommresponse {

    private $processor = [];

    function __construct() {
        $this->processor['HELLO'] = function() {
            return "";
        };
        $this->processor['__KEEPALIVE__'] = function() {
            return "";
        };
    }

    function on($name, callable $func) {
        $this->processor[$name] = $func;
        return $this;
    }

    /**
     * @param array $GET
     * @param array $POST
     * @return array
     */
    function build_response(Array $GET, Array $POST) {
        $call_processor = function($name, $param) {
            $callback = $this->processor[$name];
            if (!$callback) {
                trigger_error("$name callback not defined!", E_USER_ERROR);
            }
            if (\is_array($param)) {
                return \call_user_func_array($callback, $param);
            } else {
                return $callback($param);
            }
        };
        if (@$GET['name']) {
            $request = $GET;
        }
        if (@$POST['name']) {
            $request = $POST;
        }

        /** @var array $request */
        $name = $request['name'];
        $param = @$request['param'];
        $collection = @$request['collection'];
        
        if (\is_array($collection)) { //subscriptions
            $collection = array_map(function($sub) use($call_processor) {
                return [
                    'name' => $sub['name'],
                    'data' => $call_processor($sub['name'], @$sub['param'])
                ];
            }, $collection);
        } else { //single emit requests
            $collection = [];
        }
        
        return [
            'name' => $name,
            'data' => $call_processor($name, $param),
            'collection' => $collection
        ];
    }

}

trait liveComm {

    function livecommResponse() {
        return new Livecommresponse();
    }

}
