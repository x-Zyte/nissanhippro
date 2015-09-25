<?php

namespace App\Http\Controllers\SystemDatas;

use App\Http\Controllers\Controller;
use App\Models\SystemDatas\District;

class DistrictController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read($amphurid)
    {
        $districts = District::where('amphurid',$amphurid)->orderBy('name', 'asc')->get(['id', 'name']);
        return $districts;
    }
}