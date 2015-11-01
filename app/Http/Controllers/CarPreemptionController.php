<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Models\CarBrand;
use App\Models\CarPreemption;
use App\Models\Color;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Facades\GridEncoder;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Giveaway;
use App\Models\SystemDatas\Occupation;
use App\Models\SystemDatas\Province;
use App\Repositories\CarPreemptionRepository;
use Illuminate\Http\Request;
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

        $bookingcustomerids = CarPreemption::distinct()->lists('bookingcustomerid');
        $bookingcustomers = Customer::whereIn('id', $bookingcustomerids)->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $bookingcustomerselectlist = array();
        foreach($bookingcustomers as $item){
            array_push($bookingcustomerselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $carmodelids = CarPreemption::distinct()->lists('carmodelid');
        $carmodels = CarModel::whereIn('id', $carmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodelids = CarPreemption::distinct()->lists('carsubmodelid');
        $carsubmodels = CarSubModel::whereIn('id', $carsubmodelids)->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        $colorids = CarPreemption::distinct()->lists('colorid');
        $colors = Color::whereIn('id', $colorids)->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        $buyercustomerids = CarPreemption::distinct()->lists('buyercustomerid');
        $buyercustomers = Customer::whereIn('id', $buyercustomerids)->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $buyercustomerselectlist = array();
        foreach($buyercustomers as $item){
            array_push($buyercustomerselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $salesmanemployeeids = CarPreemption::distinct()->lists('salesmanemployeeid');
        $salesmanemployees = Employee::whereIn('id', $salesmanemployeeids)->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $salesmanemployeeselectlist = array();
        foreach($salesmanemployees as $item){
            array_push($salesmanemployeeselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $salesmanageremployeeids = CarPreemption::distinct()->lists('salesmanageremployeeid');
        $salesmanageremployees = Employee::whereIn('id', $salesmanageremployeeids)->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $salesmanageremployeeselectlist = array();
        foreach($salesmanageremployees as $item){
            array_push($salesmanageremployeeselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $approversemployeeids = CarPreemption::distinct()->lists('approversemployeeid');
        $approversemployees = Employee::whereIn('id', $approversemployeeids)->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $approversemployeeselectlist = array();
        foreach($approversemployees as $item){
            array_push($approversemployeeselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        return view('carpreemption',
            ['bookingcustomerselectlist' => implode(";",$bookingcustomerselectlist),
            'carmodelselectlist' => implode(";",$carmodelselectlist),
            'carsubmodelselectlist' => implode(";",$carsubmodelselectlist),
            'colorselectlist' => implode(";",$colorselectlist),
            'buyercustomerselectlist' => implode(";",$buyercustomerselectlist),
            'salesmanemployeeselectlist' => implode(";",$salesmanemployeeselectlist),
            'salesmanageremployeeselectlist' => implode(";",$salesmanageremployeeselectlist),
            'approversemployeeselectlist' => implode(";",$approversemployeeselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarPreemptionRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarPreemptionRepository(), $request);
    }

    public function newcarpreemption()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $customers = Customer::orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get(['id','title','firstname','lastname']);
        $customerselectlist = array();
        $customerselectlist[null] = 'เลือกชื่อลูกค้า';
        foreach($customers as $item){
            $customerselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        $provinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        $provinceselectlist[null] = 'เลือกจังหวัด';
        foreach($provinces as $item){
            $provinceselectlist[$item->id] = $item->name;
        }

        $occupations = Occupation::orderBy('name', 'asc')->get(['id', 'name']);
        $occupationselectlist = array();
        $occupationselectlist[null] = 'เลือกอาชีพ';
        foreach($occupations as $item){
            $occupationselectlist[$item->id] = $item->name;
        }

        $carmodels = CarModel::whereHas("carbrand", function($q)
        {
            $q->where('ismain',true);

        })->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        $carmodelselectlist[null] = 'เลือกแบบ';
        foreach($carmodels as $item){
            $carmodelselectlist[$item->id] = $item->name;
        }


        $oldcarbrands = CarBrand::where('ismain', false)->orderBy('name', 'asc')->get(['id','name']);
        $oldcarbrandselectlist = array();
        $oldcarbrandselectlist[null] = 'เลือกยี่ห้อรถ';
        foreach($oldcarbrands as $item){
            $oldcarbrandselectlist[$item->id] = $item->name;
        }

        $saleemployees = Employee::where('departmentid', 6)
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get(['id','title','firstname','lastname']);
        $saleemployeeselectlist = array();
        $saleemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($saleemployees as $item){
            $saleemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        $giveaways = Giveaway::orderBy('name', 'asc')->get(['id','name']);
        $giveawayselectlist = array();
        array_push($giveawayselectlist,':เลือกของแถม');
        foreach($giveaways as $ct){
            array_push($giveawayselectlist,$ct->id.':'.$ct->name);
        }

        return view('carpreemptionadd',
            ['customerselectlist' => $customerselectlist,
                'provinceselectlist' => $provinceselectlist,
                'occupationselectlist' => $occupationselectlist,
                'carmodelselectlist' => $carmodelselectlist,
                'oldcarbrandselectlist' => $oldcarbrandselectlist,
                'saleemployeeselectlist' => $saleemployeeselectlist,
                'giveawayselectlist' => implode(";",$giveawayselectlist)]);
    }

    public function add(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $this->validate($request, [
            'bookno' => 'required',
            'no' => 'required',
            'date' => 'required',

            'bookingcustomerid' => 'required_if:customer-type,0',
            'bookingcustomerfirstname' => 'required_if:customer-type,1',
            'bookingcustomerphone1' => 'required',

            'carmodelid' => 'required',
            'carsubmodelid' => 'required',
            'colorid' => 'required',
            'price' => 'required',

            'buyercustomerid' => 'required_if:buyertype,1',
            'buyercustomerfirstname' => 'required_if:buyertype,2',
            'buyercustomerphone1' => 'required_if:buyertype,1,2',
            ],
            [
                'bookno.required' => 'ข้อมูล เล่มที่ จำเป็นต้องกรอก',
                'no.required' => 'ข้อมูล เลขที่ จำเป็นต้องกรอก',
                'date.required' => 'ข้อมูล วันที่ จำเป็นต้องกรอก',

                'bookingcustomerid.required_if' => 'ผู้สั่งจอง กรุณาเลือกชื่อลูกค้า',
                'bookingcustomerfirstname.required_if' => 'ผู้สั่งจอง ชื่อ จำเป็นต้องกรอก',
                'bookingcustomerphone1.required' => 'ผู้สั่งจอง เบอร์โทร 1 จำเป็นต้องกรอก',

                'carmodelid.required' => 'รายละเอียดรถยนตร์ใหม่ กรุณาเลือกแบบ',
                'carsubmodelid.required' => 'รายละเอียดรถยนตร์ใหม่ กรุณาเลือกรุ่น',
                'colorid.required' => 'รายละเอียดรถยนตร์ใหม่ กรุณาเลือกสี',
                'price.required' => 'รายละเอียดรถยนตร์ใหม่ ราคา ต้องมีข้อมูล (กรุณาเพิ่มข้อมูล ราคา ของรถรุ่นนี้ แล้วทำการเลือกรุ่น ใหม่อีกครั้ง)',

                'buyercustomerid.required_if' => 'ผู้ซื้อ กรุณาเลือกชื่อลูกค้า',
                'buyercustomerfirstname.required_if' => 'ผู้ซื้อ ชื่อ จำเป็นต้องกรอก',
                'buyercustomerphone1.required_if' => 'ผู้ซื้อ เบอร์โทร 1 จำเป็นต้องกรอก',
            ]
        );
    }

    public function edit()
    {

    }
}