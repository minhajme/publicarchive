<?php

use Resgef\SyncList\Lib\BikroyDotCom\BikroyDotComPageParser;

date_default_timezone_set('UTC');
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 0);

session_start();

/** @var \Resgef\SyncList\Lib\SyncList\SyncListApp $app */
$app = require_once dirname(__DIR__) . '/synclist/app.php';

$twig = $app->registry->get('twig');
/** to view an item url along with saving its view history */
$twig->addFunction(new \Twig_SimpleFunction('viewable_url', function ($user_id, $listing_id, $url) use ($app) {
    return $app->url->link('goto', "listing_id={$listing_id}&user_id={$user_id}&url={$url}");
}));
$twig->addFunction(new \Twig_SimpleFunction('source_delete_url', function ($source_id) use ($app) {
    return $app->url->link('delete_source', "id={$source_id}");
}));
$twig->addFunction(new \Twig_SimpleFunction('url', function ($route = '', $trail = '') use ($app) {
    /** @var \Resgef\SyncList\Lib\Url\Url $url */
    $url = $app->registry->get('url');
    return $url->link($route, $trail);
}));
$twig->addFunction(new \Twig_SimpleFunction('config', function ($key) use ($app) {
    return $app->config[$key];
}));

$app->registry->set('twig', $twig);

$app
    ->serve_route('/', function () use ($app) {
        $data = [
            'user_id' => ''
        ];
        if (!$app->auth->is_authenticated()) {
            $app->url->redirect_route('login');
        }
        $data['user_id'] = $app->auth->user->id;
        $data['listings'] = $app->model->get_fresh_listings($app->auth->user->id);
        $content = $app->render_template('listing.twig', $data);
        return $content;
    })->serve_route('/goto', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
        $listing_id = $request->query->get('listing_id');
        $user_id = $request->query->get('user_id');
        $url = $request->query->get('url');
        $app->model->mark_viewed($listing_id, $user_id);
        $app->url->redirect($url);
    })->serve_route('/login', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
        $data = [
            'error' => ''
        ];
        if ($request->request->has('username') && $request->request->has('password')) {
            $app->auth->authenticate($request->request->get('username'), $request->request->get('password'));
            if ($app->auth->is_authenticated()) {
                $app->url->redirect_route('');
            } else {
                $data['error'] = 'login invalid';
            }
        }
        $content = $app->render_template('login.twig', $data);
        return $content;
    })->serve_route('/admin', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
        $data = [
            'msg' => '',
            'error' => ''
        ];
        if (!$app->auth->is_authenticated()) {
            $app->url->redirect_route('login');
        }
        if ($request->request->has('url')) {
            $url = $request->request->get('url');
            if (!BikroyDotComPageParser::is_valid_url($url)) {
                $data['error'] = 'invalid url';
            } else {
                $url = BikroyDotComPageParser::transform($url);
                $title = BikroyDotComPageParser::get_page_title($url);
                $user_id = $app->auth->user->id;
                $app->model->save_listing_source($url, $title, $user_id);
                //$app->url->redirect_route('');
            }
        }
        $data['sources'] = $app->model->get_all_listing_source($app->auth->user->id);
        $content = $app->render_template('admin.twig', $data);
        return $content;
    })->serve_route('/delete_source', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
        $source_id = $request->query->get('id');
        $app->model->delete_listing_source($source_id);
        $app->url->redirect_route('admin');
    })->serve_page_404(function () {
        return '404 page not found';
    });
exit(0);