<?php


namespace E8;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\FileViewFinder;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class Application extends Container
{
    private static $instance = null;
    public static function getInstance()
    {
        if(is_null(self::$instance)){
            self::$instance = new static();
            self::$instance->bindCoreClass();
        }
        return self::$instance;
    }

    private function __clone() {}

    private function __construct() {}

    public function bindCoreClass()
    {
        $this->instance(Application::class, $this);
        $this->instance(Config::class, new Config());
        $this->instance(Kernel::class, new Kernel($this));

        $this->singleton(Request::class, function ($app) {
            return new Request();
        });

        $this->singleton(Url::class, function ($app) {
            return new Url($app, $app->make(Request::class));
        });

        $this->singleton(ViewFactory::class, function ($app){
            // 依赖申明
            $files = new Filesystem();
            $resolver = new EngineResolver();
            $resolver->register('blade', function () use ($files){
                return new CompilerEngine(new BladeCompiler($files, app(Config::class)->get('app.blade_cache_dir')));
            });
            $resolver->register('file', function (){
                return new FileEngine();
            });
            $resolver->register('php', function (){
                return new PhpEngine();
            });

            return new \Illuminate\View\Factory($resolver, new FileViewFinder($files, app(Config::class)->get('app.view_path')), new Dispatcher());
        });

        $capsule = new Capsule;

        $capsule->addConnection(app(Config::class)->get('database'));

        // Set the event dispatcher used by Eloquent models... (optional)

        $capsule->setEventDispatcher(new Dispatcher(new \Illuminate\Container\Container()));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }

}