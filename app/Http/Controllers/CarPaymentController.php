<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Models\AccountingDetail;
use App\Models\Branch;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarPayment;
use App\Models\CarPreemption;
use App\Models\CarSubModel;
use App\Models\CarType;
use App\Models\Color;
use App\Models\CommissionExtra;
use App\Models\CommissionFinace;
use App\Models\CommissionFinaceCom;
use App\Models\CommissionFinaceInterest;
use App\Models\CommissionPA;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request as SupportRequest;

class CarPaymentController extends Controller {

    protected $menuPermissionName = "การชำระเงิน";

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
                        ->orWhere(function ($query) {
                            $query->where('cashpledgeredlabel',0)
                                ->orWhereHas('redlabelhistories', function($q){
                                    $q->whereNull('returndate');
                                });
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
                        ->orWhere(function ($query) {
                            $query->where('cashpledgeredlabel',0)
                                ->orWhereHas('redlabelhistories', function($q){
                                    $q->whereNull('returndate');
                                });
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
                if($carpreemption->carobjectivetype == 0){
                    $cars = Car::doesntHave('carPayment')
                        ->where('objective',0)
                        ->where('carmodelid',$carpreemption->carmodelid)
                        ->where('carsubmodelid',$carpreemption->carsubmodelid)
                        ->where('colorid',$carpreemption->colorid)
                        ->orderBy('chassisno', 'asc')
                        ->orderBy('engineno', 'asc')
                        ->get(['id','chassisno','engineno']);
                }
                else{
                    $cars = Car::doesntHave('carPayment')
                        ->where('objective','!=',0)
                        ->where('carmodelid',$carpreemption->carmodelid)
                        ->where('carsubmodelid',$carpreemption->carsubmodelid)
                        ->where('colorid',$carpreemption->colorid)
                        ->orderBy('chassisno', 'asc')
                        ->orderBy('engineno', 'asc')
                        ->get(['id','chassisno','engineno']);
                }
            }
            else{
                if($carpreemption->carobjectivetype == 0){
                    $cars = Car::where('provinceid', Auth::user()->provinceid)
                        ->doesntHave('carPayment')
                        ->where('objective',0)
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
                        ->where('objective','!=',0)
                        ->where('carmodelid',$carpreemption->carmodelid)
                        ->where('carsubmodelid',$carpreemption->carsubmodelid)
                        ->where('colorid',$carpreemption->colorid)
                        ->orderBy('chassisno', 'asc')
                        ->orderBy('engineno', 'asc')
                        ->get(['id','chassisno','engineno']);
                }
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

        $carpayment = new CarPayment();
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
                'installmentsinadvance' => 'required_if:purchasetype,1',
                'accessoriesfeeactuallypaid' => 'required',
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
                'installmentsinadvance.required_if' => 'จำนวนงวดชำระล่วงหน้า จำเป็นต้องกรอก',
                'accessoriesfeeactuallypaid.required' => 'อุปกรณ์รวมจ่ายจริง จำเป็นต้องกรอก',
                'insurancecompanyid.required_if' => 'เบี้ยประกันชั้น 1,3 กรุณาเลือกบริษัทประกัน',
                'capitalinsurance.required_if' => 'ทุนประกัน จำเป็นต้องกรอก',
                'compulsorymotorinsurancecompanyid.required' => 'เบี้ย พ.ร.บ. กรุณาเลือกบริษัทประกัน',
                'deliverycarbookno.required_with' => 'ใบส่งรถ เล่มที่ จำเป็นต้องกรอก',
                'deliverycarno.required_with' => 'ใบส่งรถ เลขที่ จำเป็นต้องกรอก',
                'deliverycardate.required_with' => 'ใบส่งรถ วันที่ จำเป็นต้องกรอก'
            ]
        );

        $input = $request->all();

        if ($request->has('id')) {
            $model = CarPayment::find($input['id']);
            if($model == null)
                return "ขออภัย!! ไม่พบข้อมูลที่จะทำการแก้ไขในระบบ เนื่องจากอาจถูกลบไปแล้ว";
        }
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

            $model->firstinstallmentpay = 0;
            $model->installmentsinadvance = 0;
        }
        else{
            $model->overrideopenbill = null;

            if ($request->has('firstinstallmentpay')) $model->firstinstallmentpay = $input['firstinstallmentpay']; else $model->firstinstallmentpay = 0;
            $model->installmentsinadvance = $input['installmentsinadvance'];
        }
        $model->accessoriesfeeactuallypaid = $input['accessoriesfeeactuallypaid'];
        $model->accessoriesfeeincludeinyodjud = $input['accessoriesfeeincludeinyodjud'];
        $model->insurancecompanyid = $input['insurancecompanyid'];
        $model->capitalinsurance = $input['capitalinsurance'];
        $model->compulsorymotorinsurancecompanyid = $input['compulsorymotorinsurancecompanyid'];

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

        if(!Auth::user()->isadmin && $model->deliverycarbookno != null && $model->deliverycarbookno != '')
            return "ไม่สามารถแก้ไขข้อมูลการชำระเงินได้ เนื่องจากมีการส่งรถแล้ว!!";

        $carpreemption = CarPreemption::find($model->carpreemptionid);

        $carpreemptionselectlist = array();
        $carpreemptionselectlist[$carpreemption->id] = $carpreemption->bookno.'/'.$carpreemption->no;

        $model->bookno = $carpreemption->bookno;
        $model->no = $carpreemption->no;

        $model->purchasetype = $carpreemption->purchasetype;
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
            $model->yodjud = number_format(0, 2, '.', '');
            $model->yodjudwithinsurancepremium = number_format(0, 2, '.', '');
            if($model->overrideopenbill != null && $model->overrideopenbill != '')
                $model->openbill = $model->overrideopenbill;
            else
                $model->openbill = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->realprice = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->payinadvanceamount = number_format(0, 2, '.', '');
        }
        else {
            $model->down = $carpreemption->down;
            $model->yodjud =  number_format($model->carprice - $carpreemption->discount - $model->down + $carpreemption->accessories + $model->accessoriesfeeincludeinyodjud, 2, '.', '');
            $model->yodjudwithinsurancepremium = number_format($model->yodjud + $model->insurancepremium, 2, '.', '');
            $model->openbill = number_format($model->yodjud + $model->down, 2, '.', '');
            $model->realprice =  number_format($model->carprice - $carpreemption->discount - $carpreemption->subdown, 2, '.', '');
            if($model->firstinstallmentpay)
                $model->firstinstallmentpayamount = number_format($model->amountperinstallment, 2, '.', '');
            else
                $model->firstinstallmentpayamount = number_format(0, 2, '.', '');
            $model->payinadvanceamount = number_format($model->installmentsinadvance * $model->amountperinstallment, 2, '.', '');

            if ($carpreemption->subsidisefree) $model->subsidise = number_format(0, 2, '.', '');
            else $model->subsidise = $carpreemption->subsidise;
        }

