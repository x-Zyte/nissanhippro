<?php

namespace App\Http\Controllers\Settings;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarModelColor;
use App\Models\CarSubModel;
use App\Models\CarType;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\Color;
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

        $defaultCarBrand = '';
        $carBrand = CarBrand::where('name',"NISSAN")->first();
        if($carBrand != null){
            $defaultCarBrand =  $carBrand->id;
        }

        return view('settings.carmodel',
            ['cartypeselectlist' => implode(";",$cartypeselectlist),
                'carbrandselectlist' => implode(";",$carbrandselectlist),
                'colorselectlist' => implode(";",$colorselectlist),
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

    public function getsubmodelandcolorbyid($id,$registrationtype)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carmodel = CarModel::find($id);

        if($registrationtype == 0)
            $registercost = $carmodel->individualregistercost;
        else if($registrationtype == 1)
            $registercost = $carmodel->companyregistercost;

        $actcharged = $carmodel->cartype->actcharged;

        $carsubmodels = CarSubModel::where('carmodelid',$id)->orderBy('name', 'asc')->get(['id', 'name']);

        $colorids = CarModelColor::where('carmodelid',$id)->lists('colorid');
        $colors = Color::whereIn('id', $colorids)->orderBy('code', 'asc')->get(['id', 'code', 'name']);

        return ['carsubmodels'=>$carsubmodels,'colors'=>$colors,'actcharged'=>$actcharged,'registercost'=>$registercost];
    }

    public function getregistrationcost($id,$registrationtype)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carmodel = CarModel::find($id);

        if($registrationtype == 0)
            $registercost = $carmodel->individualregistercost;
        else if($registrationtype == 1)
            $registercost = $carmodel->companyregistercost;

        return ['registercost'=>$registercost];
    }
}