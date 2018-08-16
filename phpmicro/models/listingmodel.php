<?php

namespace Resgef\SyncList\Models\ListingModel;

use Resgef\SyncList\Models\Model\Model;

class ListingModel extends Model
{
    /** @var integer $id */
    public $id;
    /** @var string $title */
    public $title;
    /** @var string $price */
    public $price;
    /** @var string $url */
    public $url;
    /** @var string $posted */
    public $posted;

    # below fields will be present only when a listing is viewed
    /** @var integer $user_id from listing_view table */
    public $user_id;
    /** @var string $username from users table */
    public $username;

    public function is_viewed()
    {
        if ($this->username) {
            return true;
        } else {
            return false;
        }
    }
}