<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarPayment;
use App\Models\CarPreemption;
use App\Models\Color;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Facades\GridEncoder;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\FinaceCompany;
use App\Models\InsuranceCompany;
use App\Models\Pricelist;
use App\Models\RedLabel;
use App\Repositories\CarPaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as SupportRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CarPaymentController extends Controller {

    protected $menuPermissionName = "การขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carpreemptionids = CarPayment::distinct()->lists('carpreemptionid');
        $carpreemptions = CarPreemption::whereIn('id', $carpreemptionids)->orderBy('bookno', 'asc')->orderBy('no', 'asc')
            ->get(['id', 'bookno', 'no','buyercustomerid']);
        $carpreemptionselectlist = array();
        foreach($carpreemptions as $item){
            array_push($carpreemptionselectlist,$item->id.':'.$item->bookno.'/'.$item->no);
            //$buyerCustomer = Customer::find($item->buyercustomerid);
            //array_push($carpreemptionselectlist,$item->id.':'.$item->bookno.'/'.$item->no.'/'.$buyerCustomer->title.' '.$buyerCustomer->firstname.' '.$buyerCustomer->lastname);
        }

        $carids = CarPayment::distinct()->lists('carid');
        $cars = Car::whereIn('id', $carids)->orderBy('chassisno', 'asc')->orderBy('engineno', 'asc')
            ->get(['id', 'chassisno', 'engineno']);
        $carselectlist = array();
        foreach($cars as $item){
            array_push($carselectlist,$item->id.':'.$item->chassisno.'/'.$item->engineno);
            //array_push($carselectlist,$item->id.':'.$item->chassisno.'/'.$item->engineno.'/'.$item->carModel->name.'/'.$item->carSubModel->name.'/'.$item->color->name);
        }

        $redlabelids = CarPayment::distinct()->lists('redlabelid');
        $redlabels = RedLabel::whereIn('id', $redlabelids)->orderBy('no', 'asc')
            ->get(['id', 'no']);
        $redlabelselectlist = array();
        foreach($redlabels as $item){
            array_push($redlabelselectlist,$item->id.':'.$item->no);
        }

        return view('carpayment',
            ['carpreemptionselectlist' => implode(";",$carpreemptionselectlist),
                'carselectlist' => implode(";",$carselectlist),
                'redlabelselectlist' => implode(";",$redlabelselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarPaymentRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarPaymentRepository(), $request);
    }

    public function newcarpayment()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        if(Auth::user()->isadmin){

            //$carpreemptionhaspaymentids = CarPayment::distinct()->lists('carpreemptionid');

            $carpreemptions = CarPreemption::where('status',0)
                //->whereNotIn('id', $carpreemptionhaspaymentids)
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no']);
        }
        else{
            //$carpreemptionhaspaymentids = CarPayment::where('provinceid', Auth::user()->provinceid)
                //->distinct()->lists('carpreemptionid');

            $carpreemptions = CarPreemption::where('provinceid', Auth::user()->provinceid)
                ->where('status',0)
                //->whereNotIn('id', $carpreemptionhaspaymentids)
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no']);
        }
        $carpreemptionselectlist = array();
        $carpreemptionselectlist[null] = 'เลือกการจอง';
        foreach($carpreemptions as $item){
            $carpreemptionselectlist[$item->id] = $item->bookno.'/'.$item->no;
        }

        $carpreemptionid = SupportRequest::old('carpreemptionid');
        $carselectlist = array();
        $carselectlist[null] = 'เลือกรถ';

        $purchasetype0 = false;
        $purchasetype1 = false;

        $registrationtype0 = false;
        $registrationtype1 = false;

        if($carpreemptionid != null && $carpreemptionid != ''){
            $carpreemption = CarPreemption::find($carpreemptionid);

            if($carpreemption->purchasetype == 0){
                $purchasetype0 = true;
                $purchasetype1 = false;
            }
            elseif($carpreemption->purchasetype == 1){
                $purchasetype0 = false;
                $purchasetype1 = true;
            }

            if($carpreemption->registrationtype == 0){
                $registrationtype0 = true;
                $registrationtype1 = false;
            }
            elseif($carpreemption->registrationtype == 1){
                $registrationtype0 = false;
                $registrationtype1 = true;
            }

            if(Auth::user()->isadmin){
                $carsoldids = CarPayment::distinct()->lists('carid');

                $cars = Car::whereNotIn('id', $carsoldids)
                    ->where('carmodelid',$carpreemption->carmodelid)
                    ->where('carsubmodelid',$carpreemption->carsubmodelid)
                    ->where('colorid',$carpreemption->colorid)
                    ->orderBy('chassisno', 'asc')
                    ->orderBy('engineno', 'asc')
                    ->get(['id','chassisno','engineno']);
            }
            else{
                $carsoldids = CarPayment::where('provinceid', Auth::user()->provinceid)
                    ->distinct()->lists('carid');

                $cars = Car::where('provinceid', Auth::user()->provinceid)
                    ->whereNotIn('id', $carsoldids)
                    ->where('carmodelid',$carpreemption->carmodelid)
                    ->where('carsubmodelid',$carpreemption->carsubmodelid)
                    ->where('colorid',$carpreemption->colorid)
                    ->orderBy('chassisno', 'asc')
                    ->orderBy('engineno', 'asc')
                    ->get(['id','chassisno','engineno']);
            }
            foreach($cars as $item){
                $carselectlist[$item->id] = $item->chassisno.'/'.$item->engineno;
            }
        }

        if(Auth::user()->isadmin){
            $redlabels = RedLabel::whereNull('carid')
                ->orderBy('no', 'asc')
                ->get(['id','no']);
        }
        else{
            $redlabels = RedLabel::where('provinceid', Auth::user()->provinceid)
                ->whereNull('carid')
                ->orderBy('no', 'asc')
                ->get(['id','no']);
        }
        $redlabelselectlist = array();
        $redlabelselectlist[null] = 'เลือกป้ายแดง';
        foreach($redlabels as $item){
            $redlabelselectlist[$item->id] = $item->no;
        }

        $insurancecompanies = InsuranceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $insurancecompanyselectlist = array();
        $insurancecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($insurancecompanies as $item){
            $insurancecompanyselectlist[$item->id] = $item->name;
        }

        if(Auth::user()->isadmin){
            $payeeemployees = Employee::where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $payeeemployees = Employee::where('provinceid', Auth::user()->provinceid)
                ->where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $payeeemployeeselectlist = array();
        $payeeemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($payeeemployees as $item){
            $payeeemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        return view('carpaymentform',
            ['oper' => 'new','pathPrefix' => '../',
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'carselectlist' => $carselectlist,
                'redlabelselectlist' => $redlabelselectlist,
                'insurancecompanyselectlist' => $insurancecompanyselectlist,
                'payeeemployeeselectlist' => $payeeemployeeselectlist,
                'purchasetype0' => $purchasetype0,
                'purchasetype1' => $purchasetype1,
                'registrationtype0' => $registrationtype0,
                'registrationtype1' => $registrationtype1,]);
    }

    public function save(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $this->validate($request, [
                'carpreemptionid' => 'required',
                'date' => 'required',
                'carid' => 'required',
                'amountperinstallment' => 'required',
                'insurancepremium' => 'required',
                'paymentmode' => 'required',
                'installmentsinadvance' => 'required_if:paymentmode,1',
                'insurancecompanyid' => 'required',
                'capitalinsurance' => 'required',
                'compulsorymotorinsurancecompanyid' => 'required',
                'redlabelid' => 'required',
            ],
            [
                'carpreemptionid.required' => 'กรุณาเลือกการจอง',
                'date.required' => 'วันที่ จำเป็นต้องกรอก',
                'carid.required' => 'กรุณาเลือกรถ',
                'amountperinstallment.required' => 'ยอดชำระต่องวด จำเป็นต้องกรอก',
                'insurancepremium.required' => 'เบี้ยประกันชีวิต จำเป็นต้องกรอก',
                'paymentmode.required' => 'ชำระงวดแรก หรือ ชำระงวดล่วงหน้า จำเป็นต้องเลือก',
                'installmentsinadvance.required_if' => 'จำนวนงวดล่วงหน้า จำเป็นต้องกรอก',
                'insurancecompanyid.required' => 'เบี้ยประกันชั้น 1,3 กรุณาเลือกบริษัทประกัน',
                'capitalinsurance.required' => 'ทุนประกัน จำเป็นต้องกรอก',
                'compulsorymotorinsurancecompanyid.required' => 'เบี้ย พ.ร.บ. กรุณาเลือกบริษัทประกัน',
                'redlabelid.required' => 'กรุณาเลือกทะเบียนป้ายแดง',
            ]
        );

        $input = $request->all();

        if ($request->has('id'))
            $model = CarPayment::find($input['id']);
        else
            $model = new CarPayment;

        $model->carpreemptionid = $input['carpreemptionid'];
        $model->date = date('Y-m-d', strtotime($input['date']));
        $model->carid = $input['carid'];
        $model->amountperinstallment = $input['amountperinstallment'];
        $model->insurancepremium = $input['insurancepremium'];
        $model->paymentmode = $input['paymentmode'];
        if($model->paymentmode == 0) $model->installmentsinadvance = 1;
        else $model->installmentsinadvance = $input['installmentsinadvance'];
        $model->insurancecompanyid = $input['insurancecompanyid'];
        $model->capitalinsurance = $input['capitalinsurance'];
        $model->compulsorymotorinsurancecompanyid = $input['compulsorymotorinsurancecompanyid'];
        $model->redlabelid = $input['redlabelid'];
        $model->totalpayments = $input['totalpayments'];

        $model->date2 = $input['date2'];
        $model->buyerpay = $input['buyerpay'];
        $model->overdue = $input['overdue'];
        $model->overdueinterest = $input['overdueinterest'];
        $model->totaloverdue = $input['totaloverdue'];
        if ($request->has('paybyoldcar')) $model->paybyoldcar = $input['paybyoldcar'];
        if ($request->has('paybycash')) $model->paybycash = $input['paybycash'];
        if ($request->has('paybyother')) $model->paybyother = $input['paybyother'];
        $model->paybyotherdetails = $input['paybyotherdetails'];
        $model->overdueinstallments = $input['overdueinstallments'];
        $model->overdueinstallmentdate1 = $input['overdueinstallmentdate1'];
        $model->overdueinstallmentamount1 = $input['overdueinstallmentamount1'];
        $model->overdueinstallmentdate2 = $input['overdueinstallmentdate2'];
        $model->overdueinstallmentamount2 = $input['overdueinstallmentamount2'];
        $model->overdueinstallmentdate3 = $input['overdueinstallmentdate3'];
        $model->overdueinstallmentamount3 = $input['overdueinstallmentamount3'];
        $model->overdueinstallmentdate4 = $input['overdueinstallmentdate4'];
        $model->overdueinstallmentamount4 = $input['overdueinstallmentamount4'];
        $model->overdueinstallmentdate5 = $input['overdueinstallmentdate5'];
        $model->overdueinstallmentamount5 = $input['overdueinstallmentamount5'];
        $model->overdueinstallmentdate6 = $input['overdueinstallmentdate6'];
        $model->overdueinstallmentamount6 = $input['overdueinstallmentamount6'];
        $model->oldcarbuyername = $input['oldcarbuyername'];
        $model->oldcarpayamount = $input['oldcarpayamount'];
        if ($request->has('oldcarpaytype')) $model->oldcarpaytype = $input['oldcarpaytype'];

        if($input['oldcarpaydate'] != null && $input['oldcarpaydate'] != '')
            $model->oldcarpaydate = date('Y-m-d', strtotime($input['oldcarpaydate']));
        else
            $model->oldcarpaydate = $input['oldcarpaydate'];

        $model->payeeemployeeid = $input['payeeemployeeid'];

        if($model->save()) {
            return redirect()->action('CarPaymentController@edit',['id' => $model->id]);
        }
        else{
            //hack returning error
            $this->validate($request, ['carpreemptionid' => 'alpha'], ['carpreemptionid.alpha' => 'ไม่สามารถทำการบันทึกข้อมูลการชำระเงินได้ กรุณาติดต่อผู้ดูแลระบบ!!']);
        }
    }

    public function edit($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPayment::find($id);

        $carpreemption = CarPreemption::find($model->carpreemptionid);

        $carpreemptionselectlist = array();
        $carpreemptionselectlist[$carpreemption->id] = $carpreemption->bookno.'/'.$carpreemption->no;

        $model->bookno = $carpreemption->bookno;
        $model->no = $carpreemption->no;

        if($carpreemption->purchasetype == 0){
            $purchasetype0 = true;
            $purchasetype1 = false;
        }
        elseif($carpreemption->purchasetype == 1){
            $purchasetype0 = false;
            $purchasetype1 = true;
        }

        if($carpreemption->registrationtype == 0){
            $registrationtype0 = true;
            $registrationtype1 = false;
        }
        elseif($carpreemption->registrationtype == 1){
            $registrationtype0 = false;
            $registrationtype1 = true;
        }

        $customer = Customer::find($carpreemption->buyercustomerid);
        $model->customer = $customer->title.' '.$customer->firstname.' '.$customer->lastname;
        $model->customer2 = $customer->title.' '.$customer->firstname.' '.$customer->lastname;

        $carmodel = CarModel::find($carpreemption->carmodelid);
        $carsubmodel = CarSubModel::find($carpreemption->carsubmodelid);
        $model->carmodel = $carmodel->name.'/'.$carsubmodel->name;

        $color = Color::find($carpreemption->colorid);
        $model->carcolor = $color->code.' - '.$color->name;

        $pricelist = Pricelist::find($carpreemption->pricelistid);
        $model->carprice = $pricelist->sellingpricewithaccessories;

        $carselectlist = array();
        $carselectlist[null] = 'เลือกรถ';
        if(Auth::user()->isadmin){
            $carsoldids = CarPayment::where('id','!=', $id)->distinct()->lists('carid');

            $cars = Car::whereNotIn('id', $carsoldids)
                ->where('carmodelid',$carpreemption->carmodelid)
                ->where('carsubmodelid',$carpreemption->carsubmodelid)
                ->where('colorid',$carpreemption->colorid)
                ->orderBy('chassisno', 'asc')
                ->orderBy('engineno', 'asc')
                ->get(['id','chassisno','engineno']);
        }
        else{
            $carsoldids = CarPayment::where('id','!=', $id)->where('provinceid', Auth::user()->provinceid)
                ->distinct()->lists('carid');

            $cars = Car::where('provinceid', Auth::user()->provinceid)
                ->whereNotIn('id', $carsoldids)
                ->where('carmodelid',$carpreemption->carmodelid)
                ->where('carsubmodelid',$carpreemption->carsubmodelid)
                ->where('colorid',$carpreemption->colorid)
                ->orderBy('chassisno', 'asc')
                ->orderBy('engineno', 'asc')
                ->get(['id','chassisno','engineno']);
        }
        foreach($cars as $item){
            $carselectlist[$item->id] = $item->chassisno.'/'.$item->engineno;
        }

        $model->installments = $carpreemption->installments;
        $model->interest = $carpreemption->interest;

        $finacecompany = FinaceCompany::find($carpreemption->finacecompanyid);
        $model->finacecompany = $finacecompany->name;

        $model->down = $carpreemption->down;
        $model->yodjud =  $model->carprice - $model->down;
        $model->yodjudwithinsurancepremium = $model->yodjud + $model->insurancepremium;
        $model->openbill =  $model->carprice + $carpreemption->accessories - $carpreemption->discount - $carpreemption->subdown;
        $model->payinadvanceamount = $model->installmentsinadvance * $model->amountperinstallment;
        $model->accessoriesfee = $carpreemption->accessoriesfee;

        $insurancecompanies = InsuranceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $insurancecompanyselectlist = array();
        $insurancecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($insurancecompanies as $item){
            $insurancecompanyselectlist[$item->id] = $item->name;
        }

        $model->insurancefee = $carpreemption->insurancefee;
        $model->compulsorymotorinsurancefee = $carpreemption->compulsorymotorinsurancefee;
        $model->registrationtype = $carpreemption->registrationtype;
        $model->registrationfee = $carpreemption->registrationfee;

        if(Auth::user()->isadmin){
            $redlabels = RedLabel::whereNull('carid')->orWhere('carid',$model->carid)
                ->orderBy('no', 'asc')
                ->get(['id','no']);
        }
        else{
            $redlabels = RedLabel::where('provinceid', Auth::user()->provinceid)
                ->where(function ($query) use ($model) {
                    $query->whereNull('carid')
                        ->orWhere('carid',$model->carid);
                })
                ->orderBy('no', 'asc')
                ->get(['id','no']);
        }
        $redlabelselectlist = array();
        $redlabelselectlist[null] = 'เลือกป้ายแดง';
        foreach($redlabels as $item){
            $redlabelselectlist[$item->id] = $item->no;
        }

        $model->cashpledgeredlabel = $carpreemption->cashpledgeredlabel;
        $model->total = $model->down + $model->payinadvanceamount + $model->accessoriesfee + $model->insurancefee + $model->compulsorymotorinsurancefee + $model->registrationfee + $model->cashpledgeredlabel;
        $model->subdown = $carpreemption->subdown;
        $model->cashpledge = $carpreemption->cashpledge;
        $model->oldcarprice = $carpreemption->oldcarprice;

        $salesmanemployee = Employee::find($carpreemption->salesmanemployeeid);
        $model->salesmanemployee = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;

        $approversemployee = Employee::find($carpreemption->approversemployeeid);
        $model->approversemployee = $approversemployee->title.' '.$approversemployee->firstname.' '.$approversemployee->lastname;

        if(Auth::user()->isadmin){
            $payeeemployees = Employee::where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $payeeemployees = Employee::where('provinceid', Auth::user()->provinceid)
                ->where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $payeeemployeeselectlist = array();
        $payeeemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($payeeemployees as $item){
            $payeeemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        return view('carpaymentform',
            ['oper' => 'edit','pathPrefix' => '../../','carpayment' => $model,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'carselectlist' => $carselectlist,
                'insurancecompanyselectlist' => $insurancecompanyselectlist,
                'redlabelselectlist' => $redlabelselectlist,
                'payeeemployeeselectlist' => $payeeemployeeselectlist,
                'purchasetype0' => $purchasetype0,
                'purchasetype1' => $purchasetype1,
                'registrationtype0' => $registrationtype0,
                'registrationtype1' => $registrationtype1,]);
    }

    public function view($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CarPayment::find($id);

        $carpreemption = CarPreemption::find($model->carpreemptionid);

        $carpreemptionselectlist = array();
        $carpreemptionselectlist[$carpreemption->id] = $carpreemption->bookno.'/'.$carpreemption->no;

        $model->bookno = $carpreemption->bookno;
        $model->no = $carpreemption->no;

        if($carpreemption->purchasetype == 0){
            $purchasetype0 = true;
            $purchasetype1 = false;
        }
        elseif($carpreemption->purchasetype == 1){
            $purchasetype0 = false;
            $purchasetype1 = true;
        }

        if($carpreemption->registrationtype == 0){
            $registrationtype0 = true;
            $registrationtype1 = false;
        }
        elseif($carpreemption->registrationtype == 1){
            $registrationtype0 = false;
            $registrationtype1 = true;
        }

        $customer = Customer::find($carpreemption->buyercustomerid);
        $model->customer = $customer->title.' '.$customer->firstname.' '.$customer->lastname;
        $model->customer2 = $customer->title.' '.$customer->firstname.' '.$customer->lastname;

        $carmodel = CarModel::find($carpreemption->carmodelid);
        $carsubmodel = CarSubModel::find($carpreemption->carsubmodelid);
        $model->carmodel = $carmodel->name.'/'.$carsubmodel->name;

        $color = Color::find($carpreemption->colorid);
        $model->carcolor = $color->code.' - '.$color->name;

        $pricelist = Pricelist::find($carpreemption->pricelistid);
        $model->carprice = $pricelist->sellingpricewithaccessories;

        $carselectlist = array();
        $item = Car::find($model->carid);
        $carselectlist[$item->id] = $item->chassisno.'/'.$item->engineno;

        $model->installments = $carpreemption->installments;
        $model->interest = $carpreemption->interest;

        $finacecompany = FinaceCompany::find($carpreemption->finacecompanyid);
        $model->finacecompany = $finacecompany->name;

        $model->down = $carpreemption->down;
        $model->yodjud =  $model->carprice - $model->down;
        $model->yodjudwithinsurancepremium = $model->yodjud + $model->insurancepremium;
        $model->openbill =  $model->carprice + $carpreemption->accessories - $carpreemption->discount - $carpreemption->subdown;
        $model->payinadvanceamount = $model->installmentsinadvance * $model->amountperinstallment;
        $model->accessoriesfee = $carpreemption->accessoriesfee;

        $insurancecompanies = InsuranceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $insurancecompanyselectlist = array();
        $insurancecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($insurancecompanies as $item){
            $insurancecompanyselectlist[$item->id] = $item->name;
        }

        $model->insurancefee = $carpreemption->insurancefee;
        $model->compulsorymotorinsurancefee = $carpreemption->compulsorymotorinsurancefee;
        $model->registrationtype = $carpreemption->registrationtype;
        $model->registrationfee = $carpreemption->registrationfee;

        $redlabelselectlist = array();
        $item = RedLabel::find($model->redlabelid);
        $redlabelselectlist[$item->id] = $item->no;

        $model->cashpledgeredlabel = $carpreemption->cashpledgeredlabel;
        $model->total = $model->down + $model->payinadvanceamount + $model->accessoriesfee + $model->insurancefee + $model->compulsorymotorinsurancefee + $model->registrationfee + $model->cashpledgeredlabel;
        $model->subdown = $carpreemption->subdown;
        $model->cashpledge = $carpreemption->cashpledge;
        $model->oldcarprice = $carpreemption->oldcarprice;

        $salesmanemployee = Employee::find($carpreemption->salesmanemployeeid);
        $model->salesmanemployee = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;

        $approversemployee = Employee::find($carpreemption->approversemployeeid);
        $model->approversemployee = $approversemployee->title.' '.$approversemployee->firstname.' '.$approversemployee->lastname;

        $payeeemployeeselectlist = array();
        if($model->payeeemployeeid != null && $model->payeeemployeeid != '') {
            $item = Employee::find($model->payeeemployeeid);
            $payeeemployeeselectlist[$item->id] = $item->title . ' ' . $item->firstname . ' ' . $item->lastname;
        }
        else{
            $payeeemployeeselectlist[null] = '';
        }

        return view('carpaymentform',
            ['oper' => 'view','pathPrefix' => '../../','carpayment' => $model,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'carselectlist' => $carselectlist,
                'insurancecompanyselectlist' => $insurancecompanyselectlist,
                'redlabelselectlist' => $redlabelselectlist,
                'payeeemployeeselectlist' => $payeeemployeeselectlist,
                'purchasetype0' => $purchasetype0,
                'purchasetype1' => $purchasetype1,
                'registrationtype0' => $registrationtype0,
                'registrationtype1' => $registrationtype1,]);
    }

    public function getbahttext($amount)
    {
        if ((int) $amount == 0) return 'ศูนย์บาท';
        $amount = (string) $amount;
        // find stang portion
        if (($dot = strpos($amount, '.')) > 0)
        {
            $stang = substr($amount, $dot+1);
            $amount = substr($amount, 0, $dot);
        }
        else{
            $stang = '';
        }
        // pad string to multiple of 6
        $amount = str_pad($amount, ceil(strlen($amount) / 6) * 6, ' ', STR_PAD_LEFT);
        $chunks = str_split($amount, 6);

        $text = '';
        while ( ! empty($chunks))
        {
            $segment = array_pop($chunks);
            $text = CarPaymentController::convertSegment($segment) . $text;
            if ( ! empty($chunks))
            {
                $text = 'ล้าน'.$text;
            }
        }

        if($stang == null || $stang == '' || $stang == 0) return $text . 'บาท';
        else return $text . 'บาท' . (CarPaymentController::convertSegment($stang) . 'สตางค์');
    }

    public function convertSegment($segment)
    {
        $numbers = ['ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
        $digits = ['สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
        $one_at_oneth = 'เอ็ด';
        $two_at_tenth = 'ยี่';

        $segment = trim($segment);
        $length = strlen($segment);
        $last_digit = $length -1;
        if ($length == 1) return $numbers[(int)$segment];
        $text = '';
        for ($nth = $last_digit; $nth >= 0; $nth--)
        {
            // any zero in any digit
            if ($segment[$nth] == '0') continue;
            // oneth digit
            if ($nth === $last_digit)
            {
                $digit = '';
                $number = ($segment[$nth] == '1' and $segment[$nth -1] != '0')
                    ? $one_at_oneth
                    : $numbers[(int)$segment[$nth]];
            }
            // tenth digit
            elseif ($nth === $last_digit-1)
            {
                $digit = $digits[$last_digit - $nth -1];
                if ($segment[$nth] === '1')
                {
                    $number = '';
                }
                elseif ($segment[$nth] === '2')
                {
                    $number = $two_at_tenth;
                }
                else
                {
                    $number = $numbers[(int)$segment[$nth]];
                }
            }
            // other digits
            else
            {
                $number  = $numbers[(int)$segment[$nth]];
                $digit = $digits[$last_digit - $nth -1];
            }
            $text = ($number . $digit) . $text;
        }
        return $text;
    }
}