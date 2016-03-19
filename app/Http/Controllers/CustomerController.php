<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;


use App\Models\Branch;
use App\Models\CarModel;
use App\Facades\GridEncoder;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\SystemDatas\Amphur;
use App\Models\SystemDatas\District;
use App\Models\SystemDatas\Occupation;
use App\Models\SystemDatas\Province;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;


class CustomerController extends Controller {

    protected $viewname = 'customer';
    protected $menuPermissionName = "ลูกค้า";

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

        $occupations = Occupation::orderBy('name', 'asc')->get(['id', 'name']);
        $occupationselectlist = array();
        array_push($occupationselectlist,':เลือกอาชีพ');
        foreach($occupations as $item){
            array_push($occupationselectlist,$item->id.':'.$item->name);
        }

        $addprovinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
        $addprovinceselectlist = array();
        array_push($addprovinceselectlist,':เลือกจังหวัด');
        foreach($addprovinces as $item){
            array_push($addprovinceselectlist,$item->id.':'.$item->name);
        }

        $amphurids = Customer::distinct()->lists('amphurid');
        $amphurs = Amphur::whereIn('id', $amphurids)->orderBy('name', 'asc')->get(['id', 'name']);
        $amphurselectlist = array();
        array_push($amphurselectlist,':เลือกเขต/อำเภอ');
        foreach($amphurs as $item){
            array_push($amphurselectlist,$item->id.':'.$item->name);
        }

        $districtids = Customer::distinct()->lists('districtid');
        $districts = District::whereIn('id', $districtids)->orderBy('name', 'asc')->get(['id', 'name']);
        $districtselectlist = array();
        array_push($districtselectlist,':เลือกตำบล/แขวง');
        foreach($districts as $item){
            array_push($districtselectlist,$item->id.':'.$item->name);
        }

        if(Auth::user()->isadmin)
            $employees = Employee::all(['id','firstname','lastname']);
        else{
            $provinceid = Auth::user()->provinceid;
            $employees = Employee::whereHas('branch', function($q) use($provinceid)
            {
                $q->where('provinceid', $provinceid);
            })->get(['id','firstname','lastname']);
        }
        $employeeselectlist = array();
        array_push($employeeselectlist,':เลือกพนักงาน');
        foreach($employees as $emp){
            array_push($employeeselectlist,$emp->id.':'.$emp->firstname.' '.$emp->lastname);
        }

        $carmodels = CarModel::whereHas("carbrand", function($q)
        {
            $q->where('ismain',true);

        })->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $cm){
            array_push($carmodelselectlist,$cm->id.':'.$cm->name);
        }

        $colors = Color::all(['id', 'code', 'name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        $defaultProvince = '';
        if(Auth::user()->isadmin == false){
            $defaultProvince = Auth::user()->provinceid;
        }

        return view($this->viewname,
            ['colorselectlist' => implode(";",$colorselectlist),
                'provinceselectlist' => implode(";",$provinceselectlist),
                'addprovinceselectlist' => implode(";",$addprovinceselectlist),
                'amphurselectlist' => implode(";",$amphurselectlist),
                'districtselectlist' => implode(";",$districtselectlist),
                'carmodelselectlist' => implode(";",$carmodelselectlist),
                'employeeselectlist' => implode(";",$employeeselectlist),
                'occupationselectlist' => implode(";",$occupationselectlist),
                'defaultProvince'=>$defaultProvince]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CustomerRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CustomerRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $amphurids = Customer::distinct()->lists('amphurid');
        $amphurs = Amphur::whereIn('id', $amphurids)->orderBy('name', 'asc')->get(['id', 'name']);
        $amphurselectlist = array();
        array_push($amphurselectlist,':เลือกเขต/อำเภอ');
        foreach($amphurs as $item){
            array_push($amphurselectlist,$item->id.':'.$item->name);
        }

        $districtids = Customer::distinct()->lists('districtid');
        $districts = District::whereIn('id', $districtids)->orderBy('name', 'asc')->get(['id', 'name']);
        $districtselectlist = array();
        array_push($districtselectlist,':เลือกตำบล/แขวง');
        foreach($districts as $item){
            array_push($districtselectlist,$item->id.':'.$item->name);
        }

        return ['amphurselectlist'=>implode(";",$amphurselectlist),'districtselectlist'=>implode(";",$districtselectlist)];
    }

    public function getbyid($id)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $cust = Customer::find($id);
        if($cust->birthdate != null)
            $cust->birthdate = date('d-m-Y', strtotime($cust->birthdate));

        $amphurs = Amphur::where('provinceid',$cust->addprovinceid)->orderBy('name', 'asc')->get(['id', 'name']);
        $cust->amphurs = $amphurs;

        $districts = District::where('amphurid',$cust->amphurid)->orderBy('name', 'asc')->get(['id', 'name']);
        $cust->districts = $districts;

        return $cust;
    }
}