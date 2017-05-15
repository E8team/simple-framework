<?php

namespace E8;

use Illuminate\View\Factory as ViewFactory;

/**
 * 框架核心类
 * Class Kernel
 * @package E8
 */
class Kernel
{
    protected $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 处理..
     * @param Request $requestghh
     */
    public function handle(Request $request){

        try{
            //1. 创建对应的控制器
            $controllerName = $request->getControllerName();

            $controller = $this->app->make($controllerName);
            /*if($controller instanceof Controller){
                $controller->setApp($this->app);
                $controller->setView($this->app->make(ViewFactory::class));
                $controller->setRequest($request);
            }*/
            //2. 执行控制器中对应的方法并且获取响应对象
            $response = $controller->{$request->getActionName()}();

            if($response instanceof Response){
                return $response;
            }else{
                return new Response($response);
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }
}