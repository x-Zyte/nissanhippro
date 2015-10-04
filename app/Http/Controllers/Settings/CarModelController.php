<?php

namespace App\Http\Controllers\Settings;

use App\Models\CarBrand;
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

        $cartypes = CarType::all(['id','name']);
        $cartypeselectlist = array();
        array_push($cartypeselectlist,':เลือกประเภทรถ');
        foreach($cartypes as $ct){
            array_push($cartypeselectlist,$ct->id.':'.$ct->name);
        }

        $carbrands = CarBrand::all(['id','name']);
        $carbrandselectlist = array();
        array_push($carbrandselectlist,':เลือกยี่ห้อรถ');
        foreach($carbrands as $item){
            array_push($carbrandselectlist,$item->id.':'.$item->name);
        }

        $colors = Color::all(['id','code','name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        return view('settings.carmodel',
            ['cartypeselectlist' => implode(";",$cartypeselectlist),
                'carbrandselectlist' => implode(";",$carbrandselectlist),
                'colorselectlist' => implode(";",$colorselectlist)]);
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
}