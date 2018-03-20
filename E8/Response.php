<?php

namespace E8;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\View;

/**
 * 响应类
 * Class Response
 * @package E8
 */
class Response
{
    /**
     * 响应头
     * @var array
     */
    protected $headers = [];
    /**
     * 响应正文
     * @var string
     */
    protected $content = '';
    public function __construct($content = '', $headers = [])
    {
        if(is_string($content)){
            $this->content = $content;
        }else if($content instanceof Renderable){
            $this->content = $content->render();
        }
        $this->headers = $headers;
    }

    /**
     * 设置响应头
     * @param $key
     * @param $value
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * 设置响应正文
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 响应输出
     */
    public function send()
    {
        foreach ($this->headers as $key=>$value){
            header($key.': '.$value);
        }
        echo $this->content;
    }
}