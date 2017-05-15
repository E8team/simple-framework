<?php

namespace E8;
/**
 * 请求类
 * Class Request
 * @package E8
 */
class Request
{
    /**
     * 请求头
     * @var array
     */
    protected $headers = [];
    /**
     * 请求的参数
     * @var array
     */
    protected $parameters = [];
    protected $controllerName;
    protected $actionName;


    public function __construct()
    {
        $this->headers = $_SERVER;
        // array_merge 合并数组
        $this->parameters = array_merge($_GET, $_POST);
        // 获取访问的控制器名 和 方法名
        $pathInfo = $this->header('PATH_INFO');
        $pathInfo = explode('/', trim($pathInfo, '/'), 2);
        if(empty($pathInfo[0])){
            $this->controllerName = 'Index';
        }else{
            $this->controllerName = ucfirst($pathInfo[0]);
        }
        $this->controllerName = 'App\Controllers\\'.$this->controllerName.'Controller';
        if(!isset($pathInfo[1])){
            $this->actionName = 'index';
        }else{
            $this->actionName = lcfirst($pathInfo[1]);
        }
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * 获取get或post参数
     * @param $key String 键值
     * @param $default mixed 默认值
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if(isset($this->parameters[$key])){
            return $this->parameters[$key];
        }else{
            return $default;
        }
    }

    /**
     * 获取请求头
     * @param $key String 键值
     * @param null $default 默认值
     * @return mixed|null
     */
    public function header($key, $default = null)
    {
        if(isset($this->headers[$key])){
            return $this->headers[$key];
        }else{
            return $default;
        }
    }
}