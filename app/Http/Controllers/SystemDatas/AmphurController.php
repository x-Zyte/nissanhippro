<?php

namespace App\Http\Controllers\SystemDatas;

use App\Http\Controllers\Controller;
use App\Models\SystemDatas\Amphur;

class AmphurController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read($provinceid)
    {
        $amphurs = Amphur::where('provinceid',$provinceid)->orderBy('name', 'asc')->get(['id', 'name']);
        return $amphurs;
    }
}