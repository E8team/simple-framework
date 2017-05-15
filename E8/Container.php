<?php

namespace E8;

use Closure;
use ReflectionClass;
/**
 * IOC容器
 * Class Container
 * @package E8
 */
class Container
{
    protected $instances = [];
    protected $bindings = [];

    public function instance($className, $instance)
    {
        $this->instances[$className] = $instance;
    }
    public function singleton($className, $callback){
        $this->bind($className, $callback, true);
    }

    public function bind($className, $callback, $isSingleCase = false)
    {
        $this->bindings[$className] = [
            'callback' => $callback,
            'isSingleCase' => $isSingleCase
        ];
    }

    public function make($className)
    {
        if (isset($this->instances[$className])) {
            return $this->instances[$className];
        } elseif (isset($this->bindings[$className])) {
            $concrete = $this->bindings[$className]['callback'];
        } else{
            $concrete = $className;
        }

        if ($this->isNeedBuild($className, $concrete)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }

        if (isset($this->bindings[$className]) && $this->bindings[$className]['isSingleCase']) {
            $this->instance($className, $object);
        }

        return $object;
    }

    public function isNeedBuild($className, $concrete)
    {
        return $concrete === $className || $concrete instanceof Closure;
    }

    public function build($concrete)
    {

        if ($concrete instanceof Closure) {
            // 执行创建对象的回调函数
            return $concrete($this);
        }
        $reflector = new ReflectionClass($concrete);
        if(!$reflector->isInstantiable()){
            throw new \Exception($concrete.'类不能被实例化');
        }
        $constructor = $reflector->getConstructor();
        if(is_null($constructor)){
            return new $concrete;
        }
        $dependencies = $constructor->getParameters();
        $args = [];
        foreach ($dependencies as $index => $dependency){
            if($dependency->isDefaultValueAvailable())
            {
                $args[] = $dependency->getDefaultValue();
                continue;
            }
            if(!($class = $dependency->getClass())){
                throw new \Exception("不能初始化 [\${$dependency->name}] in class {$dependency->getDeclaringClass()->getName()}");
            }
            $args[] = $this->make($dependency->getClass()->name);
        }
        return $reflector->newInstanceArgs($args);
    }
}