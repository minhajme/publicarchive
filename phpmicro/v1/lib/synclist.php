<?Php

namespace Resgef\SyncList\Lib\SyncList;

use Resgef\SyncList\Lib\AuthenticatedUser\AuthenticatedUser;
use Resgef\SyncList\Lib\Livecommresponse\Livecommresponse;
use Resgef\SyncList\Lib\Mysqli\MySQLi;
use Resgef\SyncList\Lib\Registry\Registry;
use Resgef\SyncList\Lib\Url\Url;
use Resgef\SyncList\Models\ModelHelpers\ModelHelpers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncListApp
{
    public $registry;
    /** @var array $config */
    public $config;
    /** @var MySQLi $db */
    public $db;
    /** @var ModelHelpers $model */
    public $model;
    /** @var Livecommresponse $livecomm */
    public $livecomm;
    /** @var AuthenticatedUser $auth */
    public $auth;
    /** @var Url $url */
    public $url;

    function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->config = $registry->get('config');
        $this->db = $registry->get('db');
        $this->model = $registry->get('model');
        $this->livecomm = $registry->get('livecomm');
        $this->auth = $registry->get('auth');
        $this->url = $registry->get('url');
    }

    private function send_response(callable $content_generator)
    {
        /** @var Request $request */
        $request = $this->registry->get('request');
        /** @var Response $response */
        $response = $this->registry->get('response');

        $content = $content_generator($request, $response);

        if (is_array($content)) {
            $response->headers->set('Content-Type', 'application/json');
            $content = json_encode($content);
        }
        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'text/html');
        }

        $response->setStatusCode(Response::HTTP_OK);

        $response->setCharset('UTF-8');

        $response->setContent($content);

        $response->prepare($request);

        $response->send();
    }

    /**
     * @param $tpl
     * @param $context
     * @return mixed
     */
    public function render_template($tpl, $context)
    {
        return $this->registry->get('twig')->render($tpl, $context);
    }

    /**
     * http get
     * @param string $match_route
     * @param callable $callback
     * @return $this
     */
    public function serve_route($match_route, callable $callback)
    {
        $route = $this->registry->get('request')->query->get('route');

        $route = '/' . ltrim($route, '/');

        if ($route == $match_route) {
            $this->send_response($callback);
        }
        return $this;
    }

    public function serve_page_404(callable $callback)
    {
        $this->send_response($callback);
    }
}