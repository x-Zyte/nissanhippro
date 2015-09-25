<?php

namespace App\Http\Controllers\SystemDatas;

use App\Http\Controllers\Controller;
use App\Models\SystemDatas\Zipcode;

class ZipcodeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read($districtid)
    {
        $zipcodes = Zipcode::where('districtid',$districtid)->orderBy('code', 'asc')->first(['id', 'code']);
        return $zipcodes;
    }
}