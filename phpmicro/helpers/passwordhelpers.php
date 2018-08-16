<?php

namespace Resgef\SyncList\Helpers\PasswordHelpers;

class PasswordHelpers
{
    static function make_hash($salt, $passphrase)
    {
        return sha1($salt . sha1($salt . $passphrase) . $passphrase);
    }
}