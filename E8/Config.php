<?php

namespace E8;

/**
 * 配置文件类
 * 使用方法:
 * <code>
 * app(\E8\Config::class)->get('app.xx.xx');
 * </code>
 * Class Config
 * @package E8
 */
// todo implements ArrayAccess
class Config
{
    private $config;
    private function loadConigFiles($dir, $extension = 'php')
    {
        $d=dir($dir);
        while (false !== ($entry = $d->read())) {
            $pathInfo = pathinfo($entry);
            if($extension == $pathInfo['extension']){
                $this->config[$pathInfo['filename']] = require($dir.DIRECTORY_SEPARATOR.$entry);
            }
        }
        $d->close();
    }
    public function __construct()
    {
        $this->loadConigFiles(BASE_PATH.'/configs');

    }

    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $config = $this->config;
        foreach ($keys as $k){
            if(!isset($config[$k])) return $default;
            $config = $config[$k];
        }
        return $config;
    }
}