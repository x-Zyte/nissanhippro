<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Car;
use App\Models\SystemDatas\Province;
use App\Repositories\RedLabelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RedLabelController extends Controller {

    protected $menuPermissionName = "การตั้งค่ารถ";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $provinceids = Branch::where('isheadquarter',true)->distinct()->lists('provinceid');
        $provinces = Province::whereIn('id', $provinceids)->orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        array_push($provinceselectlist,':เลือกจังหวัด');
        foreach($provinces as $item){
            array_push($provinceselectlist,$item->id.':'.$item->name);
        }

        if(Auth::user()->isadmin)
            $cars = Car::all(['id','engineno','chassisno']);
        else{
            $provinceid = Auth::user()->branch->provinceid;
            $cars = Car::where('provinceid',$provinceid)->orderBy('engineno', 'asc')->get(['id','engineno','chassisno']);
        }
        $carselectlist = array();
        array_push($carselectlist,':เลือกรถ');
        foreach($cars as $item){
            array_push($carselectlist,$item->id.':'.'เลขเครื่อง '.$item->engineno.' เลขตัวถัง '.$item->chassisno);
        }

        $defaultProvince = '';
        if(Auth::user()->isadmin == false){
            $defaultProvince = Auth::user()->branch->provinceid;
        }

        return view('settings.redlabel',
            ['provinceselectlist' => implode(";",$provinceselectlist),
                'carselectlist' => implode(";",$carselectlist),
                'defaultProvince'=>$defaultProvince]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new RedLabelRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new RedLabelRepository(), $request);
    }
}