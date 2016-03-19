<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    public function getCache($key,$value)
    {
        Cache::put($key,$value,2);

        $value2 =  Cache::get($key);
        dd($key.'~'.$value2);

    }
}
