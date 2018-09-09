<?php

namespace Resgef\SyncList\Lib\BikroyDotCom;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class BikroyDotComPageParser
{
    private $base_url = 'http://bikroy.com';

    function __construct()
    {
    }

    /**
     * check if the url is valid
     * @param string $url the source url absolute
     * @return bool
     */
    static function is_valid_url($url)
    {
        $domain_error = function ($url) {
            if (strpos($url, 'bikroy.com') === false) {
                return true;
            } else {
                return false;
            }
        };
        if ($domain_error($url)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * do some required changes to url like changing language from bn to en
     * @param string $url the url absolute
     * @return string the transformed url
     */
    static function transform($url)
    {
        $process_input_url = function ($listing_url) {
            // change language from bn to en
            $listing_url = preg_replace('#/bn/#', '/en/', $listing_url);
            return $listing_url;
        };

        return $process_input_url($url);
    }

    static function get_page_title($url)
    {
        $client = new Client();
        $response = $client->request('GET', $url);
        $html = (string)$response->getBody();
        $crawler = new Crawler($html);
        return \utf8_decode($crawler->filter('title')->text());
    }

    /**
     * TODO: complete this
     * @param $listing_url
     */
    function crawl($listing_url)
    {

    }
}