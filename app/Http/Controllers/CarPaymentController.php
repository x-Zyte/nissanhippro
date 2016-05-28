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
use App\Models\Redlabelhistory;
use App\Models\SystemDatas\Province;
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

        $carpreemptions = CarPreemption::has('carPayment')->orderBy('bookno', 'asc')->orderBy('no', 'asc')
            ->get(['id', 'bookno', 'no','buyercustomerid']);
        $carpreemptionselectlist = array();
        foreach($carpreemptions as $item){
            array_push($carpreemptionselectlist,$item->id.':'.$item->bookno.'/'.$item->no);
            //$buyerCustomer = Customer::find($item->buyercustomerid);
            //array_push($carpreemptionselectlist,$item->id.':'.$item->bookno.'/'.$item->no.'/'.$buyerCustomer->title.' '.$buyerCustomer->firstname.' '.$buyerCustomer->lastname);
        }

        $cars = Car::has('carPayment')->orderBy('chassisno', 'asc')->orderBy('engineno', 'asc')
            ->get(['id', 'chassisno', 'engineno']);
        $carselectlist = array();
        foreach($cars as $item){
            array_push($carselectlist,$item->id.':'.$item->chassisno.'/'.$item->engineno);
            //array_push($carselectlist,$item->id.':'.$item->chassisno.'/'.$item->engineno.'/'.$item->carModel->name.'/'.$item->carSubModel->name.'/'.$item->color->name);
        }

        return view('carpayment',
            ['carpreemptionselectlist' => implode(";",$carpreemptionselectlist),
                'carselectlist' => implode(";",$carselectlist)]);
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
            $carpreemptions = CarPreemption::where('status',0)
                ->where(function ($query) {
                    $query->where('carobjectivetype',1)
                        ->orWhereHas('redlabelhistories', function($q){
                            $q->whereNull('returndate');
                        });
                })
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no']);
        }
        else{
            $carpreemptions = CarPreemption::where('provinceid', Auth::user()->provinceid)
                ->where('status',0)
                ->where(function ($query) {
                    $query->where('carobjectivetype',1)
                        ->orWhereHas('redlabelhistories', function($q){
                            $q->whereNull('returndate');
                        });
                })
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

        $carobjectivetype0 = false;
        $carobjectivetype1 = false;

        $registrationtype0 = false;
        $registrationtype1 = false;
        $registrationtype2 = false;

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

            if($carpreemption->carobjectivetype == 0){
                $carobjectivetype0 = true;
                $carobjectivetype1 = false;
            }
            elseif($carpreemption->carobjectivetype == 1){
                $carobjectivetype0 = false;
                $carobjectivetype1 = true;
            }

            if($carpreemption->registrationtype == 0){
                $registrationtype0 = true;
                $registrationtype1 = false;
                $registrationtype2 = false;
            }
            elseif($carpreemption->registrationtype == 1){
                $registrationtype0 = false;
                $registrationtype1 = true;
                $registrationtype2 = false;
            }
            elseif($carpreemption->registrationtype == 2){
                $registrationtype0 = false;
                $registrationtype1 = false;
                $registrationtype2 = true;
            }

            if(Auth::user()->isadmin){
                $cars = Car::doesntHave('carPayment')
                    ->where('carmodelid',$carpreemption->carmodelid)
                    ->where('carsubmodelid',$carpreemption->carsubmodelid)
                    ->where('colorid',$carpreemption->colorid)
                    ->orderBy('chassisno', 'asc')
                    ->orderBy('engineno', 'asc')
                    ->get(['id','chassisno','engineno']);
            }
            else{
                $cars = Car::where('provinceid', Auth::user()->provinceid)
                    ->doesntHave('carPayment')
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

        $carpayment = new Carpayment;
        $carpayment->date = date('d-m-Y');

        return view('carpaymentform',
            ['oper' => 'new','pathPrefix' => '../','carpayment' => $carpayment,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'carselectlist' => $carselectlist,
                'insurancecompanyselectlist' => $insurancecompanyselectlist,
                'payeeemployeeselectlist' => $payeeemployeeselectlist,
                'purchasetype0' => $purchasetype0,
                'purchasetype1' => $purchasetype1,
                'carobjectivetype0' => $carobjectivetype0,
                'carobjectivetype1' => $carobjectivetype1,
                'registrationtype0' => $registrationtype0,
                'registrationtype1' => $registrationtype1,
                'registrationtype2' => $registrationtype2]);
    }

    public function save(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $this->validate($request, [
                'carpreemptionid' => 'required',
                'date' => 'required',
                'carid' => 'required',
                'amountperinstallment' => 'required_if:purchasetype,1',
                'insurancepremium' => 'required_if:purchasetype,1',
                'openbill' => 'required_if:purchasetype,0',
                'paymentmode' => 'required_if:purchasetype,1',
                'installmentsinadvance' => 'required_if:paymentmode,1',
                'insurancecompanyid' => 'required_if:purchasetype,1',
                'capitalinsurance' => 'required_if:purchasetype,1',
                'compulsorymotorinsurancecompanyid' => 'required',
                'deliverycarbookno' => 'required_with:deliverycarno,deliverycardate',
                'deliverycarno' => 'required_with:deliverycarbookno,deliverycardate',
                'deliverycardate' => 'required_with:deliverycarbookno,deliverycarno'
            ],
            [
                'carpreemptionid.required' => 'กรุณาเลือกการจอง',
                'date.required' => 'วันที่ จำเป็นต้องกรอก',
                'carid.required' => 'กรุณาเลือกรถ',
                'amountperinstallment.required_if' => 'ยอดชำระต่องวด จำเป็นต้องกรอก',
                'insurancepremium.required_if' => 'เบี้ยประกันชีวิต จำเป็นต้องกรอก',
                'openbill.required_if' => 'ราคาเปิดบิล จำเป็นต้องกรอก',
                'paymentmode.required_if' => 'ชำระงวดแรก หรือ ชำระงวดล่วงหน้า จำเป็นต้องเลือก',
                'installmentsinadvance.required_if' => 'จำนวนงวดล่วงหน้า จำเป็นต้องกรอก',
                'insurancecompanyid.required_if' => 'เบี้ยประกันชั้น 1,3 กรุณาเลือกบริษัทประกัน',
                'capitalinsurance.required_if' => 'ทุนประกัน จำเป็นต้องกรอก',
                'compulsorymotorinsurancecompanyid.required' => 'เบี้ย พ.ร.บ. กรุณาเลือกบริษัทประกัน',
                'deliverycarbookno.required_with' => 'ใบส่งรถ เล่มที่ จำเป็นต้องกรอก',
                'deliverycarno.required_with' => 'ใบส่งรถ เลขที่ จำเป็นต้องกรอก',
                'deliverycardate.required_with' => 'ใบส่งรถ วันที่ จำเป็นต้องกรอก'
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

        $carpreemption = CarPreemption::find($model->carpreemptionid);
        $pricelist = Pricelist::find($carpreemption->pricelistid);
        $carprice = $pricelist->sellingpricewithaccessories + $carpreemption->colorprice;


        if($input['purchasetype'] == 0) {
            $openbillinput = $input['openbill'];
            $openbill = $carprice - $carpreemption->discount;
            if($openbillinput != $openbill)
                $model->overrideopenbill = $openbillinput;
            else
                $model->overrideopenbill = null;
        }
        else{
            $model->overrideopenbill = null;
        }


        if($request->has('paymentmode')){
            $model->paymentmode = $input['paymentmode'];
            if($model->paymentmode == 0) $model->installmentsinadvance = 1;
            else $model->installmentsinadvance = $input['installmentsinadvance'];
        }
        $model->insurancecompanyid = $input['insurancecompanyid'];
        $model->capitalinsurance = $input['capitalinsurance'];
        $model->compulsorymotorinsurancecompanyid = $input['compulsorymotorinsurancecompanyid'];
        $model->totalpayments = $input['totalpayments'];

        $model->buyerpay = $input['buyerpay'];
        $model->overdue = $input['overdue'];
        $model->overdueinterest = $input['overdueinterest'];
        $model->totaloverdue = $input['totaloverdue'];
        if($request->has('paybytype')){
            $model->paybytype = $input['paybytype'];
        }
        $model->paybyotherdetails = $input['paybyotherdetails'];
        $model->overdueinstallments = $input['overdueinstallments'];

        if($input['overdueinstallmentdate1'] != null && $input['overdueinstallmentdate1'] != '')
            $model->overdueinstallmentdate1 = date('Y-m-d', strtotime($input['overdueinstallmentdate1']));
        else
            $model->overdueinstallmentdate1 = $input['overdueinstallmentdate1'];
        $model->overdueinstallmentamount1 = $input['overdueinstallmentamount1'];

        if($input['overdueinstallmentdate2'] != null && $input['overdueinstallmentdate2'] != '')
            $model->overdueinstallmentdate2 = date('Y-m-d', strtotime($input['overdueinstallmentdate2']));
        else
            $model->overdueinstallmentdate2 = $input['overdueinstallmentdate2'];
        $model->overdueinstallmentamount2 = $input['overdueinstallmentamount2'];

        if($input['overdueinstallmentdate3'] != null && $input['overdueinstallmentdate3'] != '')
            $model->overdueinstallmentdate3 = date('Y-m-d', strtotime($input['overdueinstallmentdate3']));
        else
            $model->overdueinstallmentdate3 = $input['overdueinstallmentdate3'];
        $model->overdueinstallmentamount3 = $input['overdueinstallmentamount3'];

        if($input['overdueinstallmentdate4'] != null && $input['overdueinstallmentdate4'] != '')
            $model->overdueinstallmentdate4 = date('Y-m-d', strtotime($input['overdueinstallmentdate4']));
        else
            $model->overdueinstallmentdate4 = $input['overdueinstallmentdate4'];
        $model->overdueinstallmentamount4 = $input['overdueinstallmentamount4'];

        if($input['overdueinstallmentdate5'] != null && $input['overdueinstallmentdate5'] != '')
            $model->overdueinstallmentdate5 = date('Y-m-d', strtotime($input['overdueinstallmentdate5']));
        else
            $model->overdueinstallmentdate5 = $input['overdueinstallmentdate5'];
        $model->overdueinstallmentamount5 = $input['overdueinstallmentamount5'];

        if($input['overdueinstallmentdate6'] != null && $input['overdueinstallmentdate6'] != '')
            $model->overdueinstallmentdate6 = date('Y-m-d', strtotime($input['overdueinstallmentdate6']));
        else
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

        $model->deliverycarbookno = $input['deliverycarbookno'];
        $model->deliverycarno = $input['deliverycarno'];
        if($input['deliverycardate'] != null && $input['deliverycardate'] != '')
            $model->deliverycardate = date('Y-m-d', strtotime($input['deliverycardate']));
        else
            $model->deliverycardate = $input['deliverycardate'];

        if ($request->has('isdraft')) $model->isdraft = $input['isdraft']; else $model->isdraft = 0;

        if($model->save()) {

            $error = false;

            if(Input::hasFile('deliverycarfile') && Input::file('deliverycarfile')->isValid()) {
                $error = true;

                $uploaddir = base_path() . '/uploads/images/';

                $car = Car::find($model->carid);

                $extension = Input::file('deliverycarfile')->getClientOriginalExtension();
                $fileName = $car->engineno.'_'.$car->chassisno.'_delivered'.'.'.$extension;
                $upload_success = Input::file('deliverycarfile')->move($uploaddir, $fileName);
                if($upload_success) {
                    $model->deliverycarfilepath = '/uploads/images/' . $fileName;
                    $model->save();
                    $error = false;
                }
            }

            if(!$error)
                return redirect()->action('CarPaymentController@edit',['id' => $model->id]);
            else
                $this->validate($request, ['carpreemptionid' => 'alpha'], ['carpreemptionid.alpha' => 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์ กรุณาติดต่อผู้ดูแลระบบ!!']);
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

        if($carpreemption->carobjectivetype == 0){
            $carobjectivetype0 = true;
            $carobjectivetype1 = false;
        }
        elseif($carpreemption->carobjectivetype == 1){
            $carobjectivetype0 = false;
            $carobjectivetype1 = true;
        }

        if($carpreemption->registrationtype == 0){
            $registrationtype0 = true;
            $registrationtype1 = false;
            $registrationtype2 = false;
        }
        elseif($carpreemption->registrationtype == 1){
            $registrationtype0 = false;
            $registrationtype1 = true;
            $registrationtype2 = false;
        }
        elseif($carpreemption->registrationtype == 2){
            $registrationtype0 = false;
            $registrationtype1 = false;
            $registrationtype2 = true;
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
        $model->carprice = $pricelist->sellingpricewithaccessories + $carpreemption->colorprice;

        $carselectlist = array();
        $carselectlist[null] = 'เลือกรถ';
        if(Auth::user()->isadmin){
            $cars = Car::doesntHave('carPayment')
                ->where('carmodelid',$carpreemption->carmodelid)
                ->where('carsubmodelid',$carpreemption->carsubmodelid)
                ->where('colorid',$carpreemption->colorid)
                ->orWhere(function ($query) use($model){
                    $query->where('id', $model->carid);
                })
                ->orderBy('chassisno', 'asc')
                ->orderBy('engineno', 'asc')
                ->get(['id','chassisno','engineno']);
        }
        else{
            $cars = Car::where('provinceid', Auth::user()->provinceid)
                ->doesntHave('carPayment')
                ->where('carmodelid',$carpreemption->carmodelid)
                ->where('carsubmodelid',$carpreemption->carsubmodelid)
                ->where('colorid',$carpreemption->colorid)
                ->orWhere(function ($query) use($model){
                    $query->where('id', $model->carid);
                })
                ->orderBy('chassisno', 'asc')
                ->orderBy('engineno', 'asc')
                ->get(['id','chassisno','engineno']);
        }
        foreach($cars as $item){
            $carselectlist[$item->id] = $item->chassisno.'/'.$item->engineno;
        }

        $model->installments = $carpreemption->installments;
        $model->interest = $carpreemption->interest;

        if($carpreemption->purchasetype == 1) {
            $finacecompany = FinaceCompany::find($carpreemption->finacecompanyid);
            $model->finacecompany = $finacecompany->name;
        }

        if($carpreemption->purchasetype == 0) {
            $model->down = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->yodjud =  0;
            $model->yodjudwithinsurancepremium = 0;
            if($model->overrideopenbill != null && $model->overrideopenbill != '')
                $model->openbill = $model->overrideopenbill;
            else
                $model->openbill = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->realprice = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->payinadvanceamount = 0;
        }
        else {
            $model->down = $carpreemption->down;
            $model->yodjud =  number_format($model->carprice - $carpreemption->discount - $model->down + $carpreemption->accessories, 2, '.', '');
            $model->yodjudwithinsurancepremium = number_format($model->yodjud + $model->insurancepremium, 2, '.', '');
            $model->openbill = number_format($model->yodjudwithinsurancepremium + $model->down, 2, '.', '');
            $model->realprice =  number_format($model->yodjud + $model->down - $carpreemption->subdown, 2, '.', '');
            $model->payinadvanceamount = number_format($model->installmentsinadvance * $model->amountperinstallment, 2, '.', '');
        }

        $model->accessoriesfee = $carpreemption->accessoriesfee;

        $insurancecompanies = InsuranceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $insurancecompanyselectlist = array();
        $insurancecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($insurancecompanies as $item){
            $insurancecompanyselectlist[$item->id] = $item->name;
        }

        $model->insurancefee = $carpreemption->insurancefee;
        $model->compulsorymotorinsurancefee = $carpreemption->compulsorymotorinsurancefee;

        if($carpreemption->carobjectivetype == 0) {
            $registerprovince = Province::find($carpreemption->registerprovinceid);
            $model->registerprovince = $registerprovince->name;
        }
        else{
            $model->registerprovince = null;
        }
        $model->registrationtype = $carpreemption->registrationtype;
        $model->registrationfee = $carpreemption->registrationfee;

        if($carpreemption->carobjectivetype == 0) {
            $redlabelhistory = Redlabelhistory::where('carpreemptionid', $carpreemption->id)->first();
            $redlabel = Redlabel::find($redlabelhistory->redlabelid);
            $model->redlabel = $redlabel->no;
        }
        else{
            $model->redlabel = null;
        }

        $model->cashpledgeredlabel = $carpreemption->cashpledgeredlabel;
        $model->total = number_format($model->down + $model->payinadvanceamount + $model->accessoriesfee + $model->insurancefee + $model->compulsorymotorinsurancefee + $model->registrationfee + $model->cashpledgeredlabel, 2, '.', '');
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

        $model->date = date('d-m-Y', strtotime($model->date));
        if($model->overdueinstallmentdate1 != null && $model->overdueinstallmentdate1 != '')
            $model->overdueinstallmentdate1 = date('d-m-Y', strtotime($model->overdueinstallmentdate1));
        if($model->overdueinstallmentdate2 != null && $model->overdueinstallmentdate2 != '')
            $model->overdueinstallmentdate2 = date('d-m-Y', strtotime($model->overdueinstallmentdate2));
        if($model->overdueinstallmentdate3 != null && $model->overdueinstallmentdate3 != '')
            $model->overdueinstallmentdate3 = date('d-m-Y', strtotime($model->overdueinstallmentdate3));
        if($model->overdueinstallmentdate4 != null && $model->overdueinstallmentdate4 != '')
            $model->overdueinstallmentdate4 = date('d-m-Y', strtotime($model->overdueinstallmentdate4));
        if($model->overdueinstallmentdate5 != null && $model->overdueinstallmentdate5 != '')
            $model->overdueinstallmentdate5 = date('d-m-Y', strtotime($model->overdueinstallmentdate5));
        if($model->overdueinstallmentdate6 != null && $model->overdueinstallmentdate6 != '')
            $model->overdueinstallmentdate6 = date('d-m-Y', strtotime($model->overdueinstallmentdate6));
        if($model->oldcarpaydate != null && $model->oldcarpaydate != '')
            $model->oldcarpaydate = date('d-m-Y', strtotime($model->oldcarpaydate));
        if($model->deliverycardate != null && $model->deliverycardate != '')
            $model->deliverycardate = date('d-m-Y', strtotime($model->deliverycardate));

        return view('carpaymentform',
            ['oper' => 'edit','pathPrefix' => '../../','carpayment' => $model,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'carselectlist' => $carselectlist,
                'insurancecompanyselectlist' => $insurancecompanyselectlist,
                'payeeemployeeselectlist' => $payeeemployeeselectlist,
                'purchasetype0' => $purchasetype0,
                'purchasetype1' => $purchasetype1,
                'carobjectivetype0' => $carobjectivetype0,
                'carobjectivetype1' => $carobjectivetype1,
                'registrationtype0' => $registrationtype0,
                'registrationtype1' => $registrationtype1,
                'registrationtype2' => $registrationtype2]);
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

        if($carpreemption->carobjectivetype == 0){
            $carobjectivetype0 = true;
            $carobjectivetype1 = false;
        }
        elseif($carpreemption->carobjectivetype == 1){
            $carobjectivetype0 = false;
            $carobjectivetype1 = true;
        }

        if($carpreemption->registrationtype == 0){
            $registrationtype0 = true;
            $registrationtype1 = false;
            $registrationtype2 = false;
        }
        elseif($carpreemption->registrationtype == 1){
            $registrationtype0 = false;
            $registrationtype1 = true;
            $registrationtype2 = false;
        }
        elseif($carpreemption->registrationtype == 2){
            $registrationtype0 = false;
            $registrationtype1 = false;
            $registrationtype2 = true;
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
        $model->carprice = $pricelist->sellingpricewithaccessories + $carpreemption->colorprice;

        $carselectlist = array();
        $item = Car::find($model->carid);
        $carselectlist[$item->id] = $item->chassisno.'/'.$item->engineno;

        $model->installments = $carpreemption->installments;
        $model->interest = $carpreemption->interest;

        if($carpreemption->purchasetype == 1){
            $finacecompany = FinaceCompany::find($carpreemption->finacecompanyid);
            $model->finacecompany = $finacecompany->name;
        }

        if($carpreemption->purchasetype == 0) {
            $model->down = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->yodjud =  0;
            $model->yodjudwithinsurancepremium = 0;
            if($model->overrideopenbill != null && $model->overrideopenbill != '')
                $model->openbill = $model->overrideopenbill;
            else
                $model->openbill = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->realprice = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->payinadvanceamount = 0;
        }
        else {
            $model->down = $carpreemption->down;
            $model->yodjud =  number_format($model->carprice - $carpreemption->discount - $model->down + $carpreemption->accessories, 2, '.', '');
            $model->yodjudwithinsurancepremium = number_format($model->yodjud + $model->insurancepremium, 2, '.', '');
            $model->openbill = number_format($model->yodjudwithinsurancepremium + $model->down, 2, '.', '');
            $model->realprice =  number_format($model->yodjud + $model->down - $carpreemption->subdown, 2, '.', '');
            $model->payinadvanceamount = number_format($model->installmentsinadvance * $model->amountperinstallment, 2, '.', '');
        }

        $model->accessoriesfee = $carpreemption->accessoriesfee;

        $insurancecompanies = InsuranceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $insurancecompanyselectlist = array();
        $insurancecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($insurancecompanies as $item){
            $insurancecompanyselectlist[$item->id] = $item->name;
        }

        $model->insurancefee = $carpreemption->insurancefee;
        $model->compulsorymotorinsurancefee = $carpreemption->compulsorymotorinsurancefee;

        if($carpreemption->carobjectivetype == 0) {
            $registerprovince = Province::find($carpreemption->registerprovinceid);
            $model->registerprovince = $registerprovince->name;
        }
        else{
            $model->registerprovince = null;
        }
        $model->registrationtype = $carpreemption->registrationtype;
        $model->registrationfee = $carpreemption->registrationfee;

        if($carpreemption->carobjectivetype == 0) {
            $redlabelhistory = Redlabelhistory::where('carpreemptionid', $carpreemption->id)->first();
            $redlabel = Redlabel::find($redlabelhistory->redlabelid);
            $model->redlabel = $redlabel->no;
        }
        else{
            $model->redlabel = null;
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

        $payeeemployeeselectlist = array();
        if($model->payeeemployeeid != null && $model->payeeemployeeid != '') {
            $item = Employee::find($model->payeeemployeeid);
            $payeeemployeeselectlist[$item->id] = $item->title . ' ' . $item->firstname . ' ' . $item->lastname;
        }
        else{
            $payeeemployeeselectlist[null] = '';
        }

        $model->date = date('d-m-Y', strtotime($model->date));
        if($model->overdueinstallmentdate1 != null && $model->overdueinstallmentdate1 != '')
            $model->overdueinstallmentdate1 = date('d-m-Y', strtotime($model->overdueinstallmentdate1));
        if($model->overdueinstallmentdate2 != null && $model->overdueinstallmentdate2 != '')
            $model->overdueinstallmentdate2 = date('d-m-Y', strtotime($model->overdueinstallmentdate2));
        if($model->overdueinstallmentdate3 != null && $model->overdueinstallmentdate3 != '')
            $model->overdueinstallmentdate3 = date('d-m-Y', strtotime($model->overdueinstallmentdate3));
        if($model->overdueinstallmentdate4 != null && $model->overdueinstallmentdate4 != '')
            $model->overdueinstallmentdate4 = date('d-m-Y', strtotime($model->overdueinstallmentdate4));
        if($model->overdueinstallmentdate5 != null && $model->overdueinstallmentdate5 != '')
            $model->overdueinstallmentdate5 = date('d-m-Y', strtotime($model->overdueinstallmentdate5));
        if($model->overdueinstallmentdate6 != null && $model->overdueinstallmentdate6 != '')
            $model->overdueinstallmentdate6 = date('d-m-Y', strtotime($model->overdueinstallmentdate6));
        if($model->oldcarpaydate != null && $model->oldcarpaydate != '')
            $model->oldcarpaydate = date('d-m-Y', strtotime($model->oldcarpaydate));
        if($model->deliverycardate != null && $model->deliverycardate != '')
            $model->deliverycardate = date('d-m-Y', strtotime($model->deliverycardate));

        return view('carpaymentform',
            ['oper' => 'view','pathPrefix' => '../../','carpayment' => $model,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'carselectlist' => $carselectlist,
                'insurancecompanyselectlist' => $insurancecompanyselectlist,
                'payeeemployeeselectlist' => $payeeemployeeselectlist,
                'purchasetype0' => $purchasetype0,
                'purchasetype1' => $purchasetype1,
                'carobjectivetype0' => $carobjectivetype0,
                'carobjectivetype1' => $carobjectivetype1,
                'registrationtype0' => $registrationtype0,
                'registrationtype1' => $registrationtype1,
                'registrationtype2' => $registrationtype2]);
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