<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CarBrand;
use App\Models\CarModelColor;
use App\Models\CarPreemption;
use App\Models\CarPreemptionGiveaway;
use App\Models\Color;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Facades\GridEncoder;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Giveaway;
use App\Models\SystemDatas\Amphur;
use App\Models\SystemDatas\District;
use App\Models\SystemDatas\Occupation;
use App\Models\SystemDatas\Province;
use App\Repositories\CarPreemptionRepository;
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

        $provincebranchids = Branch::where('isheadquarter',true)->distinct()->lists('provinceid');
        $provincebranchs = Province::whereIn('id', $provincebranchids)->orderBy('name', 'asc')->get(['id', 'name']);
        $provincebranchselectlist = array();
        foreach($provincebranchs as $item){
            $provincebranchselectlist[$item->id] = $item->name;
        }

        if(Auth::user()->isadmin){
            $customers = Customer::orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $customers = Customer::where('provinceid', Auth::user()->branch->provinceid)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
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

        if(Auth::user()->isadmin){
            $saleemployees = Employee::where('departmentid', 6)
                ->where('teamid','<>', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $saleemployees = Employee::where('branchid', Auth::user()->branchid)
                ->where('departmentid', 6)
                ->where('teamid','<>', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $saleemployeeselectlist = array();
        $saleemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($saleemployees as $item){
            $saleemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $salemanageremployees = Employee::where('departmentid', 6)
                ->where('teamid', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $salemanageremployees = Employee::where('branchid', Auth::user()->branchid)
                ->where('departmentid', 6)
                ->where('teamid', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $salemanageremployeeselectlist = array();
        $salemanageremployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($salemanageremployees as $item){
            $salemanageremployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $approveremployees = Employee::where('departmentid', 5)
                ->orWhere(function ($query) {
                    $query->where('departmentid', 6)
                        ->where('teamid', 1);
                })
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $approveremployees = Employee::where('branchid', Auth::user()->branchid)
                ->where(function ($query) {
                    $query->where('departmentid', 5)
                        ->orWhere(function ($query) {
                            $query->where('departmentid', 6)
                                ->where('teamid', 1);
                        });
                })
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $approveremployeeselectlist = array();
        $approveremployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($approveremployees as $item){
            $approveremployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        $giveaways = Giveaway::orderBy('name', 'asc')->orderBy('price', 'asc')->get(['id','name','price']);
        $giveawayselectlist = array();
        array_push($giveawayselectlist,':เลือกของแถม');
        foreach($giveaways as $ct){
            array_push($giveawayselectlist,$ct->id.':'.$ct->name.' ('.$ct->price.' บาท)');
        }

        return view('carpreemptionform',
            ['oper' => 'new','pathPrefix' => '../',
                'giveawayFreeDatas' => array(),
                'giveawayBuyDatas' => array(),
                'provincebranchselectlist' => $provincebranchselectlist,
                'customerselectlist' => $customerselectlist,
                'provinceselectlist' => $provinceselectlist,
                'bookingcustomeramphurselectlist' => array(null=>"เลือกเขต/อำเภอ"),
                'buyercustomeramphurselectlist' => array(null=>"เลือกเขต/อำเภอ"),
                'bookingcustomerdistrictselectlist' => array(null=>"เลือกแขวง/ตำบล"),
                'buyercustomerdistrictselectlist' => array(null=>"เลือกแขวง/ตำบล"),
                'occupationselectlist' => $occupationselectlist,
                'carmodelselectlist' => $carmodelselectlist,
                'carsubmodelselectlist' => array(null=>"เลือกรุ่น"),
                'colorselectlist' => array(null=>"เลือกสี"),
                'oldcarbrandselectlist' => $oldcarbrandselectlist,
                'oldcarmodelselectlist' => array(null=>"เลือกแบบ"),
                'giveawayselectlist' => implode(";",$giveawayselectlist),
                'saleemployeeselectlist' => $saleemployeeselectlist,
                'salemanageremployeeselectlist' => $salemanageremployeeselectlist,
                'approveremployeeselectlist' => $approveremployeeselectlist]);
    }

    public function edit($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPreemption::find($id);

        $bookingcustomer = Customer::find($model->bookingcustomerid);
        $model->provincebranchid = $bookingcustomer->provinceid;
        $model->bookingcustomeraddress = $bookingcustomer->address;
        $model->bookingcustomerprovinceid = $bookingcustomer->addprovinceid;
        $model->bookingcustomeramphurid = $bookingcustomer->amphurid;
        $model->bookingcustomerdistrictid = $bookingcustomer->districtid;
        $model->bookingcustomerzipcode = $bookingcustomer->zipcode;
        $model->bookingcustomerphone1 = $bookingcustomer->phone1;
        $model->bookingcustomerphone2 = $bookingcustomer->phone2;
        $model->bookingcustomeroccupationid = $bookingcustomer->occupationid;
        if($bookingcustomer->birthdate != null && $bookingcustomer->birthdate != '')
            $model->bookingcustomerbirthdate = date('d-m-Y', strtotime($bookingcustomer->birthdate));

        if($model->bookingcustomerid != $model->buyercustomerid){
            $model->buyertype = 1;
            $buyercustomer = Customer::find($model->buyercustomerid);
            $model->buyercustomeraddress = $buyercustomer->address;
            $model->buyercustomerprovinceid = $buyercustomer->addprovinceid;
            $model->buyercustomeramphurid = $buyercustomer->amphurid;
            $model->buyercustomerdistrictid = $buyercustomer->districtid;
            $model->buyercustomerzipcode = $buyercustomer->zipcode;
            $model->buyercustomerphone1 = $buyercustomer->phone1;
            $model->buyercustomerphone2 = $buyercustomer->phone2;
            $model->buyercustomeroccupationid = $buyercustomer->occupationid;
            if($buyercustomer->birthdate != null && $buyercustomer->birthdate != '')
                $model->buyercustomerbirthdate = date('d-m-Y', strtotime($buyercustomer->birthdate));
        }
        else{
            $model->buyertype = 0;
        }

        $provincebranchids = Branch::where('isheadquarter',true)->distinct()->lists('provinceid');
        $provincebranchs = Province::whereIn('id', $provincebranchids)->orderBy('name', 'asc')->get(['id', 'name']);
        $provincebranchselectlist = array();
        foreach($provincebranchs as $item){
            $provincebranchselectlist[$item->id] = $item->name;
        }

        if(Auth::user()->isadmin){
            $customers = Customer::orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $customers = Customer::where('provinceid', Auth::user()->branch->provinceid)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
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

        $bookingcustomeramphurs = Amphur::where('provinceid', $model->bookingcustomerprovinceid)->orderBy('name', 'asc')->get(['id', 'name']);
        $bookingcustomeramphurselectlist = array();
        $bookingcustomeramphurselectlist[null] = 'เลือกเขต/อำเภอ';
        foreach($bookingcustomeramphurs as $item){
            $bookingcustomeramphurselectlist[$item->id] = $item->name;
        }

        $buyercustomeramphurselectlist = array();
        if($model->bookingcustomerid != $model->buyercustomerid){
            $buyercustomeramphurs = Amphur::where('provinceid', $model->buyercustomerprovinceid)->orderBy('name', 'asc')->get(['id', 'name']);
            $buyercustomeramphurselectlist[null] = 'เลือกเขต/อำเภอ';
            foreach($buyercustomeramphurs as $item){
                $buyercustomeramphurselectlist[$item->id] = $item->name;
            }
        }

        $bookingcustomerdistricts = District::where('amphurid', $model->bookingcustomeramphurid)->orderBy('name', 'asc')->get(['id', 'name']);
        $bookingcustomerdistrictselectlist = array();
        $bookingcustomerdistrictselectlist[null] = 'เลือกแขวง/ตำบล';
        foreach($bookingcustomerdistricts as $item){
            $bookingcustomerdistrictselectlist[$item->id] = $item->name;
        }

        $buyercustomerdistrictselectlist = array();
        if($model->bookingcustomerid != $model->buyercustomerid){
            $buyercustomerdistricts = District::where('amphurid', $model->buyercustomeramphurid)->orderBy('name', 'asc')->get(['id', 'name']);
            $buyercustomerdistrictselectlist[null] = 'เลือกแขวง/ตำบล';
            foreach($buyercustomerdistricts as $item){
                $buyercustomerdistrictselectlist[$item->id] = $item->name;
            }
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

        $carsubmodels = CarSubModel::where('carmodelid', $model->carmodelid)->orderBy('name', 'asc')->get(['id','name']);
        $carsubmodelselectlist = array();
        $carsubmodelselectlist[null] = 'เลือกรุ่น';
        foreach($carsubmodels as $item){
            $carsubmodelselectlist[$item->id] = $item->name;
        }

        $colorids = CarModelColor::where('carmodelid',$model->carmodelid)->lists('colorid');
        $colors = Color::whereIn('id', $colorids)->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        $colorselectlist[null] = 'เลือกสี';
        foreach($colors as $item){
            $colorselectlist[$item->id] = $item->code.' - '.$item->name;
        }

        $oldcarbrands = CarBrand::where('ismain', false)->orderBy('name', 'asc')->get(['id','name']);
        $oldcarbrandselectlist = array();
        $oldcarbrandselectlist[null] = 'เลือกยี่ห้อรถ';
        foreach($oldcarbrands as $item){
            $oldcarbrandselectlist[$item->id] = $item->name;
        }

        $oldcarmodels = CarModel::where('carbrandid', $model->oldcarbrandid)->orderBy('name', 'asc')->get(['id','name']);
        $oldcarmodelselectlist = array();
        $oldcarmodelselectlist[null] = 'เลือกแบบ';
        foreach($oldcarmodels as $item){
            $oldcarmodelselectlist[$item->id] = $item->name;
        }

        if(Auth::user()->isadmin){
            $saleemployees = Employee::where('departmentid', 6)
                ->where('teamid','<>', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $saleemployees = Employee::where('branchid', Auth::user()->branchid)
                ->where('departmentid', 6)
                ->where('teamid','<>', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $saleemployeeselectlist = array();
        $saleemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($saleemployees as $item){
            $saleemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $salemanageremployees = Employee::where('departmentid', 6)
                ->where('teamid', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $salemanageremployees = Employee::where('branchid', Auth::user()->branchid)
                ->where('departmentid', 6)
                ->where('teamid', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $salemanageremployeeselectlist = array();
        $salemanageremployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($salemanageremployees as $item){
            $salemanageremployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $approveremployees = Employee::where('departmentid', 5)
                ->orWhere(function ($query) {
                    $query->where('departmentid', 6)
                        ->where('teamid', 1);
                })
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $approveremployees = Employee::where('branchid', Auth::user()->branchid)
                ->where(function ($query) {
                    $query->where('departmentid', 5)
                        ->orWhere(function ($query) {
                            $query->where('departmentid', 6)
                                ->where('teamid', 1);
                        });
                })
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $approveremployeeselectlist = array();
        $approveremployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($approveremployees as $item){
            $approveremployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        $giveaways = Giveaway::orderBy('name', 'asc')->orderBy('price', 'asc')->get(['id','name','price']);
        $giveawayselectlist = array();
        array_push($giveawayselectlist,':เลือกของแถม');
        foreach($giveaways as $ct){
            array_push($giveawayselectlist,$ct->id.':'.$ct->name.' ('.$ct->price.' บาท)');
        }

        $giveawayFrees = CarPreemptionGiveaway::where('free', true)
            ->where('carpreemptionid',$id)
            ->get(['id','giveawayid']);
        $giveawayFreeDatas = array();
        foreach($giveawayFrees as $data){
            $obj = (object)array("giveawayid" => $data->giveawayid);
            array_push($giveawayFreeDatas,$obj);
        }

        $giveawayBuys = CarPreemptionGiveaway::where('free', false)
            ->where('carpreemptionid',$id)
            ->get(['id','giveawayid']);
        $giveawayBuyDatas = array();
        foreach($giveawayBuys as $data){
            $obj = (object)array("giveawayid" => $data->giveawayid);
            array_push($giveawayBuyDatas,$obj);
        }

        return view('carpreemptionform',
            ['oper' => 'edit','pathPrefix' => '../../','carpreemption' => $model,
                'giveawayFreeDatas' => $giveawayFreeDatas,
                'giveawayBuyDatas' => $giveawayBuyDatas,
                'provincebranchselectlist' => $provincebranchselectlist,
                'customerselectlist' => $customerselectlist,
                'provinceselectlist' => $provinceselectlist,
                'bookingcustomeramphurselectlist' => $bookingcustomeramphurselectlist,
                'buyercustomeramphurselectlist' => $buyercustomeramphurselectlist,
                'bookingcustomerdistrictselectlist' => $bookingcustomerdistrictselectlist,
                'buyercustomerdistrictselectlist' => $buyercustomerdistrictselectlist,
                'occupationselectlist' => $occupationselectlist,
                'carmodelselectlist' => $carmodelselectlist,
                'carsubmodelselectlist' => $carsubmodelselectlist,
                'colorselectlist' => $colorselectlist,
                'oldcarbrandselectlist' => $oldcarbrandselectlist,
                'oldcarmodelselectlist' => $oldcarmodelselectlist,
                'giveawayselectlist' => implode(";",$giveawayselectlist),
                'saleemployeeselectlist' => $saleemployeeselectlist,
                'salemanageremployeeselectlist' => $salemanageremployeeselectlist,
                'approveremployeeselectlist' => $approveremployeeselectlist]);
    }

    public function view($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPreemption::find($id);

        $bookingcustomer = Customer::find($model->bookingcustomerid);
        $model->provincebranchid = $bookingcustomer->provinceid;
        $model->bookingcustomeraddress = $bookingcustomer->address;
        $model->bookingcustomerprovinceid = $bookingcustomer->addprovinceid;
        $model->bookingcustomeramphurid = $bookingcustomer->amphurid;
        $model->bookingcustomerdistrictid = $bookingcustomer->districtid;
        $model->bookingcustomerzipcode = $bookingcustomer->zipcode;
        $model->bookingcustomerphone1 = $bookingcustomer->phone1;
        $model->bookingcustomerphone2 = $bookingcustomer->phone2;
        $model->bookingcustomeroccupationid = $bookingcustomer->occupationid;
        if($bookingcustomer->birthdate != null && $bookingcustomer->birthdate != '')
            $model->bookingcustomerbirthdate = date('d-m-Y', strtotime($bookingcustomer->birthdate));

        if($model->bookingcustomerid != $model->buyercustomerid){
            $buyercustomer = Customer::find($model->buyercustomerid);
            $model->buyercustomeraddress = $buyercustomer->address;
            $model->buyercustomerprovinceid = $buyercustomer->addprovinceid;
            $model->buyercustomeramphurid = $buyercustomer->amphurid;
            $model->buyercustomerdistrictid = $buyercustomer->districtid;
            $model->buyercustomerzipcode = $buyercustomer->zipcode;
            $model->buyercustomerphone1 = $buyercustomer->phone1;
            $model->buyercustomerphone2 = $buyercustomer->phone2;
            $model->buyercustomeroccupationid = $buyercustomer->occupationid;
            if($buyercustomer->birthdate != null && $buyercustomer->birthdate != '')
                $model->buyercustomerbirthdate = date('d-m-Y', strtotime($buyercustomer->birthdate));
        }

        $provincebranchids = Branch::where('isheadquarter',true)->distinct()->lists('provinceid');
        $provincebranchs = Province::whereIn('id', $provincebranchids)->orderBy('name', 'asc')->get(['id', 'name']);
        $provincebranchselectlist = array();
        foreach($provincebranchs as $item){
            $provincebranchselectlist[$item->id] = $item->name;
        }

        if(Auth::user()->isadmin){
            $customers = Customer::orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $customers = Customer::where('provinceid', Auth::user()->branch->provinceid)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }

        $customerselectlist = array();
        $customerselectlist[$bookingcustomer->id] = $bookingcustomer->title.' '.$bookingcustomer->firstname.' '.$bookingcustomer->lastname;
        if($model->bookingcustomerid != $model->buyercustomerid)
            $customerselectlist[$buyercustomer->id] = $buyercustomer->title.' '.$buyercustomer->firstname.' '.$buyercustomer->lastname;


        $provinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        $provinceselectlist[null] = 'เลือกจังหวัด';
        foreach($provinces as $item){
            $provinceselectlist[$item->id] = $item->name;
        }

        $bookingcustomeramphurselectlist = array();
        $item = Amphur::find($model->bookingcustomeramphurid);
        $bookingcustomeramphurselectlist[$item->id] = $item->name;

        $buyercustomeramphurselectlist = array();
        if($model->bookingcustomerid != $model->buyercustomerid) {
            $item = Amphur::find($model->buyercustomeramphurid);
            $buyercustomeramphurselectlist[$item->id] = $item->name;
        }

        $bookingcustomerdistrictselectlist = array();
        $item = District::find($model->bookingcustomerdistrictid);
        $bookingcustomerdistrictselectlist[$item->id] = $item->name;

        $buyercustomerdistrictselectlist = array();
        if($model->bookingcustomerid != $model->buyercustomerid) {
            $item = District::find($model->buyercustomerdistrictid);
            $buyercustomerdistrictselectlist[$item->id] = $item->name;
        }

        $occupations = Occupation::orderBy('name', 'asc')->get(['id', 'name']);
        $occupationselectlist = array();
        $occupationselectlist[null] = 'เลือกอาชีพ';
        foreach($occupations as $item){
            $occupationselectlist[$item->id] = $item->name;
        }

        $carmodelselectlist = array();
        $item = CarModel::find($model->carmodelid);
        $carmodelselectlist[$item->id] = $item->name;

        $carsubmodelselectlist = array();
        $item = CarSubModel::find($model->carsubmodelid);
        $carsubmodelselectlist[$item->id] = $item->name;

        $colorselectlist = array();
        $item = Color::find($model->colorid);
        $colorselectlist[$item->id] = $item->code.' - '.$item->name;

        $oldcarbrandselectlist = array();
        if($model->oldcarbrandid != null) {
            $item = CarBrand::find($model->oldcarbrandid);
            $oldcarbrandselectlist[$item->id] = $item->name;
        }

        $oldcarmodelselectlist = array();
        if($model->oldcarmodelid != null) {
            $item = CarModel::find($model->oldcarmodelid);
            $oldcarmodelselectlist[$item->id] = $item->name;
        }


        if(Auth::user()->isadmin){
            $saleemployees = Employee::where('departmentid', 6)
                ->where('teamid','<>', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $saleemployees = Employee::where('branchid', Auth::user()->branchid)
                ->where('departmentid', 6)
                ->where('teamid','<>', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $saleemployeeselectlist = array();
        $saleemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($saleemployees as $item){
            $saleemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $salemanageremployees = Employee::where('departmentid', 6)
                ->where('teamid', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $salemanageremployees = Employee::where('branchid', Auth::user()->branchid)
                ->where('departmentid', 6)
                ->where('teamid', 1)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $salemanageremployeeselectlist = array();
        $salemanageremployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($salemanageremployees as $item){
            $salemanageremployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $approveremployees = Employee::where('departmentid', 5)
                ->orWhere(function ($query) {
                    $query->where('departmentid', 6)
                        ->where('teamid', 1);
                })
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $approveremployees = Employee::where('branchid', Auth::user()->branchid)
                ->where(function ($query) {
                    $query->where('departmentid', 5)
                        ->orWhere(function ($query) {
                            $query->where('departmentid', 6)
                                ->where('teamid', 1);
                        });
                })
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $approveremployeeselectlist = array();
        $approveremployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($approveremployees as $item){
            $approveremployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        $giveaways = Giveaway::orderBy('name', 'asc')->orderBy('price', 'asc')->get(['id','name','price']);
        $giveawayselectlist = array();
        array_push($giveawayselectlist,':เลือกของแถม');
        foreach($giveaways as $ct){
            array_push($giveawayselectlist,$ct->id.':'.$ct->name.' ('.$ct->price.' บาท)');
        }

        $giveawayFrees = CarPreemptionGiveaway::where('free', true)
            ->where('carpreemptionid',$id)
            ->get(['id','giveawayid']);
        $giveawayFreeDatas = array();
        foreach($giveawayFrees as $data){
            $obj = (object)array("giveawayid" => $data->giveawayid);
            array_push($giveawayFreeDatas,$obj);
        }

        $giveawayBuys = CarPreemptionGiveaway::where('free', false)
            ->where('carpreemptionid',$id)
            ->get(['id','giveawayid']);
        $giveawayBuyDatas = array();
        foreach($giveawayBuys as $data){
            $obj = (object)array("giveawayid" => $data->giveawayid);
            array_push($giveawayBuyDatas,$obj);
        }

        return view('carpreemptionform',
            ['oper' => 'view','pathPrefix' => '../../','carpreemption' => $model,
                'giveawayFreeDatas' => $giveawayFreeDatas,
                'giveawayBuyDatas' => $giveawayBuyDatas,
                'provincebranchselectlist' => $provincebranchselectlist,
                'customerselectlist' => $customerselectlist,
                'provinceselectlist' => $provinceselectlist,
                'bookingcustomeramphurselectlist' => $bookingcustomeramphurselectlist,
                'buyercustomeramphurselectlist' => $buyercustomeramphurselectlist,
                'bookingcustomerdistrictselectlist' => $bookingcustomerdistrictselectlist,
                'buyercustomerdistrictselectlist' => $buyercustomerdistrictselectlist,
                'occupationselectlist' => $occupationselectlist,
                'carmodelselectlist' => $carmodelselectlist,
                'carsubmodelselectlist' => $carsubmodelselectlist,
                'colorselectlist' => $colorselectlist,
                'oldcarbrandselectlist' => $oldcarbrandselectlist,
                'oldcarmodelselectlist' => $oldcarmodelselectlist,
                'giveawayselectlist' => implode(";",$giveawayselectlist),
                'saleemployeeselectlist' => $saleemployeeselectlist,
                'salemanageremployeeselectlist' => $salemanageremployeeselectlist,
                'approveremployeeselectlist' => $approveremployeeselectlist]);
    }

    public function save(Request $request)
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
                //'price' => 'required',
                'discount' => 'required',
                'subdown' => 'required',
                'accessories' => 'required',

                'cashpledge' => 'required',
                'purchasetype' => 'required',
                'leasingcompanyname' => 'required_if:purchasetype,1',
                'interest' => 'required_if:purchasetype,1',
                'down' => 'required_if:purchasetype,1',
                'installments' => 'required_if:purchasetype,1',
                'cashpledgeredlabel' => 'required',
                'registrationtype' => 'required',
                'registrationfee' => 'required',
                'insurancefee' => 'required',
                'compulsorymotorinsurancefee' => 'required',
                'accessoriesfee' => 'required',
                'otherfee' => 'required',
                'datewantgetcar' => 'required',
                'giveawayadditionalcharges' => 'required',

                'buyercustomerid' => 'required_if:buyertype,1',
                'buyercustomerfirstname' => 'required_if:buyertype,2',
                'buyercustomerphone1' => 'required_if:buyertype,1,2',

                'salesmanemployeeid' => 'required',
                'salesmanageremployeeid' => 'required',
                'approversemployeeid' => 'required',
                'approvaldate' => 'required',

                'customertype' => 'required',
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
                'discount.required' => 'รายละเอียดรถยนตร์ใหม่ ส่วนลด จำเป็นต้องกรอก',
                'subdown.required' => 'รายละเอียดรถยนตร์ใหม่ Sub Down จำเป็นต้องกรอก',
                'accessories.required' => 'รายละเอียดรถยนตร์ใหม่ บวกอุปกรณ์ จำเป็นต้องกรอก',

                'cashpledge.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน เงินมัดจำ จำเป็นต้องกรอก',
                'purchasetype.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ประเภทซื้อรถยนต์ จำเป็นต้องเลือก',
                'leasingcompanyname.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ชื่อบริษัทเช่าซื้อ จำเป็นต้องกรอก',
                'interest.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ดอกเบี้ย จำเป็นต้องกรอก',
                'down.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ดาวน์ จำเป็นต้องกรอก',
                'installments.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน จำนวนงวด จำเป็นต้องกรอก',
                'cashpledgeredlabel.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่ามัดจำป้ายแดง จำเป็นต้องกรอก',
                'registrationtype.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ประเภทจดทะเบียน จำเป็นต้องเลือก',
                'registrationfee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าจดทะเบียน จำเป็นต้องกรอก',
                'insurancefee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าประกันภัย จำเป็นต้องกรอก',
                'compulsorymotorinsurancefee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่า พ.ร.บ. จำเป็นต้องกรอก',
                'accessoriesfee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าอุปกรณ์ จำเป็นต้องกรอก',
                'otherfee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าอื่นๆ จำเป็นต้องกรอก',
                'datewantgetcar.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน วันที่ต้องการรับรถ จำเป็นต้องกรอก',
                'giveawayadditionalcharges' => 'รายละเอียดอื่นๆ ลูกค้าจ่ายเพิ่มเติ่มค่าของแถม จำเป็นต้องกรอก',

                'buyercustomerid.required_if' => 'ผู้ซื้อ กรุณาเลือกชื่อลูกค้า',
                'buyercustomerfirstname.required_if' => 'ผู้ซื้อ ชื่อ จำเป็นต้องกรอก',
                'buyercustomerphone1.required_if' => 'ผู้ซื้อ เบอร์โทร 1 จำเป็นต้องกรอก',

                'salesmanemployeeid.required' => 'พนักงานขาย กรุณาเลือกชื่อพนักงาน',
                'salesmanageremployeeid.required' => 'ผู้จัดการฝ่ายขาย กรุณาเลือกชื่อพนักงาน',
                'approversemployeeid.required' => 'ผู้อนุมัติ กรุณาเลือกชื่อพนักงาน',
                'approvaldate.required' => 'วันที่อนุมัติ จำเป็นต้องกรอก',

                'customertype.required' => 'ประเภทลูกค้า จำเป็นต้องเลือก',
            ]
        );

        $input = $request->all();

        if ($request->has('id'))
            $model = CarPreemption::find($input['id']);
        else
            $model = new CarPreemption;

        $model->bookno = $input['bookno'];
        $model->no = $input['no'];
        $model->date = date('Y-m-d', strtotime($input['date']));

        if($input['customer-type'] == 0){
            $customer = Customer::find($input['bookingcustomerid']);
        }
        else{
            $customer = new Customer;
            if(Auth::user()->isadmin) $customer->provinceid = $input['provincebranchid'];
            else $customer->provinceid = Auth::user()->branch->provinceid;
            $customer->title = $input['bookingcustomertitle'];
            $customer->firstname = $input['bookingcustomerfirstname'];
            $customer->lastname = $input['bookingcustomerlastname'];
        }

        $customer->address = $input['bookingcustomeraddress'];
        $customer->addprovinceid = $input['bookingcustomerprovinceid'];
        $customer->amphurid = $input['bookingcustomeramphurid'];
        $customer->districtid = $input['bookingcustomerdistrictid'];
        $customer->zipcode = $input['bookingcustomerzipcode'];
        $customer->phone1 = $input['bookingcustomerphone1'];
        $customer->phone2 = $input['bookingcustomerphone2'];
        $customer->occupationid = $input['bookingcustomeroccupationid'];
        if($input['bookingcustomerbirthdate'] != null && $input['bookingcustomerbirthdate'] != '')
            $customer->birthdate = date('Y-m-d', strtotime($input['bookingcustomerbirthdate']));

        if($customer->save()) {
            $model->bookingcustomerid = $customer->id;
        }
        else{
            //hack returning error
            $this->validate($request, ['bookno' => 'alpha'], ['bookno.alpha' => 'ไม่สามารถทำการบันทึกข้อมูลผู้จองได้ กรุณาติดต่อผู้ดูแลระบบ!!']);
        }

        $model->carmodelid = $input['carmodelid'];
        $model->carsubmodelid = $input['carsubmodelid'];
        $model->colorid = $input['colorid'];
        $model->price = 999000;//$input['price'];
        $model->discount = $input['discount'];
        $model->subdown = $input['subdown'];
        $model->accessories = $input['accessories'];

        $model->oldcarbrandid = $input['oldcarbrandid'];
        $model->oldcarmodelid = $input['oldcarmodelid'];
        $model->oldcargear = $input['oldcargear'];
        $model->oldcarcolor = $input['oldcarcolor'];
        $model->oldcarenginesize = $input['oldcarenginesize'];
        $model->oldcarlicenseplate = $input['oldcarlicenseplate'];
        $model->oldcaryear = $input['oldcaryear'];
        $model->oldcarprice = $input['oldcarprice'];
        $model->oldcarbuyername = $input['oldcarbuyername'];
        $model->oldcarother = $input['oldcarother'];

        $model->cashpledge = $input['cashpledge'];
        $model->purchasetype = $input['purchasetype'];
        $model->leasingcompanyname = $input['leasingcompanyname'];
        $model->interest = $input['interest'];
        $model->down = $input['down'];
        $model->installments = $input['installments'];
        $model->cashpledgeredlabel = $input['cashpledgeredlabel'];
        $model->registrationtype = $input['registrationtype'];
        $model->registrationfee = $input['registrationfee'];
        $model->insurancefee = $input['insurancefee'];
        $model->compulsorymotorinsurancefee = $input['compulsorymotorinsurancefee'];
        $model->accessoriesfee = $input['accessoriesfee'];
        $model->otherfee = $input['otherfee'];
        $model->datewantgetcar = date('Y-m-d', strtotime($input['datewantgetcar']));
        $model->giveawayadditionalcharges = $input['giveawayadditionalcharges'];

        if($input['buyertype'] == 0){
            $model->buyercustomerid = $model->bookingcustomerid;
        }
        else{
            if($input['buyertype'] == 1){
                $customer = Customer::find($input['buyercustomerid']);
            }
            else{
                $customer = new Customer;
                if(Auth::user()->isadmin) $customer->provinceid = $input['provincebranchid'];
                else $customer->provinceid = Auth::user()->branch->provinceid;
                $customer->title = $input['buyercustomertitle'];
                $customer->firstname = $input['buyercustomerfirstname'];
                $customer->lastname = $input['buyercustomerlastname'];
            }

            $customer->address = $input['buyercustomeraddress'];
            $customer->addprovinceid = $input['buyercustomerprovinceid'];
            $customer->amphurid = $input['buyercustomeramphurid'];
            $customer->districtid = $input['buyercustomerdistrictid'];
            $customer->zipcode = $input['buyercustomerzipcode'];
            $customer->phone1 = $input['buyercustomerphone1'];
            $customer->phone2 = $input['buyercustomerphone2'];
            $customer->occupationid = $input['buyercustomeroccupationid'];
            if($input['buyercustomerbirthdate'] != null && $input['buyercustomerbirthdate'] != '')
                $customer->birthdate = date('Y-m-d', strtotime($input['buyercustomerbirthdate']));

            if($customer->save()) {
                $model->buyercustomerid = $customer->id;
            }
            else{
                //hack returning error
                $this->validate($request, ['bookno' => 'alpha'], ['bookno.alpha' => 'ไม่สามารถทำการบันทึกข้อมูลผู้ซื้อได้ กรุณาติดต่อผู้ดูแลระบบ!!']);
            }
        }

        $model->salesmanemployeeid = $input['salesmanemployeeid'];
        $employee = Employee::find($input['salesmanemployeeid']);
        $model->salesmanteamid = $employee->teamid;
        $model->salesmanageremployeeid = $input['salesmanageremployeeid'];
        $model->approversemployeeid = $input['approversemployeeid'];
        $model->approvaldate = date('Y-m-d', strtotime($input['approvaldate']));

        if ($request->has('place')) $model->place = $input['place'];
        if ($request->has('showroom')) $model->showroom = $input['showroom'];
        if ($request->has('booth')) $model->booth = $input['booth'];
        if ($request->has('leaflet')) $model->leaflet = $input['leaflet'];
        if ($request->has('businesscard')) $model->businesscard = $input['businesscard'];
        if ($request->has('invitationcard')) $model->invitationcard = $input['invitationcard'];
        if ($request->has('phone')) $model->phone = $input['phone'];
        if ($request->has('signshowroom')) $model->signshowroom = $input['signshowroom'];
        if ($request->has('spotradiowalkin')) $model->spotradiowalkin = $input['spotradiowalkin'];
        if ($request->has('recommendedby')) $model->recommendedby = $input['recommendedby'];
        $model->recommendedbyname = $input['recommendedbyname'];
        if ($request->has('recommendedbytype')) $model->recommendedbytype = $input['recommendedbytype'];
        if ($request->has('customertype')) $model->customertype = $input['customertype'];
        $model->remark = $input['remark'];

        if($model->save()) {

            $giveawayFreeData = $request->giveawayFreeData;
            $giveawayBuyData = $request->giveawayBuyData;

            $giveawayFreeData = json_decode($giveawayFreeData,true);
            $giveawayBuyData = json_decode($giveawayBuyData,true);

            foreach($giveawayFreeData as $data){
                $obj = new CarPreemptionGiveaway;
                $obj->carpreemptionid = $model->id;
                $obj->giveawayid = $data["giveawayid"];
                $obj->free = true;
                $obj->save();
            }

            foreach($giveawayBuyData as $data){
                $obj = new CarPreemptionGiveaway;
                $obj->carpreemptionid = $model->id;
                $obj->giveawayid = $data["giveawayid"];
                $obj->free = false;
                $obj->save();
            }

            return redirect()->action('CarPreemptionController@edit',['id' => $model->id]);
        }
        else{
            //hack returning error
            $this->validate($request, ['bookno' => 'alpha'], ['bookno.alpha' => 'ไม่สามารถทำการบันทึกข้อมูลใบจองได้ กรุณาติดต่อผู้ดูแลระบบ!!']);
        }
    }
}