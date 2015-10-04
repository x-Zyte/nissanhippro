<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Models\Pricelist;
use App\Repositories\PricelistRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PricelistController extends Controller {

    protected $menuPermissionName = "การตั้งค่ารถ";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carmodels = CarModel::whereHas("carbrand", function($q)
        {
            $q->where('ismain',true);

        })->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodelids = Pricelist::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return view('settings.pricelist',
            ['carmodelselectlist' => implode(";",$carmodelselectlist),
            'carsubmodelselectlist' => implode(";",$carsubmodelselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new PricelistRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new PricelistRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carsubmodelids = Pricelist::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return ['carsubmodelselectlist'=>implode(";",$carsubmodelselectlist)];
    }
}