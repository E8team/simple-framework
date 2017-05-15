<?php

namespace E8;


class Url
{
    protected $app;
    protected $request;
    protected $urlRootPath;
    protected $publicPath;

    public function __construct($app, $request)
    {
        $this->app = $app;
        $this->request = $request;

        // 绝对路径 '/aa' => 'localhost/aa'
        // 相对路径 'aa' => 'localhost/E8/aa'
        $this->urlRootPath = pathinfo($this->request->header('SCRIPT_NAME'), PATHINFO_DIRNAME);
        if($this->urlRootPath == '\\' || $this->urlRootPath == '/'){
            $this->urlRootPath = '';
        }
        $this->publicPath = $this->urlRootPath.'/'.app(Config::class)->get('app.public_folder');
    }

    /**
     * 生成url
     * <code>
     * to('Login/index')  ===>  /E8/index.php/Login/index
     * to('Login/index', ['id'=>1, 'name'=>'ty'])
     *          ===>  /E8/index.php/Login/index?id=1&name=ty
     * </code>
     * @param string $path
     * @param array $param
     * @return string
     */
    public function to($path = '', $param = [])
    {
        $pathInfo  = explode('/', $path, 2);
        if(empty($pathInfo[0])){
            $pathInfo[0] = 'Index';
        }else{
            $pathInfo[0] = ucfirst($pathInfo[0]);
        }
        if(empty($pathInfo[1])){
            $pathInfo[1] = 'index';
        }else{
            $pathInfo[1] = lcfirst($pathInfo[1]);
        }
        $paramStr = '';
        if(!empty($param)){
            $paramStr = '?'.http_build_query($param);
        }
        return "{$this->urlRootPath}/index.php/{$pathInfo[0]}/{$pathInfo[1]}$paramStr";
    }

    public function asset($file)
    {
        if($file{0}!='/'){
            $file = '/'.$file;
        }
        return $this->publicPath.$file;
    }
}