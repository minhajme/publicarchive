<?php

namespace Resgef\SyncList\Lib\AuthenticatedUser;

use Resgef\SyncList\Helpers\PasswordHelpers\PasswordHelpers;
use Resgef\SyncList\Models\ModelHelpers\ModelHelpers;
use Resgef\SyncList\Models\UserModel\UserModel;

class AuthenticatedUser
{
    /** @var ModelHelpers $model */
    private $model;

    /** @var UserModel $user */
    public $user;

    function __construct(ModelHelpers $model)
    {
        $this->model = $model;
        $this->load();
    }

    /**
     *
     * @return null
     */
    function load()
    {
        $username = @$_SESSION['auth']['username'];
        if (!$username) {
            return null;
        }
        $User = $this->model->get_user($username);
        $this->user = $User;
        return true;
    }

    public function authenticate($username, $password)
    {
        $User = $this->model->get_user($username);
        $hash = PasswordHelpers::make_hash($User->salt, $password);
        if ($hash == $User->password) {
            $_SESSION['auth'] = [];
            $_SESSION['auth']['username'] = $User->username;
            $this->user = $User;
        } else {
            $this->user = null;
        }
    }

    public function is_authenticated()
    {
        if (is_object($this->user) && $this->user->username) {
            return true;
        } else {
            return false;
        }
    }
}