        $model->accessoriesfee = $carpreemption->accessoriesfee;

        $insurancecompanies = InsuranceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $insurancecompanyselectlist = array();
        $insurancecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($insurancecompanies as $item){
            $insurancecompanyselectlist[$item->id] = $item->name;
        }

        if ($carpreemption->insurancefeefree) $model->insurancefee = number_format(0, 2, '.', '');
        else $model->insurancefee = $carpreemption->insurancefee;

        if ($carpreemption->compulsorymotorinsurancefeefree) $model->compulsorymotorinsurancefee = number_format(0, 2, '.', '');
        else $model->compulsorymotorinsurancefee = $carpreemption->compulsorymotorinsurancefee;

        if($carpreemption->carobjectivetype == 0) {
            $registerprovince = Province::find($carpreemption->registerprovinceid);
            $model->registerprovince = $registerprovince->name;
        }
        else{
            $model->registerprovince = null;
        }
        $model->registrationtype = $carpreemption->registrationtype;

        if ($carpreemption->registrationfeefree) $model->registrationfee = number_format(0, 2, '.', '');
        else $model->registrationfee = $carpreemption->registrationfee;

        if($carpreemption->carobjectivetype == 0) {
            $redlabelhistory = Redlabelhistory::where('carpreemptionid', $carpreemption->id)->first();
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

        $model->cashpledgeredlabel = $carpreemption->cashpledgeredlabel;

        if ($carpreemption->implementfeefree) $model->implementfee = number_format(0, 2, '.', '');
        else $model->implementfee = $carpreemption->implementfee;

        $model->giveawaywithholdingtax = $carpreemption->giveawaywithholdingtax;

        $model->otherfee = $carpreemption->otherfee;
        $model->otherfeedetail = $carpreemption->otherfeedetail;
        $model->otherfee2 = $carpreemption->otherfee2;
        $model->otherfeedetail2 = $carpreemption->otherfeedetail2;
        $model->otherfee3 = $carpreemption->otherfee3;
        $model->otherfeedetail3 = $carpreemption->otherfeedetail3;

        if ($model->firstinstallmentpay) {
            $model->total = number_format($model->down + $model->amountperinstallment + $model->payinadvanceamount
                + $model->accessoriesfeeactuallypaid + $model->insurancefee + $model->compulsorymotorinsurancefee
                + $model->registrationfee + $model->cashpledgeredlabel + $model->implementfee
                + $model->giveawaywithholdingtax + $model->otherfee + $model->otherfee2 + $model->otherfee3
                , 2, '.', '');
        } else {
            $model->total = number_format($model->down + $model->payinadvanceamount + $model->accessoriesfeeactuallypaid
                + $model->insurancefee + $model->compulsorymotorinsurancefee + $model->registrationfee
                + $model->cashpledgeredlabel + $model->implementfee
                + $model->giveawaywithholdingtax + $model->otherfee + $model->otherfee2 + $model->otherfee3
                , 2, '.', '');
        }
        $model->subdown = $carpreemption->subdown;
        $model->cashpledge = $carpreemption->cashpledge;
        $model->oldcarprice = $carpreemption->oldcarprice;
        $model->totalpayments = number_format($model->total - $model->subdown - $model->cashpledge - $model->oldcarprice, 2, '.', '');

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

        $model->purchasetype = $carpreemption->purchasetype;
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
            $model->yodjud = number_format(0, 2, '.', '');
            $model->yodjudwithinsurancepremium = number_format(0, 2, '.', '');
            if($model->overrideopenbill != null && $model->overrideopenbill != '')
                $model->openbill = $model->overrideopenbill;
            else
                $model->openbill = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->realprice = number_format($model->carprice - $carpreemption->discount, 2, '.', '');
            $model->payinadvanceamount = number_format(0, 2, '.', '');
        }
        else {
            $model->down = $carpreemption->down;
            $model->yodjud =  number_format($model->carprice - $carpreemption->discount - $model->down + $carpreemption->accessories + $model->accessoriesfeeincludeinyodjud, 2, '.', '');
            $model->yodjudwithinsurancepremium = number_format($model->yodjud + $model->insurancepremium, 2, '.', '');
            $model->openbill = number_format($model->yodjud + $model->down, 2, '.', '');
            $model->realprice =  number_format($model->carprice - $carpreemption->discount - $carpreemption->subdown, 2, '.', '');
            if($model->firstinstallmentpay)
                $model->firstinstallmentpayamount = number_format($model->amountperinstallment, 2, '.', '');
            else
                $model->firstinstallmentpayamount = number_format(0, 2, '.', '');
            $model->payinadvanceamount = number_format($model->installmentsinadvance * $model->amountperinstallment, 2, '.', '');

            if ($carpreemption->subsidisefree) $model->subsidise = number_format(0, 2, '.', '');
            else $model->subsidise = $carpreemption->subsidise;
        }

        $model->accessoriesfee = $carpreemption->accessoriesfee;

        $insurancecompanies = InsuranceCompany::orderBy('name', 'asc')->get(['id', 'name']);
        $insurancecompanyselectlist = array();
        $insurancecompanyselectlist[null] = 'เลือกบริษัท';
        foreach($insurancecompanies as $item){
            $insurancecompanyselectlist[$item->id] = $item->name;
        }

        if ($carpreemption->insurancefeefree) $model->insurancefee = number_format(0, 2, '.', '');
        else $model->insurancefee = $carpreemption->insurancefee;

        if ($carpreemption->compulsorymotorinsurancefeefree) $model->compulsorymotorinsurancefee = number_format(0, 2, '.', '');
        else $model->compulsorymotorinsurancefee = $carpreemption->compulsorymotorinsurancefee;

