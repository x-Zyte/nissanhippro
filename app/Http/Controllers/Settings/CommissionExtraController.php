<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Models\CommissionExtraCar;
use App\Models\FinaceCompany;
use App\Repositories\CommissionExtraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommissionExtraController extends Controller {

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

        $carmodels = CarModel::whereHas("carbrand", function($q)
        {
            $q->where('ismain',true);

        })->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodels = CarSubModel::has('commissionExtraCars')->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,'0:ทุกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return view('settings.commissionextra',
            ['finacecompanyselectlist' => implode(";",$finacecompanyselectlist),
                'carmodelselectlist' => implode(";",$carmodelselectlist),
                'carsubmodelselectlist' => implode(";",$carsubmodelselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionExtraRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionExtraRepository(), $request);
    }
}