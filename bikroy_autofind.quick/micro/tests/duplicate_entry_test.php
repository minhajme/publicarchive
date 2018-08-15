<?php

class DuplicateEntryTest extends \PHPUnit\Framework\TestCase
{
    function test()
    {
        /** @var \Resgef\SyncList\Lib\SyncList\SyncListApp $app */
        $app = require dirname(__DIR__) . '/app.php';
        /** @var \Resgef\SyncList\Models\UserModel\UserModel $user */
        $user = $app->model->get_user('tutul');
        $fresh_listings = $app->model->get_fresh_listings($user->id);

        $titles = [];
        $urls = [];
        /** @var \Resgef\SyncList\Models\ListingModel\ListingModel $listing */
        foreach ($fresh_listings as $listing) {
            $titles[] = $listing->title;
            $urls[] = $listing->url;
        }
        $titles = array_unique($titles);
        $urls = array_unique($urls);

        $this->assertNotEmpty($fresh_listings, "listing empty");

        $this->assertEquals(count($fresh_listings), count($titles), sprintf("%s duplicate titles", (count($fresh_listings) - count($titles))));
        $this->assertEquals(count($fresh_listings), count($urls), sprintf("%s duplicate urls", (count($fresh_listings) - count($urls))));
    }
}