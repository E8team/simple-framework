<?php
function app($className = '')
{
    $app = \E8\Application::getInstance();
    if(empty($className)){
        return $app;
    }else{
        return $app->make($className);
    }
}
function url($path = '', $param = [])
{
    return app(\E8\Url::class)->to($path, $param);
}
function de(...$vars){
    echo '<pre>';
    foreach ($vars as $var){
        var_dump($var);
    }
    echo '</pre>';
    die;
}
function asset($file){
    return app(\E8\Url::class)->asset($file);
}