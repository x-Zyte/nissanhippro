<?php

namespace App\Http\Controllers\Settings;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarModelColor;
use App\Models\CarModelRegister;
use App\Models\CarSubModel;
use App\Models\CarType;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\SystemDatas\Province;
use App\Repositories\CarModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarModelController extends Controller {

    protected $menuPermissionName = "การตั้งค่ารถ";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $cartypes = CarType::orderBy('name', 'asc')->get(['id','name']);
        $cartypeselectlist = array();
        array_push($cartypeselectlist,':เลือกประเภทรถ');
        foreach($cartypes as $ct){
            array_push($cartypeselectlist,$ct->id.':'.$ct->name);
        }

        $carbrands = CarBrand::orderBy('name', 'asc')->get(['id','name']);
        $carbrandselectlist = array();
        array_push($carbrandselectlist,':เลือกยี่ห้อรถ');
        foreach($carbrands as $item){
            array_push($carbrandselectlist,$item->id.':'.$item->name);
        }

        $colors = Color::orderBy('code', 'asc')->orderBy('name', 'asc')->get(['id','code','name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        $provinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        array_push($provinceselectlist,':เลือกจังหวัด');
        foreach($provinces as $item){
            array_push($provinceselectlist,$item->id.':'.$item->name);
        }

        $defaultCarBrand = '';
        $carBrand = CarBrand::where('name',"NISSAN")->first();
        if($carBrand != null){
            $defaultCarBrand =  $carBrand->id;
        }

        return view('settings.carmodel',
            ['cartypeselectlist' => implode(";",$cartypeselectlist),
                'carbrandselectlist' => implode(";",$carbrandselectlist),
                'colorselectlist' => implode(";",$colorselectlist),
                'provinceselectlist' => implode(";",$provinceselectlist),
                'defaultCarBrand' => $defaultCarBrand]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarModelRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarModelRepository(), $request);
    }

    public function getsubmodelandcolorbyid($id,$registrationtype,$registerprovinceid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carmodel = CarModel::find($id);
        $carmodelregister = CarModelRegister::where('carmodelid',$id)->where('provinceid',$registerprovinceid)->first();

        if($carmodelregister == null)
            $registercost = 0;
        else if($registrationtype == 0)
            $registercost = $carmodelregister->individualregistercost;
        else if($registrationtype == 1)
            $registercost = $carmodelregister->companyregistercost;
        else if($registrationtype == 2)
            $registercost = $carmodelregister->governmentregistercost;
        else
            $registercost = null;

        $actcharged = $carmodel->cartype->actcharged;

        $carsubmodels = CarSubModel::where('carmodelid',$id)->orderBy('name', 'asc')->get(['id', 'name']);

        $colorids = CarModelColor::where('carmodelid',$id)->lists('colorid');
        $colors = Color::whereIn('id', $colorids)->orderBy('code', 'asc')->get(['id', 'code', 'name']);

        $provinceids = CarModelRegister::where('carmodelid', $id)->lists('provinceid');
        $provinces = Province::whereIn('id', $provinceids)->orderBy('name', 'asc')->get(['id', 'name']);

        return ['carsubmodels'=>$carsubmodels,'colors'=>$colors,'actcharged'=>$actcharged,'registercost'=>$registercost, 'registerprovinces' => $provinces];
    }

    public function getregistrationcost($id,$registrationtype,$registerprovinceid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carmodelregister = CarModelRegister::where('carmodelid',$id)->where('provinceid',$registerprovinceid)->first();

        if($carmodelregister == null)
            $registercost = 0;
        else if($registrationtype == 0)
            $registercost = $carmodelregister->individualregistercost;
        else if($registrationtype == 1)
            $registercost = $carmodelregister->companyregistercost;
        else if($registrationtype == 2)
            $registercost = $carmodelregister->governmentregistercost;

        return ['registercost'=>$registercost];
    }
}