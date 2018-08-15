<?php

require __DIR__ . '/vendor/autoload.php';

require __DIR__.'/helpers/pathhelpers.php';

/**
 * our rule for autoload: namespace indicates path and should be up to the file name, eg. Path\To\File(.php)\Class
 * @param $class
 * @return bool
 */
function autoload_synclist($class)
{
    $ns_lookup = [
        'Resgef\SyncList' => __DIR__
    ];
    // now remove the trailing class portion
    $class_ns = substr_replace($class, '', strrpos($class, '\\'));
    foreach ($ns_lookup as $ns_prefix => $ns_path) {
        if (strpos($class, $ns_prefix) !== false) {
            $pseudo_path = str_replace($ns_prefix, '', $class_ns);
            $path = $ns_path.strtolower(str_replace('\\', '/', $pseudo_path)).'.php';
            if (file_exists($path)) {
                require_once $path;
            } else {
                trigger_error("cannot load $class", E_USER_ERROR);
            }
        }
    }
    return true;
}

spl_autoload_register('autoload_synclist', true);