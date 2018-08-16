<?php

namespace Resgef\SyncList\Commands\BikroyGetSellingList;

use GuzzleHttp\Client;
use Resgef\SyncList\Lib\SyncList\SyncListApp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class BikroyGetSellingListCommand extends Command
{
    /** @var SyncListApp $synclistapp */
    private $synclistapp;

    function __construct(SyncListApp $app)
    {
        parent::__construct(null);
        $this->synclistapp = $app;
    }

    protected function configure()
    {
        $this->setName("bikroy:autofind")
            ->setDescription("Find office space ads near Elephant road from ad websites like bikroy.com");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var string $base_url this is needed because bikroy.com gives relative url for item details */
        $base_url = 'http://bikroy.com';

        $listing_source = $this->synclistapp->model->get_source();

        /* the starting point */
        $listing_url = $listing_source->url;

        $output->writeln("source url: $listing_url");

        /**
         * get full url from a uri relative to base url
         * @param string $uri
         * @return string the absolute url
         */
        $full_url = function ($uri) use ($base_url) {
            return rtrim($base_url, '/') . '/' . ltrim($uri, '/');
        };

        /**
         * get page html from a url
         * @param string $url
         * @return string the html of the page
         * @throws \Exception when empty page or http status code other than 200
         */
        $get_page_html = function ($url) {
            $client = new Client();
            $response = $client->request('GET', $url);
            $html = $response->getBody()->__toString();

            if (!$html) {
                throw new \Exception('no html received for the user given url');
            }

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('the user given url invalid');
            }

            return $html;
        };

        /**
         * make language bn to en(if needed)
         * @param string $listing_url
         * @return string
         */
        $process_input_url = function ($listing_url) {
            // remove base url
            //$listing_url = preg_replace('#[a-z0-9:/.]*bikroy.com/#', '/', $listing_url);
            // change language from bn to en
            $listing_url = preg_replace('#/bn/#', '/en/', $listing_url);
            return $listing_url;
        };

        /**
         * make list of items from a listing url
         * @param string $url the url of a listing page
         * @return array
         */
        $build_list = function ($url) use (&$get_page_html, $full_url, $output) {
            $crawler = new Crawler($get_page_html($url));
            /** @var Crawler $entries */
            $entries = $crawler->filter('.ui-item');
            $output->writeln($entries->count() . " entries in page");
            $results = [];
            /** @var \DOMElement $entry */
            foreach ($entries as $entry) {
                $div = new Crawler($entry);

                $title = $div->filter('.item-content a.item-title')->text();
                $title = utf8_decode($title);
                //$thumb_url = $div->filter('.item-thumb img')->attr('srcset');
                if ($div->filter('p.item-location span.is-member')->count()) {
                    $dt = $div->filter('p.item-location span')->eq(1)->text(); //format is like 2 Apr 6:35 pm
                } else {
                    $dt = $div->filter('p.item-location span')->first()->text(); //format is like 2 Apr 6:35 pm
                }
                $price = $div->filter('.item-content p.item-info')->text();
                $item_uri = $div->filter('.item-content .item-title')->attr('href'); //relative url, starts after bikroy.com
                /*
                $item_page = new Crawler($get_page_html($full_url($item_uri)));

                $by_whom = $item_page->filter('div.item-top span.poster')->text();
                $desc = $item_page->filter('div.item-description')->text();
                $address = $item_page->filter('div.item-properties dl')->eq(1)->text();
                $volume = $item_page->filter('div.item-properties dl')->eq(2)->text();
                $phones = $item_page->filter('div.item-contact-more li')->each(function (Crawler $li) {
                    return $li->text();
                });*/
                $results[] = [
                    'title' => $title,
                    'url' => $full_url($item_uri),
                    'price' => $price,
                    'datetime' => $dt
                    /*
                    'provider' => $by_whom,
                    'description' => $desc,
                    'address' => $address,
                    'volume' => $volume,
                    'phones' => $phones
                    */
                ];
            }
            return $results;
        };

        $listing_url = $process_input_url($listing_url);
        $crawler = new Crawler($get_page_html($listing_url));

        $pagination_text = $crawler->filter('span.summary-count')->text();

        if (!preg_match('#Showing (\d+)-(\d+) of ([0-9,]*) ads#i', $pagination_text, $match)) {
            $output->writeln("<error>cannot parse pagination info</error>");
            exit;
        }

        /** @var integer $total_page calculate total number of page from the pagination text */
        $total_page = call_user_func(function () use (&$match) {
            $start = $match[1];
            $end = $match[2];
            $total = $match[3];
            $total = str_replace(',', '', $total);
            $total_page = ceil($total / ($end - $start + 1));
            return $total_page;
        });

        $output->writeln($pagination_text . ", total $total_page page");

        /* build a list of pagination pages, the pages we will fetch */
        $pages_urls = call_user_func(function () use ($total_page, $listing_url) {
            $urls = [];
            if (preg_match('#page=(\d+)#i', $listing_url, $match)) { //if the page has pagination number, keep the page at first
                $urls[] = $listing_url;
                $listing_url = preg_replace('#&page=\d+#', '', $listing_url); // without the &page=... portion
                $current_pagenum = $match[1];
                $pagenums = call_user_func(function () use ($total_page, $current_pagenum) {
                    $nums = range(1, $total_page);
                    array_splice($nums, array_search($current_pagenum, $nums), 1);
                    return $nums;
                });
            } else {
                $pagenums = range(1, $total_page);
            }
            foreach ($pagenums as $num) {
                if (strpos($listing_url, '?') !== false) { //has other params too
                    $urls[] = "$listing_url&page=$num";
                } else { //page is he first param
                    $urls[] = "$listing_url?page=$num";
                }
            }
            return $urls;
        });

        $total_count = count($pages_urls);
        foreach ($pages_urls as $i => $url) {
            $output->writeln(($i + 1) . "/{$total_count}#" . "fetching $url");
            $results = $build_list($url);
            $output->writeln("saving");
            foreach ($results as $result) { //saving now to prevent memory leak
                //$output->writeln("{$result['title']}");
                $this->synclistapp->model->save_listing($result['title'], $listing_source->id, $result['url'], $result['price'], $result['datetime']);
            }
        }
        return 0;
    }
}