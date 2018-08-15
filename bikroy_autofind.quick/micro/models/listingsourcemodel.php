<?php

namespace Resgef\SyncList\Models\ListingSourceModel;

use Resgef\SyncList\Models\Model\Model;

class ListingSourceModel extends Model
{
    /** @var integer $id */
    public $id;
    /** @var string $url */
    public $url;
    /** @var string $title */
    public $title;
    /** @var integer $user_id */
    public $user_id;
}