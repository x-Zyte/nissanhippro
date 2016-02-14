<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\FinaceCompany;
use App\Models\InterestRateType;
use App\Repositories\InterestRateTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class InterestRateTypeController extends Controller {

    protected $menuPermissionName = "การตั้งค่าการขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $finacecompanies = FinaceCompany::orderBy('name', 'asc')->get(['id','name']);
        $finacecompanyselectlist = array();
        array_push($finacecompanyselectlist,':เลือกไฟแนนซ์');
        foreach($finacecompanies as $item){
            array_push($finacecompanyselectlist,$item->id.':'.$item->name);
        }

        return view('settings.interestratetype',
            ['finacecompanyselectlist' => implode(";",$finacecompanyselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new InterestRateTypeRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new InterestRateTypeRepository(), $request);
    }

    public function readSelectlist($finacecompanyid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $models = InterestRateType::where('finacecompanyid',$finacecompanyid)->orderBy('name', 'asc')->get(['id', 'name']);
        return $models;
    }
}