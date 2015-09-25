<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;


use App\Models\CarModel;
use App\Facades\GridEncoder;
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

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /*$branchs = Branch::orderBy('name', 'asc')->get(['id', 'name']);
        $branchselectlist = array();
        array_push($branchselectlist,':เลือกสาขา');
        foreach($branchs as $item){
            array_push($branchselectlist,$item->id.':'.$item->name);
        }*/

        $provinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
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

        $employees = Employee::all(['id','firstname','lastname']);
        $employeeselectlist = array();
        array_push($employeeselectlist,':เลือกพนักงาน');
        foreach($employees as $emp){
            array_push($employeeselectlist,$emp->id.':'.$emp->firstname.' '.$emp->lastname);
        }

        $carmodels = CarModel::all(['id','name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $cm){
            array_push($carmodelselectlist,$cm->id.':'.$cm->name);
        }

        $defaultProvince = '';
        if(Auth::user()->isadmin == false){
            $defaultProvince = (Auth::user()->branchid == null ? '' : Auth::user()->branch->provinceid);
        }

        return view('customer',
            [/*'branchselectlist' => implode(";",$branchselectlist),*/
                'provinceselectlist' => implode(";",$provinceselectlist),
                'amphurselectlist' => implode(";",$amphurselectlist),
                'districtselectlist' => implode(";",$districtselectlist),
                'carmodelselectlist' => implode(";",$carmodelselectlist),
                'employeeselectlist' => implode(";",$employeeselectlist),
                'occupationselectlist' => implode(";",$occupationselectlist),
                'defaultProvince'=>$defaultProvince]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new CustomerRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CustomerRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
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
}