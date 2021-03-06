<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarPayment;
use App\Models\CarPreemption;
use App\Models\CarPreemptionGiveaway;
use App\Models\CarSubModel;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\FinaceCompany;
use App\Models\Giveaway;
use App\Models\InterestRateType;
use App\Models\Pricelist;
use App\Models\RedLabel;
use App\Models\Redlabelhistory;
use App\Models\SystemDatas\Amphur;
use App\Models\SystemDatas\District;
use App\Models\SystemDatas\Occupation;
use App\Models\SystemDatas\Province;
use App\Repositories\CarPreemptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request as SupportRequest;

class CarPreemptionController extends Controller {

    protected $menuPermissionName = "การจอง";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $bookingcustomers = Customer::has('bookingCarPreemptions')->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $bookingcustomerselectlist = array();
        foreach($bookingcustomers as $item){
            array_push($bookingcustomerselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $carmodels = CarModel::has('carPreemptions')->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodels = CarSubModel::has('carPreemptions')->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        $colors = Color::has('carPreemptions')->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        $buyercustomers = Customer::has('buyerCarPreemptions')->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $buyercustomerselectlist = array();
        foreach($buyercustomers as $item){
            array_push($buyercustomerselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $salesmanemployees = Employee::has('salesmanCarPreemptions')->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $salesmanemployeeselectlist = array();
        foreach($salesmanemployees as $item){
            array_push($salesmanemployeeselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $salesmanageremployees = Employee::has('salesmanagerCarPreemptions')->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $salesmanageremployeeselectlist = array();
        foreach($salesmanageremployees as $item){
            array_push($salesmanageremployeeselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $approversemployees = Employee::has('approversCarPreemptions')->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $approversemployeeselectlist = array();
        foreach($approversemployees as $item){
            array_push($approversemployeeselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        $pricelists = Pricelist::has('carPreemptions')->orderBy('sellingpricewithaccessories', 'asc')
            ->get(['id', 'sellingpricewithaccessories', 'promotion']);
        $priceselectlist = array();
        foreach($pricelists as $item){
            //if($item->promotion != null && $item->promotion != '')
                //array_push($priceselectlist,$item->id.':'.$item->sellingpricewithaccessories.' ('.$item->promotion.')');
            //else
                array_push($priceselectlist,$item->id.':'.$item->sellingpricewithaccessories);
        }

        return view('carpreemption',
            ['bookingcustomerselectlist' => implode(";",$bookingcustomerselectlist),
            'carmodelselectlist' => implode(";",$carmodelselectlist),
            'carsubmodelselectlist' => implode(";",$carsubmodelselectlist),
            'colorselectlist' => implode(";",$colorselectlist),
            'buyercustomerselectlist' => implode(";",$buyercustomerselectlist),
            'salesmanemployeeselectlist' => implode(";",$salesmanemployeeselectlist),
            'salesmanageremployeeselectlist' => implode(";",$salesmanageremployeeselectlist),
            'approversemployeeselectlist' => implode(";",$approversemployeeselectlist),
            'priceselectlist' => implode(";",$priceselectlist)]);
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

        $provincebranchs = Province::whereHas('branchs', function($q){
            $q->where('isheadquarter', true);
        })->orderBy('name', 'asc')->get(['id', 'name']);
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
            $customers = Customer::where('provinceid', Auth::user()->provinceid)
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
            $saleemployees = Employee::where('provinceid', Auth::user()->provinceid)
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
            $salemanageremployees = Employee::where('provinceid', Auth::user()->provinceid)
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
            $approveremployees = Employee::where('provinceid', Auth::user()->provinceid)
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

        $giveaways = Giveaway::orderBy('name', 'asc')->orderBy('saleprice', 'asc')->get(['id','name','saleprice']);
        $giveawayselectlist = array();
        array_push($giveawayselectlist,':เลือกของแถม');
        foreach($giveaways as $ct){
            array_push($giveawayselectlist,$ct->id.':'.$ct->name.' ('.$ct->saleprice.' บาท)');
        }

        $finacecompanies = FinaceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $finacecompanyselectlist = array();
        $finacecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($finacecompanies as $item){
            $finacecompanyselectlist[$item->id] = $item->name;
        }

        $interestratetypeselectlist = array();
        $interestratetypeselectlist[null] = 'เลือกประเภทอัตราดอกเบี้ย';

        $bookingcustomeramphurid = SupportRequest::old('bookingcustomeramphurid');
        $bookingcustomerprovinceid = SupportRequest::old('bookingcustomerprovinceid');
        $bookingcustomerid = SupportRequest::old('bookingcustomerid');
        $buyercustomerid = SupportRequest::old('buyercustomerid');
        $buyercustomeramphurid = SupportRequest::old('bookingcustomeramphurid');
        $buyercustomerprovinceid = SupportRequest::old('bookingcustomerprovinceid');
        $carmodelid = SupportRequest::old('carmodelid');
        $oldcarbrandid = SupportRequest::old('oldcarbrandid');
        $date = SupportRequest::old('date');
        $carsubmodelid = SupportRequest::old('carsubmodelid');

        $bookingcustomeramphurselectlist = array();
        $bookingcustomeramphurselectlist[null] = 'เลือกเขต/อำเภอ';
        if($bookingcustomerprovinceid != null && $bookingcustomerprovinceid != ''){
            $bookingcustomeramphurs = Amphur::where('provinceid', $bookingcustomerprovinceid)->orderBy('name', 'asc')->get(['id', 'name']);
            foreach($bookingcustomeramphurs as $item){
                $bookingcustomeramphurselectlist[$item->id] = $item->name;
            }
        }

        $bookingcustomerdistrictselectlist = array();
        $bookingcustomerdistrictselectlist[null] = 'เลือกแขวง/ตำบล';
        if($bookingcustomeramphurid != null && $bookingcustomeramphurid != ''){
            $bookingcustomerdistricts = District::where('amphurid', $bookingcustomeramphurid)->orderBy('name', 'asc')->get(['id', 'name']);
            foreach($bookingcustomerdistricts as $item){
                $bookingcustomerdistrictselectlist[$item->id] = $item->name;
            }
        }

        $buyercustomeramphurselectlist = array();
        $buyercustomeramphurselectlist[null] = 'เลือกเขต/อำเภอ';
        if($bookingcustomerid != $buyercustomerid && $buyercustomerprovinceid != null && $buyercustomerprovinceid != ''){
            $buyercustomeramphurs = Amphur::where('provinceid', $buyercustomerprovinceid)->orderBy('name', 'asc')->get(['id', 'name']);
            foreach($buyercustomeramphurs as $item){
                $buyercustomeramphurselectlist[$item->id] = $item->name;
            }
        }

        $buyercustomerdistrictselectlist = array();
        $buyercustomerdistrictselectlist[null] = 'เลือกแขวง/ตำบล';
        if($bookingcustomerid != $buyercustomerid && $buyercustomeramphurid != null && $buyercustomeramphurid != ''){
            $buyercustomerdistricts = District::where('amphurid', $buyercustomeramphurid)->orderBy('name', 'asc')->get(['id', 'name']);
            foreach($buyercustomerdistricts as $item){
                $buyercustomerdistrictselectlist[$item->id] = $item->name;
            }
        }

        $carsubmodelselectlist = array();
        $carsubmodelselectlist[null] = 'เลือกรุ่น';
        $colorselectlist = array();
        $colorselectlist[null] = 'เลือกสี';
        $registerprovinceselectlist = array();
        $registerprovinceselectlist[null] = 'เลือกจังหวัด';
        $colorprices = array();
        $provinceregistrationfee = array();
        if($carmodelid != null && $carmodelid != ''){
            $carsubmodels = CarSubModel::where('carmodelid', $carmodelid)->orderBy('name', 'asc')->get(['id','name']);
            foreach($carsubmodels as $item){
                $carsubmodelselectlist[$item->id] = $item->name;
            }

            $colors = Color::with(['carModelColors' => function ($query) use($carmodelid){
                $query->where('carmodelid', $carmodelid);
            }])->whereHas('carModelColors', function($q) use($carmodelid){
                $q->where('carmodelid', $carmodelid);
            })->orderBy('code', 'asc')->get(['id', 'code', 'name']);

            foreach($colors as $item){
                $colorselectlist[$item->id] = $item->code.' - '.$item->name;

                $obj = (object)array("colorid" => $item->id, "price" => $item->carModelColors[0]->price);
                array_push($colorprices, $obj);
            }

            $provinces = Province::with(['carModelRegisters' => function ($query) use($carmodelid){
                $query->where('carmodelid', $carmodelid);
            }])->whereHas('carModelRegisters', function($q) use($carmodelid){
                $q->where('carmodelid', $carmodelid);
            })->orderBy('name', 'asc')->get(['id', 'name']);

            foreach($provinces as $item){
                $registerprovinceselectlist[$item->id] = $item->name;

                $registrationfee = array();
                array_push($registrationfee, (object)array("type" => 0, "price" => $item->carModelRegisters[0]->individualregistercost));
                array_push($registrationfee, (object)array("type" => 1, "price" => $item->carModelRegisters[0]->companyregistercost));
                array_push($registrationfee, (object)array("type" => 2, "price" => $item->carModelRegisters[0]->governmentregistercost));

                $obj = (object)array("provinceid" => $item->id, "registrationfee" => $registrationfee);
                array_push($provinceregistrationfee, $obj);
            }
        }

        $oldcarmodelselectlist = array();
        $oldcarmodelselectlist[null] = 'เลือกแบบ';
        if($oldcarbrandid != null && $oldcarbrandid != '') {
            $oldcarmodels = CarModel::where('carbrandid', $oldcarbrandid)->orderBy('name', 'asc')->get(['id', 'name']);
            foreach ($oldcarmodels as $item) {
                $oldcarmodelselectlist[$item->id] = $item->name;
            }
        }

        $priceselectlist = array();
        $priceselectlist[null] = 'เลือกราคา';
        $carprices = array();
        if($carsubmodelid != null && $carsubmodelid != '' && $date != null && $date != '') {
            $date = date('Y-m-d', strtotime($date));
            $pricelists = Pricelist::where('carsubmodelid',$carsubmodelid)
                ->where('effectivefrom','<=',$date)
                ->where('effectiveTo','>=',$date)
                ->orderBy('sellingpricewithaccessories', 'asc')->get(['id', 'sellingpricewithaccessories', 'promotion']);
            foreach ($pricelists as $item) {
                if($item->promotion != null && $item->promotion != '')
                    $priceselectlist[$item->id] = $item->sellingpricewithaccessories.' ('.$item->promotion.')';
                else
                    $priceselectlist[$item->id] = $item->sellingpricewithaccessories;

                $obj = (object)array("pricelistid" => $item->id, "price" => $item->sellingpricewithaccessories);
                array_push($carprices, $obj);
            }
        }

        $giveawayFreeData = SupportRequest::old('giveawayFreeData');
        $giveawayBuyData = SupportRequest::old('giveawayBuyData');

        $giveawayFreeData = json_decode($giveawayFreeData,true);
        $giveawayBuyData = json_decode($giveawayBuyData,true);

        $giveawayFreeDatas = array();
        if($giveawayFreeData != null && $giveawayFreeData != '') {
            foreach ($giveawayFreeData as $data) {
                $obj = (object)array("id" => $data["id"], "giveawayid" => $data["giveawayid"], "price" => $data["price"]);
                array_push($giveawayFreeDatas, $obj);
            }
        }

        $giveawayBuyDatas = array();
        if($giveawayBuyData != null && $giveawayBuyData != '') {
            foreach ($giveawayBuyData as $data) {
                $obj = (object)array("id" => $data["id"], "giveawayid" => $data["giveawayid"]);
                array_push($giveawayBuyDatas, $obj);
            }
        }

        $carpreemption = new Carpreemption;
        $carpreemption->date = date('d-m-Y');
        $carpreemption->approvaldate = date('d-m-Y');
        $carpreemption->documentstatus = 0;

        return view('carpreemptionform',
            ['oper' => 'new','pathPrefix' => '../','carpreemption' => $carpreemption,
                'carprices' => $carprices,
                'colorprices' => $colorprices,
                'provinceregistrationfee' => $provinceregistrationfee,
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
                'approveremployeeselectlist' => $approveremployeeselectlist,
                'finacecompanyselectlist' => $finacecompanyselectlist,
                'interestratetypeselectlist' => $interestratetypeselectlist,
                'priceselectlist' => $priceselectlist,
                'registerprovinceselectlist' => $registerprovinceselectlist]);
    }

    public function edit($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPreemption::find($id);

        $carPayment = CarPayment::where('carpreemptionid',$id)->first();
        if(!Auth::user()->isadmin && $carPayment != null && $carPayment->deliverycarbookno != null && $carPayment->deliverycarbookno != '')
            return "ไม่สามารถแก้ไขข้อมูลการจองได้ เนื่องจากมีการส่งรถแล้ว!!";
        
        $bookingcustomer = Customer::find($model->bookingcustomerid);
        $model->bookingcustomername = $bookingcustomer->title.' '.$bookingcustomer->firstname.' '.$bookingcustomer->lastname;
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

        $provincebranchs = Province::whereHas('branchs', function($q){
            $q->where('isheadquarter', true);
        })->orderBy('name', 'asc')->get(['id', 'name']);
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
            $customers = Customer::where('provinceid', Auth::user()->provinceid)
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

        $colorprices = array();
        $colors = Color::with(['carModelColors' => function ($query) use($model){
            $query->where('carmodelid', $model->carmodelid);
        }])->whereHas('carModelColors', function($q) use($model){
            $q->where('carmodelid', $model->carmodelid);
        })->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        $colorselectlist[null] = 'เลือกสี';
        foreach($colors as $item){
            $colorselectlist[$item->id] = $item->code.' - '.$item->name;

            $obj = (object)array("colorid" => $item->id, "price" => $item->carModelColors[0]->price);
            array_push($colorprices, $obj);
        }

        $provinceregistrationfee = array();
        $provinces = Province::with(['carModelRegisters' => function ($query) use($model){
            $query->where('carmodelid', $model->carmodelid);
        }])->whereHas('carModelRegisters', function($q) use($model){
            $q->where('carmodelid', $model->carmodelid);
        })->orderBy('name', 'asc')->get(['id', 'name']);
        $registerprovinceselectlist = array();
        $registerprovinceselectlist[null] = 'เลือกจังหวัด';
        foreach($provinces as $item){
            $registerprovinceselectlist[$item->id] = $item->name;

            $registrationfee = array();
            array_push($registrationfee, (object)array("type" => 0, "price" => $item->carModelRegisters[0]->individualregistercost));
            array_push($registrationfee, (object)array("type" => 1, "price" => $item->carModelRegisters[0]->companyregistercost));
            array_push($registrationfee, (object)array("type" => 2, "price" => $item->carModelRegisters[0]->governmentregistercost));

            $obj = (object)array("provinceid" => $item->id, "registrationfee" => $registrationfee);
            array_push($provinceregistrationfee, $obj);
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
            $saleemployees = Employee::where('provinceid', Auth::user()->provinceid)
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
            $salemanageremployees = Employee::where('provinceid', Auth::user()->provinceid)
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
            $approveremployees = Employee::where('provinceid', Auth::user()->provinceid)
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

        $giveaways = Giveaway::orderBy('name', 'asc')->orderBy('saleprice', 'asc')->get(['id','name','saleprice']);
        $giveawayselectlist = array();
        array_push($giveawayselectlist,':เลือกของแถม');
        foreach($giveaways as $ct){
            array_push($giveawayselectlist,$ct->id.':'.$ct->name.' ('.$ct->saleprice.' บาท)');
        }

        $giveawayFreeDatas = array();
        $giveawayBuyDatas = array();

        $giveawayFreeData = SupportRequest::old('giveawayFreeData');
        $giveawayBuyData = SupportRequest::old('giveawayBuyData');

        if($giveawayFreeData != null && $giveawayFreeData != ''){
            $giveawayFreeData = json_decode($giveawayFreeData,true);
            foreach ($giveawayFreeData as $data) {
                $obj = (object)array("id" => $data["id"], "giveawayid" => $data["giveawayid"], "price" => $data["price"]);
                array_push($giveawayFreeDatas, $obj);
            }
        }
        else{
            $giveawayFrees = CarPreemptionGiveaway::where('free', true)
                ->where('carpreemptionid',$id)
                ->get(['id','giveawayid','price']);

            foreach($giveawayFrees as $data){
                $obj = (object)array("id" => $data->id, "giveawayid" => $data->giveawayid, "price" => $data->price);
                array_push($giveawayFreeDatas,$obj);
            }
        }

        if($giveawayBuyData != null && $giveawayBuyData != ''){
            $giveawayBuyData = json_decode($giveawayBuyData,true);
            foreach ($giveawayBuyData as $data) {
                $obj = (object)array("id" => $data["id"], "giveawayid" => $data["giveawayid"]);
                array_push($giveawayBuyDatas, $obj);
            }
        }
        else{
            $giveawayBuys = CarPreemptionGiveaway::where('free', false)
                ->where('carpreemptionid',$id)
                ->get(['id','giveawayid']);

            foreach($giveawayBuys as $data){
                $obj = (object)array("id" => $data->id, "giveawayid" => $data->giveawayid);
                array_push($giveawayBuyDatas,$obj);
            }
        }

        $finacecompanies = FinaceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $finacecompanyselectlist = array();
        $finacecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($finacecompanies as $item){
            $finacecompanyselectlist[$item->id] = $item->name;
        }

        $interestratetypes = InterestRateType::where('finacecompanyid', $model->finacecompanyid)
            ->orderBy('name', 'asc')->get(['id', 'name']);
        $interestratetypeselectlist = array();
        $interestratetypeselectlist[null] = 'เลือกประเภทอัตราดอกเบี้ย';
        foreach ($interestratetypes as $item) {
            $interestratetypeselectlist[$item->id] = $item->name;
        }

        $carprices = array();
        $priceselectlist = array();
        $pricelists = Pricelist::where('carsubmodelid',$model->carsubmodelid)
            ->where('effectivefrom','<=',$model->date)
            ->where('effectiveTo','>=',$model->date)
            ->orderBy('sellingpricewithaccessories', 'asc')->get(['id', 'sellingpricewithaccessories', 'promotion']);
        foreach ($pricelists as $item) {
            if($item->promotion != null && $item->promotion != '')
                $priceselectlist[$item->id] = $item->sellingpricewithaccessories.' ('.$item->promotion.')';
            else
                $priceselectlist[$item->id] = $item->sellingpricewithaccessories;

            $obj = (object)array("pricelistid" => $item->id, "price" => $item->sellingpricewithaccessories);
            array_push($carprices, $obj);
        }

        $model->date = date('d-m-Y', strtotime($model->date));
        $model->datewantgetcar = date('d-m-Y', strtotime($model->datewantgetcar));
        $model->approvaldate = date('d-m-Y', strtotime($model->approvaldate));
        if($model->contractdate != null && $model->contractdate != '')
            $model->contractdate = date('d-m-Y', strtotime($model->contractdate));

        return view('carpreemptionform',
            ['oper' => 'edit','pathPrefix' => '../../','carpreemption' => $model,
                'carprices' => $carprices,
                'colorprices' => $colorprices,
                'provinceregistrationfee' => $provinceregistrationfee,
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
                'approveremployeeselectlist' => $approveremployeeselectlist,
                'finacecompanyselectlist' => $finacecompanyselectlist,
                'interestratetypeselectlist' => $interestratetypeselectlist,
                'priceselectlist' => $priceselectlist,
                'registerprovinceselectlist' => $registerprovinceselectlist]);
    }

    public function view($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPreemption::find($id);

        $bookingcustomer = Customer::find($model->bookingcustomerid);
        $model->bookingcustomername = $bookingcustomer->title.' '.$bookingcustomer->firstname.' '.$bookingcustomer->lastname;
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

        $provincebranchs = Province::whereHas('branchs', function($q){
            $q->where('isheadquarter', true);
        })->orderBy('name', 'asc')->get(['id', 'name']);
        $provincebranchselectlist = array();
        foreach($provincebranchs as $item){
            $provincebranchselectlist[$item->id] = $item->name;
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
        $bookingcustomeramphurselectlist[null] = 'เลือกเขต/อำเภอ';
        if($model->bookingcustomeramphurid != null && $model->bookingcustomeramphurid != '') {
            $item = Amphur::find($model->bookingcustomeramphurid);
            $bookingcustomeramphurselectlist[$item->id] = $item->name;
        }

        $buyercustomeramphurselectlist = array();
        $buyercustomeramphurselectlist[null] = 'เลือกเขต/อำเภอ';
        if($model->bookingcustomerid != $model->buyercustomerid && $model->buyercustomeramphurid != null && $model->buyercustomeramphurid != '') {
            $item = Amphur::find($model->buyercustomeramphurid);
            $buyercustomeramphurselectlist[$item->id] = $item->name;
        }

        $bookingcustomerdistrictselectlist = array();
        $bookingcustomerdistrictselectlist[null] = 'เลือกแขวง/ตำบล';
        if($model->bookingcustomerdistrictid != null && $model->bookingcustomerdistrictid != '') {
            $item = District::find($model->bookingcustomerdistrictid);
            $bookingcustomerdistrictselectlist[$item->id] = $item->name;
        }

        $buyercustomerdistrictselectlist = array();
        $buyercustomerdistrictselectlist[null] = 'เลือกแขวง/ตำบล';
        if($model->bookingcustomerid != $model->buyercustomerid && $model->buyercustomerdistrictid != null && $model->buyercustomerdistrictid != '') {
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

        $registerprovinceselectlist = array();
        if($model->registerprovinceid != null && $model->registerprovinceid != ''){
            $item = Province::find($model->registerprovinceid);
            $registerprovinceselectlist[$item->id] = $item->name;
        }

        $oldcarbrandselectlist = array();
        $oldcarbrandselectlist[null] = 'เลือกยี่ห้อรถ';
        if($model->oldcarbrandid != null) {
            $item = CarBrand::find($model->oldcarbrandid);
            $oldcarbrandselectlist[$item->id] = $item->name;
        }

        $oldcarmodelselectlist = array();
        $oldcarmodelselectlist[null] = 'เลือกแบบ';
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
            $saleemployees = Employee::where('provinceid', Auth::user()->provinceid)
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
            $salemanageremployees = Employee::where('provinceid', Auth::user()->provinceid)
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
            $approveremployees = Employee::where('provinceid', Auth::user()->provinceid)
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

        $giveaways = Giveaway::orderBy('name', 'asc')->orderBy('saleprice', 'asc')->get(['id','name','saleprice']);
        $giveawayselectlist = array();
        array_push($giveawayselectlist,':เลือกของแถม');
        foreach($giveaways as $ct){
            array_push($giveawayselectlist,$ct->id.':'.$ct->name.' ('.$ct->saleprice.' บาท)');
        }

        $giveawayFrees = CarPreemptionGiveaway::where('free', true)
            ->where('carpreemptionid',$id)
            ->get(['id','giveawayid','price']);
        $giveawayFreeDatas = array();
        foreach($giveawayFrees as $data){
            $obj = (object)array("id" => $data->id, "giveawayid" => $data->giveawayid, "price" => $data->price);
            array_push($giveawayFreeDatas,$obj);
        }

        $giveawayBuys = CarPreemptionGiveaway::where('free', false)
            ->where('carpreemptionid',$id)
            ->get(['id','giveawayid']);
        $giveawayBuyDatas = array();
        foreach($giveawayBuys as $data){
            $obj = (object)array("id" => $data->id, "giveawayid" => $data->giveawayid);
            array_push($giveawayBuyDatas,$obj);
        }

        $finacecompanyselectlist = array();
        array_push($finacecompanyselectlist, ':เลือกบริษัท');
        if($model->purchasetype == 1) {
            $item = FinaceCompany::find($model->finacecompanyid);
            $finacecompanyselectlist[$item->id] = $item->name;
        }

        $interestratetypeselectlist = array();
        array_push($interestratetypeselectlist, ':เลือกประเภทอัตราดอกเบี้ย');
        if ($model->purchasetype == 1) {
            $item = InterestRateType::find($model->interestratetypeid);
            $interestratetypeselectlist[$item->id] = $item->name;
        }

        $priceselectlist = array();
        $item = Pricelist::find($model->pricelistid);
        if($item->promotion != null && $item->promotion != '')
            $priceselectlist[$item->id] = $item->sellingpricewithaccessories.' ('.$item->promotion.')';
        else
            $priceselectlist[$item->id] = $item->sellingpricewithaccessories;

        $model->date = date('d-m-Y', strtotime($model->date));
        $model->datewantgetcar = date('d-m-Y', strtotime($model->datewantgetcar));
        $model->approvaldate = date('d-m-Y', strtotime($model->approvaldate));
        if($model->contractdate != null && $model->contractdate != '')
            $model->contractdate = date('d-m-Y', strtotime($model->contractdate));

        return view('carpreemptionform',
            ['oper' => 'view','pathPrefix' => '../../','carpreemption' => $model,
                'carprices' => array(),
                'colorprices' => array(),
                'provinceregistrationfee' => array(),
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
                'approveremployeeselectlist' => $approveremployeeselectlist,
                'finacecompanyselectlist' => $finacecompanyselectlist,
                'interestratetypeselectlist' => $interestratetypeselectlist,
                'priceselectlist' => $priceselectlist,
                'registerprovinceselectlist' => $registerprovinceselectlist]);
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

                'carobjectivetype' => 'required',
                'carmodelid' => 'required',
                'carsubmodelid' => 'required',
                'colorid' => 'required',
                'pricelistid' => 'required',
                'discount' => 'required',
                'subdown' => 'required',
                'accessories' => 'required',

                'cashpledge' => 'required',
            'cashpledgepaymenttype' => 'required',
            'cashpledgechargepercent' => 'required_if:cashpledgepaymenttype,1',
                'purchasetype' => 'required',
                'finacecompanyid' => 'required_if:purchasetype,1',
            'interestratetypeid' => 'required_if:purchasetype,1',
            'interestratemode' => 'required_if:purchasetype,1',
                'interest' => 'required_if:purchasetype,1',
                'down' => 'required_if:purchasetype,1',
            'subsidise' => 'required_if:purchasetype,1',
                'installments' => 'required_if:purchasetype,1',
                'cashpledgeredlabel' => 'required_if:carobjectivetype,0',
                'registrationtype' => 'required_if:carobjectivetype,0',
                'registrationfee' => 'required_if:carobjectivetype,0',
                'insurancefee' => 'required',
                'compulsorymotorinsurancefee' => 'required',
                'accessoriesfee' => 'required',
            'giveawaywithholdingtax' => 'required',
                'otherfee' => 'required',
            'otherfee2' => 'required',
            'otherfee3' => 'required',
                'implementfee' => 'required',
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

                'carobjectivetype.required' => 'รายละเอียดรถยนตร์ใหม่ รถใหม่/รถบริษัท จำเป็นต้องเลือก',
                'carmodelid.required' => 'รายละเอียดรถยนตร์ใหม่ กรุณาเลือกแบบ',
                'carsubmodelid.required' => 'รายละเอียดรถยนตร์ใหม่ กรุณาเลือกรุ่น',
                'colorid.required' => 'รายละเอียดรถยนตร์ใหม่ กรุณาเลือกสี',
                'pricelistid.required' => 'รายละเอียดรถยนตร์ใหม่ กรุณาเลือกราคา',
                'discount.required' => 'รายละเอียดรถยนตร์ใหม่ ส่วนลด จำเป็นต้องกรอก',
                'subdown.required' => 'รายละเอียดรถยนตร์ใหม่ Sub Down จำเป็นต้องกรอก',
                'accessories.required' => 'รายละเอียดรถยนตร์ใหม่ บวกอุปกรณ์ จำเป็นต้องกรอก',

                'cashpledge.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน เงินมัดจำ จำเป็นต้องกรอก',
                'cashpledgepaymenttype.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ประเภทการจ่ายเงินมัดจำ จำเป็นต้องเลือก',
                'cashpledgechargepercent.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน % ค่าธรรมเนียม จำเป็นต้องเลือก',
                'purchasetype.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ประเภทซื้อรถยนต์ จำเป็นต้องเลือก',
                'finacecompanyid.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ชื่อบริษัทเช่าซื้อ จำเป็นต้องเลือก',
                'interestratetypeid.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ประเภทอัตราดอกเบี้ย จำเป็นต้องเลือก',
                'interestratemode.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน Mode อัตราดอกเบี้ย จำเป็นต้องเลือก',
                'interest.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ดอกเบี้ย จำเป็นต้องกรอก',
                'down.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ดาวน์ จำเป็นต้องกรอก',
                'installments.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน จำนวนงวด จำเป็นต้องกรอก',
                'cashpledgeredlabel.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่ามัดจำป้ายแดง จำเป็นต้องกรอก',
                'registrationtype.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ประเภทจดทะเบียน จำเป็นต้องเลือก',
                'registrationfee.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าจดทะเบียน จำเป็นต้องกรอก',
                'insurancefee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าประกันภัย จำเป็นต้องกรอก',
                'compulsorymotorinsurancefee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่า พ.ร.บ. จำเป็นต้องกรอก',
                'accessoriesfee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าอุปกรณ์ จำเป็นต้องกรอก',
                'giveawaywithholdingtax.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ภาษีหัก ณ ที่จ่าย (กรณีลูกค้าได้รับของแถม เช่น ทอง) จำเป็นต้องกรอก',
                'otherfee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าอื่นๆ (1) จำเป็นต้องกรอก',
                'otherfee2.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าอื่นๆ (2) จำเป็นต้องกรอก',
                'otherfee3.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าอื่นๆ (3) จำเป็นต้องกรอก',
                'subsidise.required_if' => 'รายละเอียด/เงื่อนไขการชำระเงิน SUBSIDISE จำเป็นต้องกรอก',
                'implementfee.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน ค่าดำเนินการ จำเป็นต้องกรอก',
                'datewantgetcar.required' => 'รายละเอียด/เงื่อนไขการชำระเงิน วันที่ต้องการรับรถ จำเป็นต้องกรอก',
                'giveawayadditionalcharges.required' => 'รายละเอียดอื่นๆ ลูกค้าจ่ายเพิ่มเติ่มค่าของแถม จำเป็นต้องกรอก',

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

        if ($request->has('id')){
            $model = CarPreemption::find($input['id']);
            if($model == null)
                return "ขออภัย!! ไม่พบข้อมูลที่จะทำการแก้ไขในระบบ เนื่องจากอาจถูกลบไปแล้ว";
        }
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
            else $customer->provinceid = Auth::user()->provinceid;
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

        $model->carobjectivetype = $input['carobjectivetype'];
        $model->carmodelid = $input['carmodelid'];
        $model->carsubmodelid = $input['carsubmodelid'];
        $model->colorid = $input['colorid'];
        $model->pricelistid = $input['pricelistid'];
        $model->colorprice = $input['colorprice'];
        $model->totalcarprice = $input['totalcarprice'];
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
        $model->cashpledgepaymenttype = $input['cashpledgepaymenttype'];
        $model->cashpledgechargepercent = $input['cashpledgechargepercent'];
        $model->cashpledgechargeamount = $input['cashpledgechargeamount'];
        if ($request->has('cashpledgechargefree')) $model->cashpledgechargefree = $input['cashpledgechargefree'];
        else $model->cashpledgechargefree = 0;
        $model->purchasetype = $input['purchasetype'];
        $model->finacecompanyid = $input['finacecompanyid'];
        $model->interestratetypeid = $input['interestratetypeid'];
        $model->interestratemode = $input['interestratemode'];
        $model->interest = $input['interest'];
        $model->down = $input['down'];
        $model->installments = $input['installments'];
        $model->financingfee = $input['financingfee'];
        $model->transferfee = $input['transferfee'];
        $model->transferoperationfee = $input['transferoperationfee'];
        $model->cashpledgeredlabel = $input['cashpledgeredlabel'];
        $model->registerprovinceid = $input['registerprovinceid'];
        $model->registrationtype = $input['registrationtype'];
        $model->registrationfee = $input['registrationfee'];
        if ($request->has('registrationfeefree')) $model->registrationfeefree = $input['registrationfeefree'];
        else $model->registrationfeefree = 0;
        $model->insurancefee = $input['insurancefee'];
        if ($request->has('insurancefeefree')) $model->insurancefeefree = $input['insurancefeefree'];
        else $model->insurancefeefree = 0;
        $model->compulsorymotorinsurancefee = $input['compulsorymotorinsurancefee'];
        if ($request->has('compulsorymotorinsurancefeefree')) $model->compulsorymotorinsurancefeefree = $input['compulsorymotorinsurancefeefree'];
        else $model->compulsorymotorinsurancefeefree = 0;
        $model->accessoriesfee = $input['accessoriesfee'];
        $model->giveawaywithholdingtax = $input['giveawaywithholdingtax'];
        $model->otherfee = $input['otherfee'];
        $model->otherfeedetail = $input['otherfeedetail'];
        $model->otherfee2 = $input['otherfee2'];
        $model->otherfeedetail2 = $input['otherfeedetail2'];
        $model->otherfee3 = $input['otherfee3'];
        $model->otherfeedetail3 = $input['otherfeedetail3'];
        $model->subsidise = $input['subsidise'];
        if ($request->has('subsidisefree')) $model->subsidisefree = $input['subsidisefree'];
        else $model->subsidisefree = 0;
        $model->implementfee = $input['implementfee'];
        if ($request->has('implementfeefree')) $model->implementfeefree = $input['implementfeefree'];
        else $model->implementfeefree = 0;
        $model->datewantgetcar = date('Y-m-d', strtotime($input['datewantgetcar']));
        $model->giveawayadditionalcharges = $input['giveawayadditionalcharges'];
        $model->totalfree = $input['totalfree'];

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
                else $customer->provinceid = Auth::user()->provinceid;
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
        $model->salesmanageremployeeid = $input['salesmanageremployeeid'];
        $model->approversemployeeid = $input['approversemployeeid'];
        $model->approvaldate = date('Y-m-d', strtotime($input['approvaldate']));

        if ($request->has('place')) $model->place = $input['place']; else $model->place = 0;
        if ($request->has('showroom')) $model->showroom = $input['showroom']; else $model->showroom = 0;
        if ($request->has('booth')) $model->booth = $input['booth']; else $model->booth = 0;
        if ($request->has('leaflet')) $model->leaflet = $input['leaflet']; else $model->leaflet = 0;
        if ($request->has('businesscard')) $model->businesscard = $input['businesscard']; else $model->businesscard = 0;
        if ($request->has('invitationcard')) $model->invitationcard = $input['invitationcard']; else $model->invitationcard = 0;
        if ($request->has('phone')) $model->phone = $input['phone']; else $model->phone = 0;
        if ($request->has('signshowroom')) $model->signshowroom = $input['signshowroom']; else $model->signshowroom = 0;
        if ($request->has('spotradiowalkin')) $model->spotradiowalkin = $input['spotradiowalkin']; else $model->spotradiowalkin = 0;
        if ($request->has('recommendedby')) $model->recommendedby = $input['recommendedby']; else $model->recommendedby = 0;
        $model->recommendedbyname = $input['recommendedbyname'];
        if ($request->has('recommendedbytype')) $model->recommendedbytype = $input['recommendedbytype'];
        if ($request->has('customertype')) $model->customertype = $input['customertype'];
        $model->documentstatus = $input['documentstatus'];
        $model->remark = $input['remark'];

        if($model->oldcarbrandid == '') $model->oldcarbrandid = null;
        if($model->oldcarmodelid == '') $model->oldcarmodelid = null;
        if($model->oldcargear == '') $model->oldcargear = null;
        if($model->oldcarcolor == '') $model->oldcarcolor = null;
        if($model->oldcarenginesize == '') $model->oldcarenginesize = null;
        if($model->oldcarlicenseplate == '') $model->oldcarlicenseplate = null;
        if($model->oldcaryear == '') $model->oldcaryear = null;
        if($model->oldcarprice == '') $model->oldcarprice = null;
        if($model->oldcarbuyername == '') $model->oldcarbuyername = null;
        if($model->oldcarother == '') $model->oldcarother = null;

        if ($model->cashpledgepaymenttype == 0) {
            $model->cashpledgechargepercent = null;
            $model->cashpledgechargeamount = null;
        }
        if($model->purchasetype == 0){
            $model->finacecompanyid = null;
            $model->interestratetypeid = null;
            $model->interest = null;
            $model->down = null;
            $model->installments = null;
            $model->subsidise = null;
            $model->interestratemode = null;
        }
        if($model->recommendedby == false){
            $model->recommendedbyname = null;
            $model->recommendedbytype = null;
        }

        if($input['contractdate'] != null && $input['contractdate'] != '')
            $model->contractdate = date('Y-m-d', strtotime($input['contractdate']));
        else
            $model->contractdate = $input['contractdate'];

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
                $obj->price = $data["price"];
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
            $this->validate($request, ['bookno' => 'alpha'], ['bookno.alpha' => 'ไม่สามารถทำการบันทึกข้อมูลการจองได้ กรุณาติดต่อผู้ดูแลระบบ!!']);
        }
    }

    public function getbyid($id)//find for new car payment
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPreemption::find($id);

        $customer = Customer::find($model->buyercustomerid);
        $model->customer = $customer->title.' '.$customer->firstname.' '.$customer->lastname;

        $carmodel = CarModel::find($model->carmodelid);
        $carsubmodel = CarSubModel::find($model->carsubmodelid);
        $model->carmodel = $carmodel->name.'/'.$carsubmodel->name;

        $color = Color::find($model->colorid);
        $model->carcolor = $color->code.' - '.$color->name;

        $pricelist = Pricelist::find($model->pricelistid);
        $model->carprice = $pricelist->sellingpricewithaccessories + $model->colorprice;

        if(Auth::user()->isadmin){
            if($model->carobjectivetype == 0){
                $cars = Car::doesntHave('carPayment')
                    ->where('objective',0)
                    ->where('carmodelid',$model->carmodelid)
                    ->where('carsubmodelid',$model->carsubmodelid)
                    ->where('colorid',$model->colorid)
                    ->orderBy('chassisno', 'asc')
                    ->orderBy('engineno', 'asc')
                    ->get(['id','chassisno','engineno']);
            }
            else{
                $cars = Car::doesntHave('carPayment')
                    ->where('objective','!=',0)
                    ->where('carmodelid',$model->carmodelid)
                    ->where('carsubmodelid',$model->carsubmodelid)
                    ->where('colorid',$model->colorid)
                    ->orderBy('chassisno', 'asc')
                    ->orderBy('engineno', 'asc')
                    ->get(['id','chassisno','engineno']);
            }
        }
        else{
            if($model->carobjectivetype == 0){
                $cars = Car::where('provinceid', Auth::user()->provinceid)
                    ->doesntHave('carPayment')
                    ->where('objective',0)
                    ->where('carmodelid',$model->carmodelid)
                    ->where('carsubmodelid',$model->carsubmodelid)
                    ->where('colorid',$model->colorid)
                    ->orderBy('chassisno', 'asc')
                    ->orderBy('engineno', 'asc')
                    ->get(['id','chassisno','engineno']);
            }
            else{
                $cars = Car::where('provinceid', Auth::user()->provinceid)
                    ->doesntHave('carPayment')
                    ->where('objective','!=',0)
                    ->where('carmodelid',$model->carmodelid)
                    ->where('carsubmodelid',$model->carsubmodelid)
                    ->where('colorid',$model->colorid)
                    ->orderBy('chassisno', 'asc')
                    ->orderBy('engineno', 'asc')
                    ->get(['id','chassisno','engineno']);
            }
        }
        $model->cars = $cars;

        if($model->finacecompanyid != null && $model->finacecompanyid != '') {
            $finacecompany = FinaceCompany::find($model->finacecompanyid);
            $model->finacecompany = $finacecompany->name;
        }

        if ($model->interestratetypeid != null && $model->interestratetypeid != '') {
            $interestratetype = InterestRateType::find($model->interestratetypeid);
            $model->interestratetype = $interestratetype->name;
        }

        if($model->purchasetype == 0) {
            $model->yodjud =  0;
            $model->realprice = $model->carprice - $model->discount;
        }
        else{
            $model->yodjud =  $model->carprice - $model->discount - $model->down + $model->accessories;
            $model->realprice =  $model->carprice - $model->discount - $model->subdown;
        }

        $salesmanemployee = Employee::find($model->salesmanemployeeid);
        $model->salesmanemployee = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;

        $approversemployee = Employee::find($model->approversemployeeid);
        $model->approversemployee = $approversemployee->title.' '.$approversemployee->firstname.' '.$approversemployee->lastname;

        if($model->carobjectivetype == 0) {
            $registerprovince = Province::find($model->registerprovinceid);
            $model->registerprovince = $registerprovince->name;

            $redlabelhistory = Redlabelhistory::where('carpreemptionid',$id)->first();
            if($redlabelhistory != null){
                $redlabel = Redlabel::find($redlabelhistory->redlabelid);
                $model->redlabel = $redlabel->no;
            }
            else{
                $model->redlabel = "ไม่มีป้าย";
            }
        }
        else{
            $model->redlabel = null;
        }

        //if($cust->birthdate != null)
            //$cust->birthdate = date('d-m-Y', strtotime($cust->birthdate));

        return $model;
    }

    public function getbyidforcancelcarpreemption($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPreemption::find($id);

        $customer = Customer::find($model->buyercustomerid);
        $model->customer = $customer->title.' '.$customer->firstname.' '.$customer->lastname;

        $carmodel = CarModel::find($model->carmodelid);
        $carsubmodel = CarSubModel::find($model->carsubmodelid);
        $model->carmodel = $carmodel->name.'/'.$carsubmodel->name;

        $salesmanemployee = Employee::find($model->salesmanemployeeid);
        $model->salesmanemployee = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;

        $model->date = date('d-m-Y', strtotime($model->date));

        return $model;
    }

    public function calculateaccessoriesfee($giveawayids)
    {
        $fee = 0;
        $ids = explode(",",$giveawayids);
        foreach($ids as $id){
            $model = Giveaway::find($id);
            $fee += $model->saleprice;
        }

        return $fee;
    }

    public function getprice($pricelistid)
    {
        $model = Pricelist::find($pricelistid);
        return $model->sellingpricewithaccessories;
    }
}