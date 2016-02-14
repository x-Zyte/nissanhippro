<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\FinaceCompany;
use App\Repositories\CommissionPARepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommissionPAController extends Controller {

    protected $menuPermissionName = "การตั้งค่าการขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $models = FinaceCompany::orderBy('name', 'asc')->get(['id','name']);
        $finacecompanyselectlist = array();
        array_push($finacecompanyselectlist,':เลือกไฟแนนซ์');
        foreach($models as $model){
            array_push($finacecompanyselectlist,$model->id.':'.$model->name);
        }

        return view('settings.commissionpa',
            ['finacecompanyselectlist' => implode(";",$finacecompanyselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionPARepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionPARepository(), $request);
    }
}