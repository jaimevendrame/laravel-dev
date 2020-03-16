<?php

namespace lotecweb\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use lotecweb\Usuario;

use Illuminate\Routing\Controller as BaseController;


class TestController extends BaseController
{
    /**
     * All of the current user's projects.
     */
    protected $projects;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware(function ($request, $next) {
//            $this->projects = Auth::user()->idusu;
//
//            return $next($request);
//        });
    }

    public function test(){


//        $codigo = $this->projects;

        $codigo = Auth::user()->idusu;
        dd($codigo);
    }
}