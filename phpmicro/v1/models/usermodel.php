<?php

namespace Resgef\SyncList\Models\UserModel;

use Resgef\SyncList\Models\Model\Model;

class UserModel extends Model
{
    /** @var integer $id the auto_increment id */
    public $id;
    /** @var string $username */
    public $username;
    /** @var string $password our algorithm specific hashed */
    public $password;
    /** @var string $salt */
    public $salt;
}