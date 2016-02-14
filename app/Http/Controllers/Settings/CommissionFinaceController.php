<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Models\CommissionFinace;
use App\Models\CommissionFinaceCar;
use App\Models\FinaceCompany;
use App\Models\InterestRateType;
use App\Repositories\CommissionFinaceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommissionFinaceController extends Controller {

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

        $interestratetypeids = CommissionFinace::distinct()->lists('interestratetypeid');
        $models = InterestRateType::whereIn('id', $interestratetypeids)->orderBy('name', 'asc')->get(['id', 'name']);
        $interestratetypeselectlist = array();
        array_push($interestratetypeselectlist,':เลือกประเภทอัตราดอกเบี้ย');
        foreach($models as $model){
            array_push($interestratetypeselectlist,$model->id.':'.$model->name);
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

        $carsubmodelids = CommissionFinaceCar::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,'0:ทุกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return view('settings.commissionfinace',
            ['finacecompanyselectlist' => implode(";",$finacecompanyselectlist),
                'interestratetypeselectlist' => implode(";",$interestratetypeselectlist),
                'carmodelselectlist' => implode(";",$carmodelselectlist),
                'carsubmodelselectlist' => implode(";",$carsubmodelselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionFinaceRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionFinaceRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $interestratetypeids = CommissionFinace::distinct()->lists('interestratetypeid');
        $models = InterestRateType::whereIn('id', $interestratetypeids)->orderBy('name', 'asc')->get(['id', 'name']);
        $interestratetypeselectlist = array();
        array_push($interestratetypeselectlist,':เลือกประเภทอัตราดอกเบี้ย');
        foreach($models as $item){
            array_push($interestratetypeselectlist,$item->id.':'.$item->name);
        }

        return ['interestratetypeselectlist'=>implode(";",$interestratetypeselectlist)];
    }
}