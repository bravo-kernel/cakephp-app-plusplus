<?php
namespace App\Lib;

use Cake\Cache\Cache;
use Cake\Filesystem\Folder;

/**
 * Class library
 */
class Kickstart
{
    /**
     * Returns a list of Controller names found in /src/Controller/Api.
     *
     * @return array List holding sorted Controller names
     */
    public static function getApiControllers()
    {
        $cached = Cache::read('api_controllers');
        if ($cached) {
            return $cached;
        }

        $dir = new Folder(APP . 'Controller' . DS . 'Api');
        $controllerFiles = $dir->find('.*Controller\.php');
        if (!$controllerFiles) {
            return false;
        }

        $result = [];
        foreach ($controllerFiles as $controllerFile) {
            $result[] = substr($controllerFile, 0, strlen($controllerFile) - 14);
        }

        sort ($result);
        Cache::write('api_controllers', $result);
        return $result;
    }

}
