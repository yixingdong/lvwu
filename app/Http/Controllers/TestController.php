<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Efriandika\LaravelSettings\Facades\Settings;


class TestController extends Controller
{
    public function putValue($key,$value)
    {
        Cache::add($key,$value,2);        

    }

    public function getValue($key){
        if(Cache::has($key)){
            dd(Cache::get($key));
        }
        dd($key.'~已失效');
    }
}
