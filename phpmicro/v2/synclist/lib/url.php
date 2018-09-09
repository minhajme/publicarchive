<?php

namespace Resgef\SyncList\Lib\Url;

use Resgef\SyncList\Lib\Registry\Registry;

class Url
{
    /** @var array $config */
    private $config;

    function __construct(Registry $registry)
    {
        $this->config = $registry->get('config');
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function redirect_route($route)
    {
        $url = $this->link($route, '');
        header("Location: $url");
    }

    public function link($route, $trail='')
    {
        $uri = '';
        if ($route) {
            $uri = "?route=$route";
        }
        if ($trail) {
            $uri .= "&$trail";
        }
        if ($uri) {
            return join_path($this->config['root_url'], 'index.php') . $uri;
        } else {
            return join_path($this->config['root_url'], $uri);
        }
    }
}