        if($carpreemption->carobjectivetype == 0) {
            $registerprovince = Province::find($carpreemption->registerprovinceid);
            $model->registerprovince = $registerprovince->name;
        }
        else{
            $model->registerprovince = null;
        }
        $model->registrationtype = $carpreemption->registrationtype;

        if ($carpreemption->registrationfeefree) $model->registrationfee = number_format(0, 2, '.', '');
        else $model->registrationfee = $carpreemption->registrationfee;

        if($carpreemption->carobjectivetype == 0) {
            $redlabelhistory = Redlabelhistory::where('carpreemptionid', $carpreemption->id)->first();
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

        $model->cashpledgeredlabel = $carpreemption->cashpledgeredlabel;

        if ($carpreemption->implementfeefree) $model->implementfee = number_format(0, 2, '.', '');
        else $model->implementfee = $carpreemption->implementfee;

        $model->giveawaywithholdingtax = $carpreemption->giveawaywithholdingtax;

        $model->otherfee = $carpreemption->otherfee;
        $model->otherfeedetail = $carpreemption->otherfeedetail;
        $model->otherfee2 = $carpreemption->otherfee2;
        $model->otherfeedetail2 = $carpreemption->otherfeedetail2;
        $model->otherfee3 = $carpreemption->otherfee3;
        $model->otherfeedetail3 = $carpreemption->otherfeedetail3;

        if ($model->firstinstallmentpay) {
            $model->total = number_format($model->down + $model->amountperinstallment + $model->payinadvanceamount
                + $model->accessoriesfeeactuallypaid + $model->insurancefee + $model->compulsorymotorinsurancefee
                + $model->registrationfee + $model->cashpledgeredlabel + $model->implementfee
                + $model->giveawaywithholdingtax + $model->otherfee + $model->otherfee2 + $model->otherfee3
                , 2, '.', '');
        } else {
            $model->total = number_format($model->down + $model->payinadvanceamount + $model->accessoriesfeeactuallypaid
                + $model->insurancefee + $model->compulsorymotorinsurancefee + $model->registrationfee
                + $model->cashpledgeredlabel + $model->implementfee
                + $model->giveawaywithholdingtax + $model->otherfee + $model->otherfee2 + $model->otherfee3
                , 2, '.', '');
        }
        $model->subdown = $carpreemption->subdown;
        $model->cashpledge = $carpreemption->cashpledge;
        $model->oldcarprice = $carpreemption->oldcarprice;
        $model->totalpayments = number_format($model->total - $model->subdown - $model->cashpledge - $model->oldcarprice, 2, '.', '');

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

    public function getforaccountingdetailbyid($id, $donumberformat)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $accountingdetail = new AccountingDetail();
        $accountingdetail->carpaymentid = $id;
        $carpayment = CarPayment::find($id);
        $branch = Branch::find($carpayment->branchid);
        $accountingdetail->branchname = $branch->name;

        $carpreemption = CarPreemption::find($carpayment->carpreemptionid);
        $customer = Customer::find($carpreemption->buyercustomerid);
        $accountingdetail->customername = $customer->title . $customer->firstname . ' ' . $customer->lastname;

        if ($carpayment->deliverycardate != null && $carpayment->deliverycardate != '') {
            $accountingdetail->date = date('d-m-Y', strtotime($carpayment->deliverycardate));
            $accountingdetail->deliverycardate = date('d-m-Y', strtotime($carpayment->deliverycardate));
        } else {
            $accountingdetail->date = null;
            $accountingdetail->deliverycardate = "-";
        }

        $pricelist = Pricelist::find($carpreemption->pricelistid);
        $accountingdetail->carpriceinpricelist = $pricelist->sellingpricewithaccessories;
        $accountingdetail->colorprice = $carpreemption->colorprice;
        $carwithcolorprice = $pricelist->sellingpricewithaccessories + $carpreemption->colorprice;
        $accountingdetail->carwithcolorprice = $carwithcolorprice;

        $accountingdetail->purchasetype = $carpreemption->purchasetype;
        if ($carpreemption->purchasetype == 0) {
            $openbill = $accountingdetail->carwithcolorprice - $carpreemption->discount;
        } else {
            $openbill = $accountingdetail->carwithcolorprice - $carpreemption->discount + $carpreemption->accessories + $carpayment->accessoriesfeeincludeinyodjud;
        }
        $accountingdetail->openbill = $openbill;

        $accountingdetail->accessoriesfeeincludeinyodjud = $carpayment->accessoriesfeeincludeinyodjud;
        $accountingdetail->fakeaccessories = $carpreemption->accessories;
        $accountingdetail->discount = $carpreemption->discount;
        $accountingdetail->subdown = $carpreemption->subdown;
        $realsalesprice = $carwithcolorprice + $carpayment->accessoriesfeeincludeinyodjud - $carpreemption->discount - $carpreemption->subdown;
        $accountingdetail->realsalesprice = $realsalesprice;
        $accountingdetail->accessoriesfeeactuallypaid = $carpayment->accessoriesfeeactuallypaid;

        $registrationfee = $carpreemption->registrationfeefree ? 0 : $carpreemption->registrationfee;
        $accountingdetail->registrationfee = $registrationfee;

        $accountingdetail->actualinsurancefee = $carpreemption->insurancefee;

        if ($carpreemption->purchasetype == 0) {
            $compulsorymotorinsurancefeecash = $carpreemption->compulsorymotorinsurancefeefree ? 0 : $carpreemption->compulsorymotorinsurancefee;
            $conditioncompulsorymotorinsurancefeecustomerpaid = $compulsorymotorinsurancefeecash;
            $accountingdetail->compulsorymotorinsurancefeecash = $compulsorymotorinsurancefeecash;

            $insurancefeecash = $carpreemption->insurancefeefree ? 0 : $carpreemption->insurancefee;
            $accountingdetail->insurancefeecash = $insurancefeecash;

            $accountingdetail->compulsorymotorinsurancefeefn = 0;
            $compulsorymotorinsurancefeefn = 0;
            $accountingdetail->insurancefeefn = 0;
            $insurancefeefn = 0;
            $accountingdetail->firstinstallmentpayamount = 0;
            $firstinstallmentpayamount = 0;
            $accountingdetail->installmentsinadvance = 0;
            $accountingdetail->amountperinstallment = 0;
            $accountingdetail->payinadvanceamount = 0;
            $payinadvanceamount = 0;
            $accountingdetail->insurancepremium = 0;
            $accountingdetail->totalinadvancefees = 0;
            $totalinadvancefees = 0;

            $accountingdetail->conditioninsurancefee = $carpreemption->insurancefee;
            $accountingdetail->conditioninsurancefeecustomerpaid = $insurancefeecash;
            $conditioninsurancefeecompanypaid = $carpreemption->insurancefee - $insurancefeecash;
            $accountingdetail->conditioninsurancefeecompanypaid = $conditioninsurancefeecompanypaid;
            $accountingdetail->conditioncompulsorymotorinsurancefeecustomerpaid = $compulsorymotorinsurancefeecash;
        } else {
            $compulsorymotorinsurancefeecash = 0;
            $insurancefeecash = 0;
            $compulsorymotorinsurancefeefn = $carpreemption->compulsorymotorinsurancefeefree ? 0 : $carpreemption->compulsorymotorinsurancefee;
            $conditioncompulsorymotorinsurancefeecustomerpaid = $compulsorymotorinsurancefeefn;
            $accountingdetail->compulsorymotorinsurancefeefn = $compulsorymotorinsurancefeefn;

            $insurancefeefn = $carpreemption->insurancefeefree ? 0 : $carpreemption->insurancefee;
            $accountingdetail->insurancefeefn = $insurancefeefn;

            $firstinstallmentpayamount = $carpayment->firstinstallmentpay ? $carpayment->amountperinstallment : 0;
            $accountingdetail->firstinstallmentpayamount = $firstinstallmentpayamount;

            $accountingdetail->installmentsinadvance = $carpayment->installmentsinadvance;
            $accountingdetail->amountperinstallment = $carpayment->amountperinstallment;
            $payinadvanceamount = $carpayment->installmentsinadvance * $carpayment->amountperinstallment;
            $accountingdetail->payinadvanceamount = $payinadvanceamount;
            $accountingdetail->insurancepremium = $carpayment->insurancepremium;
            $totalinadvancefees = $insurancefeefn + $compulsorymotorinsurancefeefn + $firstinstallmentpayamount
                + $payinadvanceamount + $carpayment->insurancepremium;
            $accountingdetail->totalinadvancefees = $totalinadvancefees;

            $accountingdetail->compulsorymotorinsurancefeecash = 0;
            $accountingdetail->insurancefeecash = 0;

            $accountingdetail->conditioninsurancefee = $carpreemption->insurancefee;
            $accountingdetail->conditioninsurancefeecustomerpaid = $insurancefeefn;
            $conditioninsurancefeecompanypaid = $carpreemption->insurancefee - $insurancefeefn;
            $accountingdetail->conditioninsurancefeecompanypaid = $conditioninsurancefeecompanypaid;
            $accountingdetail->conditioncompulsorymotorinsurancefeecustomerpaid = $compulsorymotorinsurancefeefn;
        }

        $implementfee = $carpreemption->implementfeefree ? 0 : $carpreemption->implementfee;
        $accountingdetail->implementfee = $implementfee;

        $subsidise = $carpreemption->subsidisefree ? 0 : $carpreemption->subsidise;
        $accountingdetail->subsidise = $subsidise;

        $accountingdetail->giveawaywithholdingtax = $carpreemption->giveawaywithholdingtax;

        $accountingdetail->otherfee = $carpreemption->otherfee;
        $accountingdetail->otherfeedetail = $carpreemption->otherfeedetail;
        $accountingdetail->otherfee2 = $carpreemption->otherfee2;
        $accountingdetail->otherfeedetail2 = $carpreemption->otherfeedetail2;
        $accountingdetail->otherfee3 = $carpreemption->otherfee3;
        $accountingdetail->otherfeedetail3 = $carpreemption->otherfeedetail3;

        $totalotherfee = $subsidise + $carpreemption->giveawaywithholdingtax
            + $carpreemption->otherfee + $carpreemption->otherfee2 + $carpreemption->otherfee3;
        $accountingdetail->totalotherfee = $totalotherfee;

        $totalotherfees = $carpayment->accessoriesfeeactuallypaid + $registrationfee + $compulsorymotorinsurancefeecash
            + $insurancefeecash + $implementfee + $totalotherfee;
        $accountingdetail->totalotherfees = $totalotherfees;

        $carmodel = CarModel::find($carpreemption->carmodelid);
        $carsubmodel = CarSubModel::find($carpreemption->carsubmodelid);
        $accountingdetail->submodel = $carmodel->name . '/' . $carsubmodel->name;

        $car = Car::find($carpayment->carid);
        $accountingdetail->carno = $car->no;
        $accountingdetail->engineno = $car->engineno;
        $accountingdetail->chassisno = $car->chassisno;

        $color = Color::find($car->colorid);
        $accountingdetail->color = $color->code;

        $accountingdetail->purchasetypetext = $carpreemption->purchasetype == 0 ? "C" : "F";

        $accountingdetail->down = $carpreemption->down;

        $insurancecompany = InsuranceCompany::find($carpayment->insurancecompanyid);
        if ($insurancecompany != null) $accountingdetail->insurancecompany = $insurancecompany->name;

        $accountingdetail->capitalinsurance = $carpayment->capitalinsurance;

        $compulsorymotorinsurancecompany = InsuranceCompany::find($carpayment->compulsorymotorinsurancecompanyid);
        if ($compulsorymotorinsurancecompany != null) $accountingdetail->compulsorymotorinsurancecompany = $compulsorymotorinsurancecompany->name;

        $cartype = CarType::find($carmodel->cartypeid);
        $accountingdetail->conditioncompulsorymotorinsurancefee = $cartype->actpaidincludevat;
        $accountingdetail->hascompulsorymotorinsurancefee = $cartype->actpaidincludevat == 0 ? 0 : 1;
        $conditioncompulsorymotorinsurancefeecompanypaid = $cartype->actpaidincludevat - $conditioncompulsorymotorinsurancefeecustomerpaid;
        $accountingdetail->conditioncompulsorymotorinsurancefeecompanypaid = $conditioncompulsorymotorinsurancefeecompanypaid;

        $note1insurancefee = ($carpreemption->insurancefee * 100) / 107.00;
        $accountingdetail->note1insurancefee = $note1insurancefee;
        $accountingdetail->note1insurancefeeincludevat = $carpreemption->insurancefee;
        $note1insurancefeevat = $carpreemption->insurancefee - $note1insurancefee;
        $accountingdetail->note1insurancefeevat = $note1insurancefeevat;

        $accountingdetail->note1compulsorymotorinsurancefee = $cartype->actpaid;
        $accountingdetail->note1compulsorymotorinsurancefeevat = ($cartype->actpaidincludevat - $cartype->actpaid);
        $accountingdetail->note1compulsorymotorinsurancefeeincludevat = $cartype->actpaidincludevat;

        $note1totalfee = $note1insurancefee + $cartype->actpaid;
        $accountingdetail->note1totalfee = $note1totalfee;
        $note1totalfeevat = $note1insurancefeevat + ($cartype->actpaidincludevat - $cartype->actpaid);
        $accountingdetail->note1totalfeevat = $note1totalfeevat;
        $note1totalfeeincludevat = $carpreemption->insurancefee + $cartype->actpaidincludevat;
        $accountingdetail->note1totalfeeincludevat = $note1totalfeeincludevat;

        $cashpledgeredlabel = $carpreemption->cashpledgeredlabel;
        $accountingdetail->cashpledgeredlabel = $cashpledgeredlabel;
        if ($cashpledgeredlabel == null || $cashpledgeredlabel == 0)
            $accountingdetail->hascashpledgeredlabel = 0;
        else
            $accountingdetail->hascashpledgeredlabel = 1;

        $redlabelhistory = Redlabelhistory::where('carpreemptionid', $carpreemption->id)->orderBy('id', 'desc')->first();
        if ($redlabelhistory != null) {
            if ($redlabelhistory->returndate != null)
                $accountingdetail->redlabelreturncashpledgedate = date('d-m-Y', strtotime($redlabelhistory->returncashpledgedate));
            else
                $accountingdetail->redlabelreturncashpledgedate = "-";
        } else
            $accountingdetail->redlabelreturncashpledgedate = "ไม่เอาป้าย";

        $cashpledge = $carpreemption->cashpledge;
        $accountingdetail->cashpledge = $cashpledge;

        $totalcashpledge = $cashpledgeredlabel - $cashpledge;
        $accountingdetail->totalcashpledge = $totalcashpledge;
        $totalcash = $realsalesprice + $totalotherfees + $totalinadvancefees + $totalcashpledge;
        $accountingdetail->totalcash = $totalcash;

        $finacecompany = FinaceCompany::find($carpreemption->finacecompanyid);
        if ($finacecompany != null) $accountingdetail->finacecompany = $finacecompany->name;

        $accountingdetail->incasefinace = $carpreemption->purchasetype;
        if ($carpreemption->purchasetype == 1) {
            $accountingdetail->interest = $carpreemption->interest;
            $accountingdetail->installments = $carpreemption->installments;

            $pricelist = Pricelist::find($carpreemption->pricelistid);
            $carprice = $pricelist->sellingpricewithaccessories + $carpreemption->colorprice;
            $yodjud = $carprice - $carpreemption->discount - $carpreemption->down + $carpreemption->accessories + $carpayment->accessoriesfeeincludeinyodjud;
            $yodjudwithinsurancepremium = $yodjud + $carpayment->insurancepremium;
            $accountingdetail->yodjud = $yodjudwithinsurancepremium;
            $yodjudwithinterest = ($yodjudwithinsurancepremium * ($carpreemption->interest + 100)) / 100.00;
            $accountingdetail->yodjudwithinterest = $yodjudwithinterest;
            $finaceprofit = $yodjudwithinterest - $yodjudwithinsurancepremium;

            $commissionfinace = CommissionFinace::where('finacecompanyid', $carpreemption->finacecompanyid)
                ->where('interestratetypeid', $carpreemption->interestratetypeid)->where('finaceminimumprofit', '<=', $finaceprofit)
                ->where('effectivefrom', '<=', $carpreemption->date)->where('effectiveto', '>=', $carpreemption->date)
                ->whereHas('commissionFinaceCars', function ($query) use ($carpreemption) {
                    $query->where('carmodelid', $carpreemption->carmodelid)
                        ->Where(function ($query) use ($carpreemption) {
                            $query->where('carsubmodelid', $carpreemption->carsubmodelid)
                                ->orWhere('carsubmodelid', 0);
                        });
                })->first();

            $percentdown = ($carpreemption->down * 100.00) / ($carprice - $carpreemption->discount + $carpreemption->accessories);
            $accountingdetail->comfinpercent = null;
            $comfinpercent = 0;
            $accountingdetail->comfinyear = null;
            $comfinyear = 0;
            if ($commissionfinace != null) {
                $commissionfinaceinterest = CommissionFinaceInterest::where('commissionfinaceid', $commissionfinace->id)
                    ->where('downfrom', '<=', $percentdown)->where('downto', '>=', $percentdown)->first();
                if ($commissionfinaceinterest != null) {
                    $commissionstandardinterest = 0;
                    switch ($carpreemption->installments) {
                        case 24:
                            $commissionstandardinterest = $commissionfinaceinterest->installment24;
                            break;
                        case 36:
                            $commissionstandardinterest = $commissionfinaceinterest->installment36;
                            break;
                        case 48:
                            $commissionstandardinterest = $commissionfinaceinterest->installment48;
                            break;
                        case 60:
                            $commissionstandardinterest = $commissionfinaceinterest->installment60;
                            break;
                        case 72:
                            $commissionstandardinterest = $commissionfinaceinterest->installment72;
                            break;
                        case 84:
                            $commissionstandardinterest = $commissionfinaceinterest->installment84;
                            break;
                    }

                    $commissionfinaceinterests = CommissionFinaceCom::where('commissionfinaceid', $commissionfinace->id)->orderBy('com', 'asc')->get();
                    $previousstepcom = null;

                    foreach ($commissionfinaceinterests as $item) {
                        if ($carpreemption->interestratemode == 0)
                            $currentstepinterest = $commissionstandardinterest + $item->interestcalculationbeginning;
                        else
                            $currentstepinterest = $commissionstandardinterest + $item->interestcalculationending;

                        if ($carpreemption->interest == $currentstepinterest) {
                            $accountingdetail->comfinpercent = $item->com;
                            $accountingdetail->comfinyear = $commissionfinace->years;
                            $comfinpercent = $item->com;
                            $comfinyear = $commissionfinace->years;
                            break;
                        } else if ($carpreemption->interest < $currentstepinterest) {
                            if ($previousstepcom != null) {
                                $accountingdetail->comfinpercent = $previousstepcom;
                                $accountingdetail->comfinyear = $commissionfinace->years;
                                $comfinpercent = $previousstepcom;
                                $comfinyear = $commissionfinace->years;
                            } else {
                                $accountingdetail->comfinpercent = null;
                                $accountingdetail->comfinyear = null;
                                $comfinpercent = 0;
                                $comfinyear = 0;
                            }
                            break;
                        } else if ($carpreemption->interest > $currentstepinterest) {
                            $previousstepcom = $item->com;
                        }
                    }

                    if ($accountingdetail->comfinpercent == null) {
                        if ($previousstepcom != null) {
                            $accountingdetail->comfinpercent = $previousstepcom;
                            $accountingdetail->comfinyear = $commissionfinace->years;
                            $comfinpercent = $previousstepcom;
                            $comfinyear = $commissionfinace->years;
                        }
                    }
                }
            }

            $employee = Employee::find($carpreemption->salesmanemployeeid);
            $accountingdetail->salename = $employee->title . $employee->firstname . ' ' . $employee->lastname;

            $accountingdetail->incasefinaceinsurancefee = $carpreemption->insurancefee;
            $accountingdetail->note2insurancefeewhtax = ($note1insurancefee / 100.00);
            $accountingdetail->note2insurancefee = $insurancefeefn;

            $note2insurancefeeexpense = $conditioninsurancefeecompanypaid > 0 ? $conditioninsurancefeecompanypaid : 0;
            $accountingdetail->note2insurancefeeexpense = $note2insurancefeeexpense;
            $note2insurancefeeincome = $conditioninsurancefeecompanypaid < 0 ? ($conditioninsurancefeecompanypaid * -1) : 0;
            $accountingdetail->note2insurancefeeincome = $note2insurancefeeincome;

            $accountingdetail->incasefinacecompulsorymotorinsurancefee = $cartype->actpaidincludevat;
            $accountingdetail->note2compulsorymotorinsurancefeewhtax = ($cartype->actpaid / 100.00);
            $accountingdetail->note2compulsorymotorinsurancefee = $compulsorymotorinsurancefeefn;

            $note2compulsorymotorinsurancefeeexpense = $conditioncompulsorymotorinsurancefeecompanypaid > 0 ? $conditioncompulsorymotorinsurancefeecompanypaid : 0;
            $accountingdetail->note2compulsorymotorinsurancefeeexpense = $note2compulsorymotorinsurancefeeexpense;
            $note2compulsorymotorinsurancefeeincome = $conditioncompulsorymotorinsurancefeecompanypaid < 0 ? ($conditioncompulsorymotorinsurancefeecompanypaid * -1) : 0;
            $accountingdetail->note2compulsorymotorinsurancefeeincome = $note2compulsorymotorinsurancefeeincome;

            $accountingdetail->incasefinacefirstinstallmentpayamount = $firstinstallmentpayamount;
            $accountingdetail->note2firstinstallmentpayamount = $firstinstallmentpayamount;
            $accountingdetail->incasefinacepayinadvanceamount = $payinadvanceamount;
            $accountingdetail->note2payinadvanceamount = $payinadvanceamount;
            $accountingdetail->incasefinaceinsurancepremium = $carpayment->insurancepremium;
            $accountingdetail->note2insurancepremium = $carpayment->insurancepremium;
            $totalincasefinace = $carpreemption->insurancefee + $cartype->actpaidincludevat + $firstinstallmentpayamount + $payinadvanceamount + $carpayment->insurancepremium;
            $accountingdetail->totalincasefinace = $totalincasefinace;

            $accountingdetail->incasefinacereceivedcash = ($yodjudwithinsurancepremium - $totalincasefinace);
            $note2total1 = $insurancefeefn + $compulsorymotorinsurancefeefn + $firstinstallmentpayamount + $payinadvanceamount + $carpayment->insurancepremium;
            $accountingdetail->note2total1 = $note2total1;
            $note2total2 = $note2insurancefeeexpense + $note2compulsorymotorinsurancefeeexpense;
            $accountingdetail->note2total2 = $note2total2;
            $note2total3 = $note2insurancefeeincome + $note2compulsorymotorinsurancefeeincome;
            $accountingdetail->note2total3 = $note2total3;

            $accountingdetail->incasefinacesubsidise = $carpreemption->subsidise;
            $incasefinacesubsidisewithvat = $carpreemption->subsidise + ($carpreemption->subsidise * 0.07);
            $accountingdetail->incasefinacesubsidisewithvat = $incasefinacesubsidisewithvat;
            $accountingdetail->note2subsidisewhtax = ($carpreemption->subsidise * 0.03);
            $note2subsidisetotal = ($carpreemption->subsidise + ($carpreemption->subsidise * 0.07)) - ($carpreemption->subsidise * 0.03);
            $accountingdetail->note2subsidisetotal = $note2subsidisetotal;

            $incasefinacehassubsidisereceivedcash = ($yodjudwithinsurancepremium - $totalincasefinace) - $incasefinacesubsidisewithvat;
            $accountingdetail->incasefinacehassubsidisereceivedcash = $incasefinacehassubsidisereceivedcash;
            $note2totalwhtax = ($note1insurancefee / 100.00) + ($cartype->actpaid / 100.00) + ($carpreemption->subsidise * 0.03);
            $accountingdetail->note2totalwhtax = $note2totalwhtax;

            //NLTH,AYCAL,KL
            if ($carpreemption->finacecompanyid == 1 || $carpreemption->finacecompanyid == 2 || $carpreemption->finacecompanyid == 4) {
                $incasefinacecomfinamountwithvat = $yodjud * ($carpreemption->interest / 100.00) * $comfinyear * ($comfinpercent / 100.00);
                $incasefinacecomfinamount = $incasefinacecomfinamountwithvat / 1.07;
                $incasefinacecomfinvat = $incasefinacecomfinamountwithvat - $incasefinacecomfinamount;
            } //SCB,T-Bank
            else if ($carpreemption->finacecompanyid == 3 || $carpreemption->finacecompanyid == 5) {
                $incasefinacecomfinamount = floor(($yodjud / 1.07) * ($carpreemption->interest / 100.00) * $comfinyear * ($comfinpercent / 100.00));
                $incasefinacecomfinvat = $incasefinacecomfinamount * 0.07;
                $incasefinacecomfinamountwithvat = $incasefinacecomfinamount + $incasefinacecomfinvat;
            } //KK
            else if ($carpreemption->finacecompanyid == 6) {
                $incasefinacecomfinamount = round(($yodjud / 1.07) * ($carpreemption->interest / 100.00) * $comfinyear * ($comfinpercent / 100.00), 2);
                $incasefinacecomfinvat = $incasefinacecomfinamount * 0.07;
                $incasefinacecomfinamountwithvat = $incasefinacecomfinamount + $incasefinacecomfinvat;
            } //KTB
            else if ($carpreemption->finacecompanyid == 7) {
                $incasefinacecomfinamount = (((($carpayment->amountperinstallment / 1.07) * $carpreemption->installments) - ($yodjud / 1.07)) * ($comfinpercent / 100.00) * 48) / $carpreemption->installments;
                $incasefinacecomfinvat = $incasefinacecomfinamount * 0.07;
                $incasefinacecomfinamountwithvat = $incasefinacecomfinamount + $incasefinacecomfinvat;
            }

            $accountingdetail->incasefinacecomfinamount = $incasefinacecomfinamount;
            $accountingdetail->incasefinacecomfinvat = $incasefinacecomfinvat;
            $accountingdetail->incasefinacecomfinamountwithvat = $incasefinacecomfinamountwithvat;

            $accountingdetail->incasefinacecomfinwhtax = ($incasefinacecomfinamount * 0.03);
            $incasefinacecomfintotal = $incasefinacecomfinamountwithvat - ($incasefinacecomfinamount * 0.03);
            $accountingdetail->incasefinacecomfintotal = $incasefinacecomfintotal;

            $commissionextra = CommissionExtra::where('finacecompanyid', $carpreemption->finacecompanyid)
                ->where('effectivefrom', '<=', $carpreemption->date)->where('effectiveto', '>=', $carpreemption->date)
                ->whereHas('commissionExtraCars', function ($query) use ($carpreemption) {
                    $query->where('carmodelid', $carpreemption->carmodelid)
                        ->Where(function ($query) use ($carpreemption) {
                            $query->where('carsubmodelid', $carpreemption->carsubmodelid)
                                ->orWhere('carsubmodelid', 0);
                        });
                })->first();

            if ($commissionfinace != null) {
                $accountingdetail->incasefinacecomextraamount = $commissionextra->amount;
                $incasefinacecomextraamount = $commissionextra->amount;
                $accountingdetail->incasefinacecomextravat = ($commissionextra->amount * 0.07);
                $incasefinacecomextravat = ($commissionextra->amount * 0.07);
                $incasefinacecomextraamountwithvat = $commissionextra->amount + ($commissionextra->amount * 0.07);
                $accountingdetail->incasefinacecomextraamountwithvat = $incasefinacecomextraamountwithvat;
                $accountingdetail->incasefinacecomextrawhtax = ($commissionextra->amount * 0.03);
                $incasefinacecomextrawhtax = ($commissionextra->amount * 0.03);
                $incasefinacecomextratotal = $incasefinacecomextraamountwithvat - ($commissionextra->amount * 0.03);
                $accountingdetail->incasefinacecomextratotal = $incasefinacecomextratotal;
            } else {
                $accountingdetail->incasefinacecomextraamount = 0;
                $incasefinacecomextraamount = 0;
                $accountingdetail->incasefinacecomextravat = 0;
                $incasefinacecomextravat = 0;
                $accountingdetail->incasefinacecomextraamountwithvat = 0;
                $accountingdetail->incasefinacecomextrawhtax = 0;
                $incasefinacecomextrawhtax = 0;
                $accountingdetail->incasefinacecomextratotal = 0;
                $incasefinacecomextratotal = 0;
            }

            if ($carpayment->insurancepremium > 0) {
                $commissionpa = CommissionPA::where('finacecompanyid', $carpreemption->finacecompanyid)
                    ->where('effectivefrom', '<=', $carpreemption->date)->where('effectiveto', '>=', $carpreemption->date)
                    ->first();

                if ($commissionpa != null) {
                    $accountingdetail->incasefinacecompaamount = $commissionpa->amount;
                    $incasefinacecompaamount = $commissionpa->amount;
                    $accountingdetail->incasefinacecompavat = ($commissionpa->amount * 0.07);
                    $incasefinacecompavat = ($commissionpa->amount * 0.07);
                    $incasefinacecompaamountwithvat = $commissionpa->amount + ($commissionpa->amount * 0.07);
                    $accountingdetail->incasefinacecompaamountwithvat = $incasefinacecompaamountwithvat;
                    $accountingdetail->incasefinacecompawhtax = ($commissionpa->amount * 0.03);
                    $incasefinacecompawhtax = ($commissionpa->amount * 0.03);
                    $incasefinacecompatotal = $incasefinacecompaamountwithvat - ($commissionpa->amount * 0.03);
                    $accountingdetail->incasefinacecompatotal = $incasefinacecompatotal;
                } else {
                    $accountingdetail->incasefinacecompaamount = 0;
                    $incasefinacecompaamount = 0;
                    $accountingdetail->incasefinacecompavat = 0;
                    $incasefinacecompavat = 0;
                    $accountingdetail->incasefinacecompaamountwithvat = 0;
                    $accountingdetail->incasefinacecompawhtax = 0;
                    $incasefinacecompawhtax = 0;
                    $accountingdetail->incasefinacecompatotal = 0;
                    $incasefinacecompatotal = 0;
                }
            } else {
                $accountingdetail->incasefinacecompaamount = 0;
                $incasefinacecompaamount = 0;
                $accountingdetail->incasefinacecompavat = 0;
                $incasefinacecompavat = 0;
                $accountingdetail->incasefinacecompaamountwithvat = 0;
                $accountingdetail->incasefinacecompawhtax = 0;
                $incasefinacecompawhtax = 0;
                $accountingdetail->incasefinacecompatotal = 0;
                $incasefinacecompatotal = 0;
            }

            $incasefinacetotalcomamount = $incasefinacecomfinamount + $incasefinacecomextraamount + $incasefinacecompaamount;
            $accountingdetail->incasefinacetotalcomamount = $incasefinacetotalcomamount;
            $incasefinacetotalcomvat = ($incasefinacecomfinamount * 0.07) + $incasefinacecomextravat + $incasefinacecompavat;
            $accountingdetail->incasefinacetotalcomvat = $incasefinacetotalcomvat;
            $incasefinacetotalcomamountwithvat = $incasefinacetotalcomamount + $incasefinacetotalcomvat;
            $accountingdetail->incasefinacetotalcomamountwithvat = $incasefinacetotalcomamountwithvat;
            $incasefinacetotalcomwhtax = ($incasefinacecomfinamount * 0.03) + $incasefinacecomextrawhtax + $incasefinacecompawhtax;
            $accountingdetail->incasefinacetotalcomwhtax = $incasefinacetotalcomwhtax;
            $incasefinacetotalcomtotal = $incasefinacecomfintotal + $incasefinacecomextratotal + $incasefinacecompatotal;
            $accountingdetail->incasefinacetotalcomtotal = $incasefinacetotalcomtotal;

            $receivedcashfromfinace = round($incasefinacehassubsidisereceivedcash, 2) + round($note2totalwhtax, 2) + round($incasefinacetotalcomtotal, 2);
            $accountingdetail->receivedcashfromfinace = $receivedcashfromfinace;
            $accountingdetail->receivedcashfromfinacenet = $receivedcashfromfinace;

            $accountingdetail->receivedcashfromfinaceshort = $receivedcashfromfinace;
            $accountingdetail->receivedcashfromfinacenetshort = $receivedcashfromfinace;
            $accountingdetail->receivedcashfromfinaceover = 0;
            $accountingdetail->receivedcashfromfinacenetover = 0;
        } else {
            $yodjud = 0;
            $yodjudwithinsurancepremium = 0;
            $accountingdetail->incasefinacereceivedcash = 0;
        }

        $tradereceivableaccount2amount = $totalcash - $yodjudwithinsurancepremium;
        $accountingdetail->tradereceivableaccount2amount = $tradereceivableaccount2amount;;
        $oldcarprice = $carpreemption->oldcarprice;
        $accountingdetail->oldcarprice = $oldcarprice;
        $overdue = $carpayment->overdue;
        $accountingdetail->overdue = $overdue;
        $tradereceivableaccount2remainingamount = $tradereceivableaccount2amount - $oldcarprice - $overdue;
        $accountingdetail->tradereceivableaccount2remainingamount = $tradereceivableaccount2remainingamount;

        $accountingdetail->ins = $accountingdetail->note1insurancefeeincludevat; //$carpreemption->insurancefee;
        $accountingdetail->prb = $accountingdetail->note1compulsorymotorinsurancefeeincludevat; //$carpreemption->compulsorymotorinsurancefee;
        $accountingdetail->dc = $carpreemption->accessories + $carpreemption->subdown;

        $accountingdetail->totalacc2 = $tradereceivableaccount2remainingamount;
        $accountingdetail->totalacc2short = $tradereceivableaccount2remainingamount;
        $accountingdetail->totalacc2over = 0;
        $accountingdetail->totalaccount2 = $tradereceivableaccount2remainingamount;
        $accountingdetail->totalaccount2short = $tradereceivableaccount2remainingamount;
        $accountingdetail->totalaccount2over = 0;


        $arrNotFormatted = array("id", "purchasetype", "carpaymentid", "hasinsurancefee", "hascompulsorymotorinsurancefee", "hascashpledgeredlabel"
        , "systemcalincasefinacecomfinamount", "systemcalincasefinacecomfinvat", "systemcalincasefinacecomfinamountwithvat"
        , "systemcalincasefinacecomfinwhtax", "systemcalincasefinacecomfintotal", "receivedcashfromfinacenet"
        , "receivedcashfromfinacenetshort", "receivedcashfromfinacenetover"
        , "totalaccount1", "totalaccount1short", "totalaccount1over", "totalaccount2", "totalaccount2short", "totalaccount2over"
        , 'totalaccounts', 'totalaccountsshort', 'totalaccountsover'
        , "invoiceno", "additionalopenbill", "deliverycardate"
        , "cashpledgeredlabelreceiptbookno", "cashpledgeredlabelreceiptno", "cashpledgeredlabelreceiptdate", "redlabelreturncashpledgedate"
        , "cashpledgereceiptbookno", "cashpledgereceiptno", "cashpledgereceiptdate"
        , "incasefinacecomfinamount", "incasefinacecomfinvat", "incasefinacecomfinamountwithvat"
        , "incasefinacecomfinwhtax", "incasefinacecomfintotal", "oldcarcomamount", 'oldcarcomdate', "adj"
        , "insurancefeereceiptcondition", "compulsorymotorinsurancefeereceiptcondition"
        , "payinadvanceamountreimbursementdate", "payinadvanceamountreimbursementdocno", 'insurancebilldifferent'
        , "note1insurancefeereceiptcondition", "note1compulsorymotorinsurancefeereceiptcondition"
        , 'insurancefeepayment', 'insurancefeepaidseparatelydate'
        , 'insurancepremiumnet', 'insurancepremiumcom', 'insurancefeepaidseparatelytotal'
        , 'compulsorymotorinsurancefeepayment', 'compulsorymotorinsurancefeepaidseparatelydate'
        , 'compulsorymotorinsurancepremiumnet', 'compulsorymotorinsurancepremiumcom', 'compulsorymotorinsurancefeepaidseparatelytotal'
        , "carno", "installmentsinadvance", "installments", "comfinyear"
        , "createdby", "createddate", "modifiedby", "modifieddate", 'actualinsurancefee'
        );

        if ($donumberformat == 1) {
            foreach ($accountingdetail->toArray() as $key => $value) {
                if (!in_array($key, $arrNotFormatted)) {
                    if (is_numeric($value) && (float)$value != 0.00) {
                        $value = number_format($value, 2, '.', ',');
                    } else if ($value == null || $value == '' || (is_numeric($value) && (float)$value == 0.00))
                        $value = '-';

                    $accountingdetail->$key = $value;
                }
            }
        }

        return $accountingdetail;
    }
}