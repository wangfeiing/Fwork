<?php

namespace App\Route;

use App\Cache\Cache;

class Route
{
    public static function parseURL()
    {
        //路由解析
        $requestURL = $_SERVER['REQUEST_URI'];
        $requestURL = trim($requestURL,'/');

        //缓存下请求路径
        cacheVar('url',$requestURL);


        //访问缓存
        if (Cache::has($requestURL)){
            echo Cache::get($requestURL);
            exit();
        }

        $requestArr = explode('/',$requestURL);
        if (count($requestArr) % 2 != 0){

            $base =  new BaseController();
            $base->display('404.html');

            exit();
        }

        //解析出控制器 与 方法

        $controller = $requestArr[2];
        $method = $requestArr[3];

        $class_ = '\\App\\Controller\\'.$controller;

        //解析出 参数
        $paramArr = array();

        if(count($requestArr) > 3 && count($requestArr) % 2 == 0){
            $index = 4;
            while ($index < count($requestArr)){

                $key =  $requestArr[$index++];
                $value = $requestArr[$index++];
                $paramArr[$key] = $value;
            }
        }

        //调用方法 传入参数 （参数可能为空)
        (new $class_)->$method($paramArr);
    }
}