<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;


use App\Models\Branch;
use App\Models\Color;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Facades\GridEncoder;
use App\Models\SystemDatas\Province;
use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CarPreemptionController extends Controller {

    protected $menuPermissionName = "ใบจอง";

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

        $carmodels = CarModel::whereHas("carbrand", function($q)
        {
            $q->where('ismain',true);

        })->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodelids = Car::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        $colorids = Car::distinct()->lists('colorid');
        $colors = Color::whereIn('id', $colorids)->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        $defaultProvince = '';
        if(!Auth::user()->isadmin){
            $defaultProvince =  Auth::user()->branch->provinceid;
        }

        return view('car',
            ['provinceselectlist' => implode(";",$provinceselectlist),
            'carmodelselectlist' => implode(";",$carmodelselectlist),
            'carsubmodelselectlist' => implode(";",$carsubmodelselectlist),
            'colorselectlist' => implode(";",$colorselectlist),
            'defaultProvince'=>$defaultProvince]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarRepository(), $request);
    }

    public function upload()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $error = false;
        $uploaddir = base_path().'/uploads/images/';
        $engineno = Input::get('engineno');

        $car = Car::where('engineno', $engineno)->first();

        if(Input::hasFile('receivecarfile') && Input::file('receivecarfile')->isValid()){
            $extension = Input::file('receivecarfile')->getClientOriginalExtension();
            $fileName = $engineno.'_received'.'.'.$extension;
            $upload_success = Input::file('receivecarfile')->move($uploaddir, $fileName);
            if($upload_success)
                $car->receivecarfilepath = '/uploads/images/'.$fileName;
            else
                $error = true;
        }
        if(Input::hasFile('deliverycarfile') && Input::file('deliverycarfile')->isValid()){
            $extension = Input::file('deliverycarfile')->getClientOriginalExtension();
            $fileName = $engineno.'_delivered'.'.'.$extension;
            $upload_success = Input::file('deliverycarfile')->move($uploaddir, $fileName);
            if($upload_success)
                $car->deliverycarfilepath = '/uploads/images/'.$fileName;
            else
                $error = true;
        }

        $car->save();

        $data = ($error) ? array('error' => 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์') : array('success' => 'อัพโหลดไฟล์สำเร็จ');
        echo json_encode($data);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carsubmodelids = Car::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        $colorids = Car::distinct()->lists('colorid');
        $colors = Color::whereIn('id', $colorids)->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        return ['carsubmodelselectlist'=>implode(";",$carsubmodelselectlist),
            'colorselectlist'=>implode(";",$colorselectlist)];
    }
}