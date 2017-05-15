<?php

namespace E8;


use Illuminate\View\Factory as ViewFactory;

class Controller
{
    /**
     * @var Application $app
     */
    protected $app;
    /**
     * @var ViewFactory $view
     */
    protected $viewFactory;
    protected $request;

    public function __construct(Application $app, ViewFactory $viewFactory, Request $request)
    {
        $this->setApp($app);
        $this->setViewFactory($viewFactory);
        $this->setRequest($request);
    }

    public function setApp($app){
        $this->app = $app;
    }

    public function setViewFactory($viewFactory){
        $this->viewFactory = $viewFactory;
    }

    public function setRequest($request){
        $this->request = $request;
    }

    public function view($viewName = '', $data = [])
    {
        if(empty($viewName)){
            $viewName = $this->request->getActionName();
        }
        return $this->viewFactory->make($viewName, $data);
    }
}