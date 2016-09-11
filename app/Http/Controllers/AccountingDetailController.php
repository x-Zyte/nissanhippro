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
use App\Models\AccountingDetailReceiveAndPay;
use App\Models\Bank;
use App\Models\CarPayment;
use App\Repositories\AccountingDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request as SupportRequest;

class AccountingDetailController extends Controller
{

    protected $menuPermissionName = "รายละเอียดเพื่อการบันทึกบัญชี";

    protected $arrNotFormatted = array("id", "purchasetype", "carpaymentid", "hasinsurancefee", "hascompulsorymotorinsurancefee", "hascashpledgeredlabel"
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
    , "createdby", "createddate", "modifiedby", "modifieddate", "submodel", 'actualinsurancefee'
    );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        if (Auth::user()->isadmin) {
            $carpayments = CarPayment::has('accountingDetail')
                ->with('carpreemption.buyerCustomer')
                ->get();
        } else {
            $carpayments = CarPayment::has('accountingDetail')->where('provinceid', Auth::user()->provinceid)
                ->with('carpreemption.buyerCustomer')
                ->get();
        }
        $customerselectlist = array();
        foreach ($carpayments as $item) {
            array_push($customerselectlist, $item->id . ':' . $item->carpreemption->buyerCustomer->title . $item->carpreemption->buyerCustomer->firstname . ' ' . $item->carpreemption->buyerCustomer->lastname);
        }

        return view('accountingdetail', ['customerselectlist' => implode(";", $customerselectlist)]);
    }

    public function read()
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new AccountingDetailRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new AccountingDetailRepository(), $request);
    }

    public function newaccountingdetail()
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        if (Auth::user()->isadmin) {
            $carpayments = CarPayment::with(['carpreemption' => function ($query) {
                $query->orderBy('bookno', 'asc')->orderBy('no', 'asc');
            }])
                ->doesntHave('accountingDetail')
                ->get();
        } else {
            $carpayments = CarPayment::with(['carpreemption' => function ($query) {
                $query->orderBy('bookno', 'asc')->orderBy('no', 'asc');
            }])
                ->where('provinceid', Auth::user()->provinceid)
                ->doesntHave('accountingDetail')
                ->get();
        }

        $carpayments = $carpayments->sortBy(function ($carpayment) {
            return sprintf('%08s%08s', $carpayment->carpreemption->bookno, $carpayment->carpreemption->no);
        });

        $carpaymentselectlist = array();
        $carpaymentselectlist[null] = 'เลือกการจอง';
        foreach ($carpayments as $item) {
            $carpaymentselectlist[$item->id] = $item->carpreemption->bookno . '/' . $item->carpreemption->no;
        }

        $banks = Bank::orderBy('accountgroup', 'asc')->orderBy('name', 'asc')->get();
        $bankselectlist = array();
        $bankselectlist[null] = 'เลือกบัญชี';
        foreach ($banks as $item) {
            $bankselectlist[$item->id] = $item->name . ' ' . substr($item->accountno, -4);
        }

        $bankselectlist2 = array();
        array_push($bankselectlist2, ':เลือกบัญชี');
        foreach ($banks as $item) {
            array_push($bankselectlist2, $item->id . ':' . $item->name . ' ' . substr($item->accountno, -4));
        }

        $accountingdetail = new AccountingDetail();
        $carpaymentid = SupportRequest::old('carpaymentid');
        if ($carpaymentid != null && $carpaymentid > 0) {
            $accountingdetail = (new CarPaymentController)->getforaccountingdetailbyid($carpaymentid, 0);
            $additionalopenbill = SupportRequest::old('additionalopenbill');

            $finalopenbill = $accountingdetail->openbill;
            if ($additionalopenbill != null && $additionalopenbill > 0) {
                $finalopenbill = $finalopenbill + $additionalopenbill;
            }

            if ($finalopenbill == 0) {
                $accountingdetail->finalopenbill = 0;
                $accountingdetail->vatoffinalopenbill = 0;
                $accountingdetail->finalopenbillwithoutvat = 0;
                $accountingdetail->realsalespricewithoutvat = $accountingdetail->realsalesprice;
            } else {
                $accountingdetail->finalopenbill = $finalopenbill;
                $vat = ($finalopenbill * 7.00) / 107.00;
                $accountingdetail->vatoffinalopenbill = $vat;
                $accountingdetail->finalopenbillwithoutvat = ($finalopenbill - $vat);
                $realsalesprice = $accountingdetail->realsalesprice;
                $accountingdetail->realsalespricewithoutvat = ($realsalesprice - $vat);
            }

            $incasefinacecomfinamount = SupportRequest::old('incasefinacecomfinamount');
            if ($incasefinacecomfinamount != null) {
                $incasefinacetotalcomamount = $incasefinacecomfinamount + $accountingdetail->incasefinacecomextraamount
                    + $accountingdetail->incasefinacecompaamount;
                $accountingdetail->incasefinacetotalcomamount = $incasefinacetotalcomamount;
            }
            $incasefinacecomfinvat = SupportRequest::old('incasefinacecomfinvat');
            if ($incasefinacecomfinvat != null) {
                $incasefinacetotalcomvat = $incasefinacecomfinvat + $accountingdetail->incasefinacecomextravat
                    + $accountingdetail->incasefinacecompavat;
                $accountingdetail->incasefinacetotalcomvat = $incasefinacetotalcomvat;
            }
            $incasefinacecomfinamountwithvat = SupportRequest::old('incasefinacecomfinamountwithvat');
            if ($incasefinacecomfinamountwithvat != null) {
                $incasefinacetotalcomamountwithvat = $incasefinacecomfinamountwithvat + $accountingdetail->incasefinacecomextraamountwithvat
                    + $accountingdetail->incasefinacecompaamountwithvat;
                $accountingdetail->incasefinacetotalcomamountwithvat = $incasefinacetotalcomamountwithvat;
            }
            $incasefinacecomfinwhtax = SupportRequest::old('incasefinacecomfinwhtax');
            if ($incasefinacecomfinwhtax != null) {
                $incasefinacetotalcomwhtax = $incasefinacecomfinwhtax + $accountingdetail->incasefinacecomextrawhtax
                    + $accountingdetail->incasefinacecompawhtax;
                $accountingdetail->incasefinacetotalcomwhtax = $incasefinacetotalcomwhtax;
            }
            $incasefinacecomfintotal = SupportRequest::old('incasefinacecomfintotal');
            if ($incasefinacecomfintotal != null) {
                $incasefinacetotalcomtotal = $incasefinacecomfintotal + $accountingdetail->incasefinacecomextratotal
                    + $accountingdetail->incasefinacecompatotal;
                $accountingdetail->incasefinacetotalcomtotal = $incasefinacetotalcomtotal;
            }

            $receivedcashfromfinacenet = SupportRequest::old('receivedcashfromfinacenet');
            if ($receivedcashfromfinacenet != null) {
                $accountingdetail->receivedcashfromfinace = $receivedcashfromfinacenet;
            }
            $receivedcashfromfinacenetshort = SupportRequest::old('receivedcashfromfinacenetshort');
            if ($receivedcashfromfinacenetshort != null) {
                $accountingdetail->receivedcashfromfinaceshort = $receivedcashfromfinacenetshort;
            }
            $receivedcashfromfinacenetover = SupportRequest::old('receivedcashfromfinacenetover');
            if ($receivedcashfromfinacenetover != null) {
                $accountingdetail->receivedcashfromfinaceover = $receivedcashfromfinacenetover;
            }

            $incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash;
            $tradereceivableaccount1amount = $finalopenbill - $incasefinacereceivedcash;
            $accountingdetail->tradereceivableaccount1amount = $tradereceivableaccount1amount;
            $accountingdetail->ar = $tradereceivableaccount1amount;

            $adj = SupportRequest::old('adj');
            $cash = $tradereceivableaccount1amount - $accountingdetail->ins
                - $accountingdetail->prb
                - $accountingdetail->dc
                + $adj;
            $accountingdetail->cash = $cash;

            $accountingdetail->totalacc1 = $cash;
            $accountingdetail->totalacc1short = $cash;
            $accountingdetail->totalacc1over = 0;
            $accountingdetail->totalaccount1 = $cash;
            $accountingdetail->totalaccount1short = $cash;
            $accountingdetail->totalaccount1over = 0;

            $totalaccount1 = SupportRequest::old('totalaccount1');
            if ($totalaccount1 != null) {
                $accountingdetail->totalacc1 = $totalaccount1;
            }
            $totalaccount1short = SupportRequest::old('totalaccount1short');
            if ($totalaccount1short != null) {
                $accountingdetail->totalacc1short = $totalaccount1short;
            }
            $totalaccount1over = SupportRequest::old('totalaccount1over');
            if ($totalaccount1over != null) {
                $accountingdetail->totalacc1over = $totalaccount1over;
            }

            $tradereceivableaccount2amount = $accountingdetail->tradereceivableaccount2amount;
            if ($cash < $tradereceivableaccount2amount) {
                $totalaccount2 = $tradereceivableaccount2amount - $cash;
                $accountingdetail->totalacc2 = $totalaccount2;
                $accountingdetail->totalacc2short = $totalaccount2;
                $accountingdetail->totalacc2over = 0;
                $accountingdetail->totalaccount2 = $totalaccount2;
                $accountingdetail->totalaccount2short = $totalaccount2;
                $accountingdetail->totalaccount2over = 0;
            } else {
                $totalaccount2 = 0;
                $accountingdetail->totalacc2 = $totalaccount2;
                $accountingdetail->totalacc2short = $totalaccount2;
                $accountingdetail->totalacc2over = $totalaccount2;
                $accountingdetail->totalaccount2 = $totalaccount2;
                $accountingdetail->totalaccount2short = $totalaccount2;
                $accountingdetail->totalaccount2over = $totalaccount2;
            }

            $totalaccount2old = SupportRequest::old('totalaccount2');
            if ($totalaccount2old != null) {
                $accountingdetail->totalacc2 = $totalaccount2old;
            }
            $totalaccount2short = SupportRequest::old('totalaccount2short');
            if ($totalaccount2short != null) {
                $accountingdetail->totalacc2short = $totalaccount2short;
            }
            $totalaccount2over = SupportRequest::old('totalaccount2over');
            if ($totalaccount2over != null) {
                $accountingdetail->totalacc2over = $totalaccount2over;
            }

            $totalaccounts = $cash + $totalaccount2;
            $accountingdetail->totalaccs = $totalaccounts;
            $accountingdetail->totalaccsshort = $totalaccounts;
            $accountingdetail->totalaccsover = 0;
            $accountingdetail->totalaccounts = $totalaccounts;
            $accountingdetail->totalaccountsshort = $totalaccounts;
            $accountingdetail->totalaccountsover = 0;

            $totalaccounts = SupportRequest::old('totalaccounts');
            if ($totalaccounts != null) {
                $accountingdetail->totalaccs = $totalaccounts;
            }
            $totalaccountsshort = SupportRequest::old('totalaccountsshort');
            if ($totalaccountsshort != null) {
                $accountingdetail->totalaccsshort = $totalaccountsshort;
            }
            $totalaccountsover = SupportRequest::old('totalaccountsover');
            if ($totalaccountsover != null) {
                $accountingdetail->totalaccsover = $totalaccountsover;
            }
        }

        $receiveAndPayData0 = SupportRequest::old('receiveAndPayData0');
        $receiveAndPayData0 = json_decode($receiveAndPayData0, true);
        $receiveAndPayDatas0 = array();
        if ($receiveAndPayData0 != null && $receiveAndPayData0 != '') {
            foreach ($receiveAndPayData0 as $data) {
                $obj = (object)array("id" => $data["id"], "date" => $data["date"], "type" => $data["type"], "amount" => $data["amount"], "accountgroup" => $data["accountgroup"], "bankid" => $data["bankid"], "note" => $data["note"]);
                array_push($receiveAndPayDatas0, $obj);
            }
        }

        $receiveAndPayData1 = SupportRequest::old('receiveAndPayData1');
        $receiveAndPayData1 = json_decode($receiveAndPayData1, true);
        $receiveAndPayDatas1 = array();
        if ($receiveAndPayData1 != null && $receiveAndPayData1 != '') {
            foreach ($receiveAndPayData1 as $data) {
                $obj = (object)array("id" => $data["id"], "date" => $data["date"], "type" => $data["type"], "amount" => $data["amount"], "accountgroup" => $data["accountgroup"], "bankid" => $data["bankid"], "note" => $data["note"]);
                array_push($receiveAndPayDatas1, $obj);
            }
        }

        foreach ($accountingdetail->toArray() as $key => $value) {
            if (!in_array($key, $this->arrNotFormatted)) {
                if (is_numeric($value) && (float)$value != 0.00) {
                    $value = number_format($value, 2, '.', ',');
                } else if ($value == null || $value == '' || (is_numeric($value) && (float)$value == 0.00))
                    $value = '-';

                $accountingdetail->$key = $value;
            }
        }

        return view('accountingdetailform',
            ['oper' => 'new', 'pathPrefix' => '../', 'accountingdetail' => $accountingdetail,
                'carpaymentselectlist' => $carpaymentselectlist,
                'bankselectlist' => $bankselectlist,
                'bankselectlist2' => implode(";", $bankselectlist2),
                'receiveAndPayDatas0' => $receiveAndPayDatas0,
                'receiveAndPayDatas1' => $receiveAndPayDatas1]);
    }

    public function save(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $this->validate($request, [
            'carpaymentid' => 'required',
            'invoiceno' => 'required',
            'date' => 'required',
            'additionalopenbill' => 'required',
            'insurancefeereceiptcondition' => 'required_if:hasinsurancefee,1',
            'compulsorymotorinsurancefeereceiptcondition' => 'required_if:hascompulsorymotorinsurancefee,1',
            'insurancebilldifferent' => 'required',
            'note1insurancefeereceiptcondition' => 'required_if:hasinsurancefee,1',
            'note1compulsorymotorinsurancefeereceiptcondition' => 'required_if:hascompulsorymotorinsurancefee,1',
            'insurancefeepayment' => 'required_if:hasinsurancefee,1',
            'compulsorymotorinsurancefeepayment' => 'required_if:hascompulsorymotorinsurancefee,1',
            'cashpledgeredlabelreceiptbookno' => 'required_if:hascashpledgeredlabel,1',
            'cashpledgeredlabelreceiptno' => 'required_if:hascashpledgeredlabel,1',
            'cashpledgeredlabelreceiptdate' => 'required_if:hascashpledgeredlabel,1',

            'cashpledgereceiptbookno' => 'required',
            'cashpledgereceiptno' => 'required',
            'cashpledgereceiptdate' => 'required',
            'incasefinacecomfinamount' => 'required_if:purchasetype,1',
            'incasefinacecomfinvat' => 'required_if:purchasetype,1',
            'incasefinacecomfinamountwithvat' => 'required_if:purchasetype,1',
            'incasefinacecomfinwhtax' => 'required_if:purchasetype,1',
            'incasefinacecomfintotal' => 'required_if:purchasetype,1',
            'oldcarcomamount' => 'required',
            'adj' => 'required'
        ],
            [
                'carpaymentid.required' => 'กรุณาเลือกการจอง',
                'invoiceno.required' => 'เลขที่ใบกำกับ จำเป็นต้องกรอก',
                'date.required' => 'วันที่ จำเป็นต้องกรอก',
                'additionalopenbill.required' => 'ราคาเปิดบิลเพิ่มเติม จำเป็นต้องกรอก',
                'insurancefeereceiptcondition.required_if' => 'เงื่อนไข AC1* ค่าเบี้ย ป.1 จำเป็นต้องเลือก',
                'compulsorymotorinsurancefeereceiptcondition.required_if' => 'เงื่อนไข AC1* ค่า พ.ร.บ. จำเป็นต้องเลือก',
                'insurancebilldifferent.required' => 'ส่วนต่างบิลประกันภัย (รวม Vat) จำเป็นต้องกรอก',
                'note1insurancefeereceiptcondition.required_if' => 'เงื่อนไข AC2* ค่าเบี้ย ป.1 จำเป็นต้องเลือก',
                'note1compulsorymotorinsurancefeereceiptcondition.required_if' => 'เงื่อนไข AC2* ค่า พ.ร.บ. จำเป็นต้องเลือก',
                'insurancefeepayment.required_if' => 'ป.1 จ่ายเงิน จำเป็นต้องเลือก',
                'compulsorymotorinsurancefeepayment.required_if' => 'พ.ร.บ. จ่ายเงิน จำเป็นต้องเลือก',
                'cashpledgeredlabelreceiptbookno.required_if' => 'เงินมัดจำป้ายแดง เล่มที่ใบรับเงิน จำเป็นต้องกรอก',
                'cashpledgeredlabelreceiptno.required_if' => 'เงินมัดจำป้ายแดง เลขที่ใบรับเงิน จำเป็นต้องกรอก',
                'cashpledgeredlabelreceiptdate.required_if' => 'เงินมัดจำป้ายแดง วันที่ใบรับเงิน จำเป็นต้องกรอก',
                'cashpledgereceiptbookno.required' => 'เงินมัดจำรถ เล่มที่ใบรับเงิน จำเป็นต้องกรอก',
                'cashpledgereceiptno.required' => 'เงินมัดจำรถ เลขที่ใบรับเงิน จำเป็นต้องกรอก',
                'cashpledgereceiptdate.required' => 'เงินมัดจำรถ วันที่ใบรับเงิน จำเป็นต้องกรอก',
                'incasefinacecomfinamount.required_if' => 'Com Fin... จำเป็นต้องกรอก',
                'incasefinacecomfinvat.required_if' => 'Com Fin Vat... จำเป็นต้องกรอก',
                'incasefinacecomfinamountwithvat.required_if' => 'Com Fin Total... จำเป็นต้องกรอก',
                'incasefinacecomfinwhtax.required_if' => 'Com Fin W/H ถูกหัก จำเป็นต้องกรอก',
                'incasefinacecomfintotal.required_if' => 'Com Fin Net... จำเป็นต้องกรอก',
                'oldcarcomamount.required' => 'รับเงินค่าคอมรถเก่า จำเป็นต้องกรอก',
                'adj.required' => 'ADJ. จำเป็นต้องกรอก'
            ]
        );

        $input = $request->all();

        if ($request->has('id')) {
            $model = AccountingDetail::find($input['id']);
            if ($model == null)
                return "ขออภัย!! ไม่พบข้อมูลที่จะทำการแก้ไขในระบบ เนื่องจากอาจถูกลบไปแล้ว";
        } else
            $model = new AccountingDetail;

        $model->carpaymentid = $input['carpaymentid'];
        $model->invoiceno = $input['invoiceno'];
        $model->date = date('Y-m-d', strtotime($input['date']));
        $model->additionalopenbill = $input['additionalopenbill'];

        if ($input['payinadvanceamountreimbursementdate'] != null && $input['payinadvanceamountreimbursementdate'] != '')
            $model->payinadvanceamountreimbursementdate = date('Y-m-d', strtotime($input['payinadvanceamountreimbursementdate']));
        else
            $model->payinadvanceamountreimbursementdate = null;
        if ($input['payinadvanceamountreimbursementdocno'] != null && $input['payinadvanceamountreimbursementdocno'] != '')
            $model->payinadvanceamountreimbursementdocno = $input['payinadvanceamountreimbursementdocno'];
        else
            $model->payinadvanceamountreimbursementdocno = null;

        $model->insurancebilldifferent = $input['insurancebilldifferent'];

        if ($input['hasinsurancefee'] == 1) {
            $model->insurancefeereceiptcondition = $input['insurancefeereceiptcondition'];
            $model->note1insurancefeereceiptcondition = $input['note1insurancefeereceiptcondition'];
            $model->insurancefeepayment = $input['insurancefeepayment'];
            if ($input['insurancefeepayment'] == 1) {
                if ($input['insurancefeepaidseparatelydate'] != null && $input['insurancefeepaidseparatelydate'] != '')
                    $model->insurancefeepaidseparatelydate = date('Y-m-d', strtotime($input['insurancefeepaidseparatelydate']));
                if ($input['insurancepremiumnet'] != null && $input['insurancepremiumnet'] != '')
                    $model->insurancepremiumnet = $input['insurancepremiumnet'];
                if ($input['insurancepremiumcom'] != null && $input['insurancepremiumcom'] != '')
                    $model->insurancepremiumcom = $input['insurancepremiumcom'];
                if ($input['insurancefeepaidseparatelytotal'] != null && $input['insurancefeepaidseparatelytotal'] != '')
                    $model->insurancefeepaidseparatelytotal = $input['insurancefeepaidseparatelytotal'];
            } else {
                $model->insurancefeepaidseparatelydate = null;
                $model->insurancepremiumnet = null;
                $model->insurancepremiumcom = null;
                $model->insurancefeepaidseparatelytotal = null;
            }
        } else {
            $model->insurancefeereceiptcondition = null;
            $model->note1insurancefeereceiptcondition = null;
            $model->insurancefeepayment = null;
            $model->insurancefeepaidseparatelydate = null;
            $model->insurancepremiumnet = null;
            $model->insurancepremiumcom = null;
            $model->insurancefeepaidseparatelytotal = null;
        }

        if ($input['hascompulsorymotorinsurancefee'] == 1) {
            $model->compulsorymotorinsurancefeereceiptcondition = $input['compulsorymotorinsurancefeereceiptcondition'];
            $model->note1compulsorymotorinsurancefeereceiptcondition = $input['note1compulsorymotorinsurancefeereceiptcondition'];
            $model->compulsorymotorinsurancefeepayment = $input['compulsorymotorinsurancefeepayment'];
            if ($input['compulsorymotorinsurancefeepayment'] == 1) {
                if ($input['compulsorymotorinsurancefeepaidseparatelydate'] != null && $input['compulsorymotorinsurancefeepaidseparatelydate'] != '')
                    $model->compulsorymotorinsurancefeepaidseparatelydate = date('Y-m-d', strtotime($input['compulsorymotorinsurancefeepaidseparatelydate']));
                if ($input['compulsorymotorinsurancepremiumnet'] != null && $input['compulsorymotorinsurancepremiumnet'] != '')
                    $model->compulsorymotorinsurancepremiumnet = $input['compulsorymotorinsurancepremiumnet'];
                if ($input['compulsorymotorinsurancepremiumcom'] != null && $input['compulsorymotorinsurancepremiumcom'] != '')
                    $model->compulsorymotorinsurancepremiumcom = $input['compulsorymotorinsurancepremiumcom'];
                if ($input['compulsorymotorinsurancefeepaidseparatelytotal'] != null && $input['compulsorymotorinsurancefeepaidseparatelytotal'] != '')
                    $model->compulsorymotorinsurancefeepaidseparatelytotal = $input['compulsorymotorinsurancefeepaidseparatelytotal'];
            } else {
                $model->compulsorymotorinsurancefeepaidseparatelydate = null;
                $model->compulsorymotorinsurancepremiumnet = null;
                $model->compulsorymotorinsurancepremiumcom = null;
                $model->compulsorymotorinsurancefeepaidseparatelytotal = null;
            }
        } else {
            $model->compulsorymotorinsurancefeereceiptcondition = null;
            $model->note1compulsorymotorinsurancefeereceiptcondition = null;
            $model->compulsorymotorinsurancefeepayment = null;
            $model->compulsorymotorinsurancefeepaidseparatelydate = null;
            $model->compulsorymotorinsurancepremiumnet = null;
            $model->compulsorymotorinsurancepremiumcom = null;
            $model->compulsorymotorinsurancefeepaidseparatelytotal = null;
        }

        if ($input['hascashpledgeredlabel'] == 1) {
            $model->cashpledgeredlabelreceiptbookno = $input['cashpledgeredlabelreceiptbookno'];
            $model->cashpledgeredlabelreceiptno = $input['cashpledgeredlabelreceiptno'];
            $model->cashpledgeredlabelreceiptdate = date('Y-m-d', strtotime($input['cashpledgeredlabelreceiptdate']));
        } else {
            $model->cashpledgeredlabelreceiptbookno = null;
            $model->cashpledgeredlabelreceiptno = null;
            $model->cashpledgeredlabelreceiptdate = null;
        }

        $model->cashpledgereceiptbookno = $input['cashpledgereceiptbookno'];
        $model->cashpledgereceiptno = $input['cashpledgereceiptno'];
        $model->cashpledgereceiptdate = date('Y-m-d', strtotime($input['cashpledgereceiptdate']));

        if ($input['purchasetype'] == 1) {
            $model->incasefinacecomfinamount = $input['incasefinacecomfinamount'];
            $model->incasefinacecomfinvat = $input['incasefinacecomfinvat'];
            $model->incasefinacecomfinamountwithvat = $input['incasefinacecomfinamountwithvat'];
            $model->incasefinacecomfinwhtax = $input['incasefinacecomfinwhtax'];
            $model->incasefinacecomfintotal = $input['incasefinacecomfintotal'];
            $model->systemcalincasefinacecomfinamount = $input['systemcalincasefinacecomfinamount'];
            $model->systemcalincasefinacecomfinvat = $input['systemcalincasefinacecomfinvat'];
            $model->systemcalincasefinacecomfinamountwithvat = $input['systemcalincasefinacecomfinamountwithvat'];
            $model->systemcalincasefinacecomfinwhtax = $input['systemcalincasefinacecomfinwhtax'];
            $model->systemcalincasefinacecomfintotal = $input['systemcalincasefinacecomfintotal'];
            $model->receivedcashfromfinacenet = $input['receivedcashfromfinacenet'];
            $model->receivedcashfromfinacenetshort = $input['receivedcashfromfinacenetshort'];
            $model->receivedcashfromfinacenetover = $input['receivedcashfromfinacenetover'];
        } else {
            $model->incasefinacecomfinamount = null;
            $model->incasefinacecomfinvat = null;
            $model->incasefinacecomfinamountwithvat = null;
            $model->incasefinacecomfinwhtax = null;
            $model->incasefinacecomfintotal = null;
            $model->systemcalincasefinacecomfinamount = null;
            $model->systemcalincasefinacecomfinvat = null;
            $model->systemcalincasefinacecomfinamountwithvat = null;
            $model->systemcalincasefinacecomfinwhtax = null;
            $model->systemcalincasefinacecomfintotal = null;
            $model->receivedcashfromfinacenet = null;
            $model->receivedcashfromfinacenetshort = null;
            $model->receivedcashfromfinacenetover = null;
        }
        $model->totalaccount1 = $input['totalaccount1'];
        $model->totalaccount1short = $input['totalaccount1short'];
        $model->totalaccount1over = $input['totalaccount1over'];
        $model->totalaccount2 = $input['totalaccount2'];
        $model->totalaccount2short = $input['totalaccount2short'];
        $model->totalaccount2over = $input['totalaccount2over'];
        $model->totalaccounts = $input['totalaccounts'];
        $model->totalaccountsshort = $input['totalaccountsshort'];
        $model->totalaccountsover = $input['totalaccountsover'];

        $model->oldcarcomamount = $input['oldcarcomamount'];
        if ($input['oldcarcomdate'] != null && $input['oldcarcomdate'] != '')
            $model->oldcarcomdate = date('Y-m-d', strtotime($input['oldcarcomdate']));
        else
            $model->oldcarcomdate = null;
        $model->adj = $input['adj'];

        if ($model->save()) {
            $receiveAndPayData0 = $request->receiveAndPayData0;
            $receiveAndPayData0 = json_decode($receiveAndPayData0, true);

            foreach ($receiveAndPayData0 as $data) {
                $obj = new AccountingDetailReceiveAndPay();
                $obj->accountingdetailid = $model->id;
                $obj->sectiontype = 0;
                $obj->date = date('Y-m-d', strtotime($data["date"]));
                $obj->type = $data["type"];
                $obj->amount = $data["amount"];
                $obj->accountgroup = $data["accountgroup"];
                $obj->bankid = $data["bankid"];
                $obj->note = $data["note"];
                $obj->save();
            }

            $receiveAndPayData1 = $request->receiveAndPayData1;
            $receiveAndPayData1 = json_decode($receiveAndPayData1, true);

            foreach ($receiveAndPayData1 as $data) {
                $obj = new AccountingDetailReceiveAndPay();
                $obj->accountingdetailid = $model->id;
                $obj->sectiontype = 1;
                $obj->date = date('Y-m-d', strtotime($data["date"]));
                $obj->type = $data["type"];
                $obj->amount = $data["amount"];
                $obj->accountgroup = $data["accountgroup"];
                $obj->bankid = $data["bankid"];
                $obj->note = $data["note"];
                $obj->save();
            }

            return redirect()->action('AccountingDetailController@edit', ['id' => $model->id]);
        } else {
            //hack returning error
            $this->validate($request, ['invoiceno' => 'alpha'], ['invoiceno.alpha' => 'ไม่สามารถทำการบันทึกข้อมูลรายละเอียดเพื่อการบันทึกบัญชีได้ กรุณาติดต่อผู้ดูแลระบบ!!']);
        }
    }

    public function edit($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $tempModel = AccountingDetail::find($id);
        $accountingdetail = (new CarPaymentController)->getforaccountingdetailbyid($tempModel->carpaymentid, 0);
        $accountingdetail->id = $tempModel->id;
        $accountingdetail->invoiceno = $tempModel->invoiceno;
        $accountingdetail->date = date('d-m-Y', strtotime($tempModel->date));
        $accountingdetail->additionalopenbill = $tempModel->additionalopenbill;
        $accountingdetail->insurancefeereceiptcondition = $tempModel->insurancefeereceiptcondition;
        if ($tempModel->payinadvanceamountreimbursementdate != null)
            $accountingdetail->payinadvanceamountreimbursementdate = date('d-m-Y', strtotime($tempModel->payinadvanceamountreimbursementdate));
        else
            $accountingdetail->payinadvanceamountreimbursementdate = null;
        $accountingdetail->payinadvanceamountreimbursementdocno = $tempModel->payinadvanceamountreimbursementdocno;
        $accountingdetail->insurancebilldifferent = $tempModel->insurancebilldifferent;
        if ($tempModel->insurancebilldifferent > 0) {
            $accountingdetail->note1insurancefeeincludevat = $accountingdetail->note1insurancefeeincludevat + $tempModel->insurancebilldifferent;
            $accountingdetail->note1insurancefee = ($accountingdetail->note1insurancefeeincludevat * 100) / 107.00;
            $accountingdetail->note1insurancefeevat = $accountingdetail->note1insurancefeeincludevat - $accountingdetail->note1insurancefee;

            $accountingdetail->incasefinaceinsurancefee = $accountingdetail->incasefinaceinsurancefee + $tempModel->insurancebilldifferent;
            $accountingdetail->totalincasefinace = $accountingdetail->totalincasefinace + $tempModel->insurancebilldifferent;
            $accountingdetail->incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash - $tempModel->insurancebilldifferent;
            $accountingdetail->incasefinacehassubsidisereceivedcash = $accountingdetail->incasefinacehassubsidisereceivedcash - $tempModel->insurancebilldifferent;

            $accountingdetail->ins = $accountingdetail->ins + $tempModel->insurancebilldifferent;
        }
        $accountingdetail->note1insurancefeereceiptcondition = $tempModel->note1insurancefeereceiptcondition;
        $accountingdetail->insurancefeepayment = $tempModel->insurancefeepayment;
        if ($tempModel->insurancefeepayment == 1) {
            $accountingdetail->note2insurancefeewhtax = 0;
            $accountingdetail->totalincasefinace = $accountingdetail->totalincasefinace - $accountingdetail->incasefinaceinsurancefee;
            $accountingdetail->incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash + $accountingdetail->incasefinaceinsurancefee;
            $accountingdetail->incasefinacehassubsidisereceivedcash = $accountingdetail->incasefinacehassubsidisereceivedcash + $accountingdetail->incasefinaceinsurancefee;
            $accountingdetail->incasefinaceinsurancefee = 0;
        }
        if ($tempModel->insurancefeepaidseparatelydate != null)
            $accountingdetail->insurancefeepaidseparatelydate = date('d-m-Y', strtotime($tempModel->insurancefeepaidseparatelydate));
        else
            $accountingdetail->insurancefeepaidseparatelydate = null;
        $accountingdetail->insurancepremiumnet = $tempModel->insurancepremiumnet;
        $accountingdetail->insurancepremiumcom = $tempModel->insurancepremiumcom;
        $accountingdetail->insurancefeepaidseparatelytotal = $tempModel->insurancefeepaidseparatelytotal;

        if ($tempModel->insurancefeereceiptcondition == 0 || $tempModel->insurancefeereceiptcondition == 1) {
            $accountingdetail->hasinsurancefee = 1;
            if ($tempModel->insurancefeereceiptcondition == 0) {
                $accountingdetail->note2insurancefeewhtax = 0;
                $accountingdetail->ins = 0;
            }
        }
        else
            $accountingdetail->hasinsurancefee = 0;

        $accountingdetail->compulsorymotorinsurancefeereceiptcondition = $tempModel->compulsorymotorinsurancefeereceiptcondition;
        $accountingdetail->note1compulsorymotorinsurancefeereceiptcondition = $tempModel->note1compulsorymotorinsurancefeereceiptcondition;
        $accountingdetail->compulsorymotorinsurancefeepayment = $tempModel->compulsorymotorinsurancefeepayment;
        if ($tempModel->compulsorymotorinsurancefeepayment == 1) {
            $accountingdetail->note2compulsorymotorinsurancefeewhtax = 0;
            $accountingdetail->totalincasefinace = $accountingdetail->totalincasefinace - $accountingdetail->incasefinacecompulsorymotorinsurancefee;
            $accountingdetail->incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash + $accountingdetail->incasefinacecompulsorymotorinsurancefee;
            $accountingdetail->incasefinacehassubsidisereceivedcash = $accountingdetail->incasefinacehassubsidisereceivedcash + $accountingdetail->incasefinacecompulsorymotorinsurancefee;
            $accountingdetail->incasefinacecompulsorymotorinsurancefee = 0;
        }
        if ($tempModel->compulsorymotorinsurancefeepaidseparatelydate != null)
            $accountingdetail->compulsorymotorinsurancefeepaidseparatelydate = date('d-m-Y', strtotime($tempModel->compulsorymotorinsurancefeepaidseparatelydate));
        else
            $accountingdetail->compulsorymotorinsurancefeepaidseparatelydate = null;
        $accountingdetail->compulsorymotorinsurancepremiumnet = $tempModel->compulsorymotorinsurancepremiumnet;
        $accountingdetail->compulsorymotorinsurancepremiumcom = $tempModel->compulsorymotorinsurancepremiumcom;
        $accountingdetail->compulsorymotorinsurancefeepaidseparatelytotal = $tempModel->compulsorymotorinsurancefeepaidseparatelytotal;

        if ($tempModel->compulsorymotorinsurancefeereceiptcondition == 0 || $tempModel->compulsorymotorinsurancefeereceiptcondition == 1) {
            $accountingdetail->hascompulsorymotorinsurancefee = 1;
            if ($tempModel->compulsorymotorinsurancefeereceiptcondition == 0) {
                $accountingdetail->note2compulsorymotorinsurancefeewhtax = 0;
                $accountingdetail->prb = 0;
            }
        }
        else
            $accountingdetail->hascompulsorymotorinsurancefee = 0;

        $note2totalwhtax = $accountingdetail->note2insurancefeewhtax + $accountingdetail->note2compulsorymotorinsurancefeewhtax + $accountingdetail->note2subsidisewhtax;
        $accountingdetail->note2totalwhtax = $note2totalwhtax;

        $accountingdetail->cashpledgeredlabelreceiptbookno = $tempModel->cashpledgeredlabelreceiptbookno;
        $accountingdetail->cashpledgeredlabelreceiptno = $tempModel->cashpledgeredlabelreceiptno;
        if ($tempModel->cashpledgeredlabelreceiptdate != null)
            $accountingdetail->cashpledgeredlabelreceiptdate = date('d-m-Y', strtotime($tempModel->cashpledgeredlabelreceiptdate));
        else $accountingdetail->cashpledgeredlabelreceiptdate = null;
        $accountingdetail->cashpledgereceiptbookno = $tempModel->cashpledgereceiptbookno;
        $accountingdetail->cashpledgereceiptno = $tempModel->cashpledgereceiptno;
        $accountingdetail->cashpledgereceiptdate = date('d-m-Y', strtotime($tempModel->cashpledgereceiptdate));
        $accountingdetail->oldcarcomamount = $tempModel->oldcarcomamount;
        if ($tempModel->oldcarcomdate != null)
            $accountingdetail->oldcarcomdate = date('d-m-Y', strtotime($tempModel->oldcarcomdate));
        else $accountingdetail->oldcarcomdate = null;
        $accountingdetail->adj = $tempModel->adj;
        $accountingdetail->incasefinacecomfinamount = $tempModel->incasefinacecomfinamount;
        $accountingdetail->incasefinacecomfinvat = $tempModel->incasefinacecomfinvat;
        $accountingdetail->incasefinacecomfinamountwithvat = $tempModel->incasefinacecomfinamountwithvat;
        $accountingdetail->incasefinacecomfinwhtax = $tempModel->incasefinacecomfinwhtax;
        $accountingdetail->incasefinacecomfintotal = $tempModel->incasefinacecomfintotal;

        $accountingdetail->incasefinacetotalcomamount = $accountingdetail->incasefinacecomfinamount
            + $accountingdetail->incasefinacecomextraamount + $accountingdetail->incasefinacecompaamount;
        $accountingdetail->incasefinacetotalcomvat = $accountingdetail->incasefinacecomfinvat
            + $accountingdetail->incasefinacecomextravat + $accountingdetail->incasefinacecompavat;
        $accountingdetail->incasefinacetotalcomamountwithvat = $accountingdetail->incasefinacecomfinamountwithvat
            + $accountingdetail->incasefinacecomextraamountwithvat + $accountingdetail->incasefinacecompaamountwithvat;
        $accountingdetail->incasefinacetotalcomwhtax = $accountingdetail->incasefinacecomfinwhtax
            + $accountingdetail->incasefinacecomextrawhtax + $accountingdetail->incasefinacecompawhtax;
        $accountingdetail->incasefinacetotalcomtotal = $accountingdetail->incasefinacecomfintotal
            + $accountingdetail->incasefinacecomextratotal + $accountingdetail->incasefinacecompatotal;

        $accountingdetail->systemcalincasefinacecomfinamount = $tempModel->systemcalincasefinacecomfinamount;
        $accountingdetail->systemcalincasefinacecomfinvat = $tempModel->systemcalincasefinacecomfinvat;
        $accountingdetail->systemcalincasefinacecomfinamountwithvat = $tempModel->systemcalincasefinacecomfinamountwithvat;
        $accountingdetail->systemcalincasefinacecomfinwhtax = $tempModel->systemcalincasefinacecomfinwhtax;
        $accountingdetail->systemcalincasefinacecomfintotal = $tempModel->systemcalincasefinacecomfintotal;
        $accountingdetail->receivedcashfromfinace = $tempModel->receivedcashfromfinacenet;
        $accountingdetail->receivedcashfromfinacenet = $tempModel->receivedcashfromfinacenet;
        $accountingdetail->receivedcashfromfinaceshort = $tempModel->receivedcashfromfinacenetshort;
        $accountingdetail->receivedcashfromfinacenetshort = $tempModel->receivedcashfromfinacenetshort;
        $accountingdetail->receivedcashfromfinaceover = $tempModel->receivedcashfromfinacenetover;
        $accountingdetail->receivedcashfromfinacenetover = $tempModel->receivedcashfromfinacenetover;

        $accountingdetail->totalacc1 = $tempModel->totalaccount1;
        $accountingdetail->totalaccount1 = $tempModel->totalaccount1;
        $accountingdetail->totalacc1short = $tempModel->totalaccount1short;
        $accountingdetail->totalaccount1short = $tempModel->totalaccount1short;
        $accountingdetail->totalacc1over = $tempModel->totalaccount1over;
        $accountingdetail->totalaccount1over = $tempModel->totalaccount1over;

        $accountingdetail->totalacc2 = $tempModel->totalaccount2;
        $accountingdetail->totalaccount2 = $tempModel->totalaccount2;
        $accountingdetail->totalacc2short = $tempModel->totalaccount2short;
        $accountingdetail->totalaccount2short = $tempModel->totalaccount2short;
        $accountingdetail->totalacc2over = $tempModel->totalaccount2over;
        $accountingdetail->totalaccount2over = $tempModel->totalaccount2over;

        $accountingdetail->totalaccs = $tempModel->totalaccounts;
        $accountingdetail->totalaccounts = $tempModel->totalaccounts;
        $accountingdetail->totalaccsshort = $tempModel->totalaccountsshort;
        $accountingdetail->totalaccountsshort = $tempModel->totalaccountsshort;
        $accountingdetail->totalaccsover = $tempModel->totalaccountsover;
        $accountingdetail->totalaccountsover = $tempModel->totalaccountsover;

        $additionalopenbill = SupportRequest::old('additionalopenbill');
        if ($additionalopenbill != null) $accountingdetail->additionalopenbill = $additionalopenbill;

        $finalopenbill = $accountingdetail->openbill;
        if ($accountingdetail->additionalopenbill != null && $accountingdetail->additionalopenbill > 0) {
            $finalopenbill = $finalopenbill + $accountingdetail->additionalopenbill;
        }

        if ($finalopenbill == 0) {
            $accountingdetail->finalopenbill = 0;
            $accountingdetail->vatoffinalopenbill = 0;
            $accountingdetail->finalopenbillwithoutvat = 0;
            $accountingdetail->realsalespricewithoutvat = $accountingdetail->realsalesprice;
        } else {
            $accountingdetail->finalopenbill = $finalopenbill;
            $vat = ($finalopenbill * 7.00) / 107.00;
            $accountingdetail->vatoffinalopenbill = $vat;
            $accountingdetail->finalopenbillwithoutvat = ($finalopenbill - $vat);
            $realsalesprice = $accountingdetail->realsalesprice;
            $accountingdetail->realsalespricewithoutvat = ($realsalesprice - $vat);
        }

        $incasefinacecomfinamount = SupportRequest::old('incasefinacecomfinamount');
        if ($incasefinacecomfinamount != null) {
            $incasefinacetotalcomamount = $incasefinacecomfinamount + $accountingdetail->incasefinacecomextraamount
                + $accountingdetail->incasefinacecompaamount;
            $accountingdetail->incasefinacetotalcomamount = $incasefinacetotalcomamount;
        }
        $incasefinacecomfinvat = SupportRequest::old('incasefinacecomfinvat');
        if ($incasefinacecomfinvat != null) {
            $incasefinacetotalcomvat = $incasefinacecomfinvat + $accountingdetail->incasefinacecomextravat
                + $accountingdetail->incasefinacecompavat;
            $accountingdetail->incasefinacetotalcomvat = $incasefinacetotalcomvat;
        }
        $incasefinacecomfinamountwithvat = SupportRequest::old('incasefinacecomfinamountwithvat');
        if ($incasefinacecomfinamountwithvat != null) {
            $incasefinacetotalcomamountwithvat = $incasefinacecomfinamountwithvat + $accountingdetail->incasefinacecomextraamountwithvat
                + $accountingdetail->incasefinacecompaamountwithvat;
            $accountingdetail->incasefinacetotalcomamountwithvat = $incasefinacetotalcomamountwithvat;
        }
        $incasefinacecomfinwhtax = SupportRequest::old('incasefinacecomfinwhtax');
        if ($incasefinacecomfinwhtax != null) {
            $incasefinacetotalcomwhtax = $incasefinacecomfinwhtax + $accountingdetail->incasefinacecomextrawhtax
                + $accountingdetail->incasefinacecompawhtax;
            $accountingdetail->incasefinacetotalcomwhtax = $incasefinacetotalcomwhtax;
        }
        $incasefinacecomfintotal = SupportRequest::old('incasefinacecomfintotal');
        if ($incasefinacecomfintotal != null) {
            $incasefinacetotalcomtotal = $incasefinacecomfintotal + $accountingdetail->incasefinacecomextratotal
                + $accountingdetail->incasefinacecompatotal;
            $accountingdetail->incasefinacetotalcomtotal = $incasefinacetotalcomtotal;
        }

        $receivedcashfromfinacenet = SupportRequest::old('receivedcashfromfinacenet');
        if ($receivedcashfromfinacenet != null) {
            $accountingdetail->receivedcashfromfinace = $receivedcashfromfinacenet;
        }
        $receivedcashfromfinacenetshort = SupportRequest::old('receivedcashfromfinacenetshort');
        if ($receivedcashfromfinacenetshort != null) {
            $accountingdetail->receivedcashfromfinaceshort = $receivedcashfromfinacenetshort;
        }
        $receivedcashfromfinacenetover = SupportRequest::old('receivedcashfromfinacenetover');
        if ($receivedcashfromfinacenetover != null) {
            $accountingdetail->receivedcashfromfinaceover = $receivedcashfromfinacenetover;
        }

        $incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash;
        $tradereceivableaccount1amount = $finalopenbill - $incasefinacereceivedcash;
        $accountingdetail->tradereceivableaccount1amount = $tradereceivableaccount1amount;
        $accountingdetail->ar = $tradereceivableaccount1amount;

        $adj = SupportRequest::old('adj');
        if ($adj != null) $accountingdetail->adj = $adj;

        $cash = $tradereceivableaccount1amount - $accountingdetail->ins
            - $accountingdetail->prb
            - $accountingdetail->dc
            + $accountingdetail->adj;
        $accountingdetail->cash = $cash;

        $totalaccount1 = SupportRequest::old('totalaccount1');
        if ($totalaccount1 != null) {
            $accountingdetail->totalacc1 = $totalaccount1;
        }
        $totalaccount1short = SupportRequest::old('totalaccount1short');
        if ($totalaccount1short != null) {
            $accountingdetail->totalacc1short = $totalaccount1short;
        }
        $totalaccount1over = SupportRequest::old('totalaccount1over');
        if ($totalaccount1over != null) {
            $accountingdetail->totalacc1over = $totalaccount1over;
        }

        $totalaccount2 = SupportRequest::old('totalaccount2');
        if ($totalaccount2 != null) {
            $accountingdetail->totalacc2 = $totalaccount2;
        }
        $totalaccount2short = SupportRequest::old('totalaccount2short');
        if ($totalaccount2short != null) {
            $accountingdetail->totalacc2short = $totalaccount2short;
        }
        $totalaccount2over = SupportRequest::old('totalaccount2over');
        if ($totalaccount2over != null) {
            $accountingdetail->totalacc2over = $totalaccount2over;
        }

        $totalaccounts = SupportRequest::old('totalaccounts');
        if ($totalaccounts != null) {
            $accountingdetail->totalaccs = $totalaccounts;
        }
        $totalaccountsshort = SupportRequest::old('totalaccountsshort');
        if ($totalaccountsshort != null) {
            $accountingdetail->totalaccsshort = $totalaccountsshort;
        }
        $totalaccountsover = SupportRequest::old('totalaccountsover');
        if ($totalaccountsover != null) {
            $accountingdetail->totalaccsover = $totalaccountsover;
        }

        $carpayment = CarPayment::where('id', $tempModel->carpaymentid)->with('carpreemption')->first();
        $carpaymentselectlist = array();
        $carpaymentselectlist[$carpayment->id] = $carpayment->carpreemption->bookno . '/' . $carpayment->carpreemption->no;

        $banks = Bank::orderBy('accountgroup', 'asc')->orderBy('name', 'asc')->get();
        $bankselectlist = array();
        $bankselectlist[null] = 'เลือกบัญชี';
        foreach ($banks as $item) {
            $bankselectlist[$item->id] = $item->name . ' ' . substr($item->accountno, -4);
        }

        $bankselectlist2 = array();
        array_push($bankselectlist2, ':เลือกบัญชี');
        foreach ($banks as $item) {
            array_push($bankselectlist2, $item->id . ':' . $item->name . ' ' . substr($item->accountno, -4));
        }

        $receiveAndPayDatas0 = array();
        $receiveAndPayData0 = SupportRequest::old('receiveAndPayData0');
        if ($receiveAndPayData0 != null && $receiveAndPayData0 != '') {
            $receiveAndPayData0 = json_decode($receiveAndPayData0, true);
            foreach ($receiveAndPayData0 as $data) {
                $obj = (object)array("id" => $data["id"], "date" => $data["date"], "type" => $data["type"], "amount" => $data["amount"], "accountgroup" => $data["accountgroup"], "bankid" => $data["bankid"], "note" => $data["note"]);
                array_push($receiveAndPayDatas0, $obj);
            }
        } else {
            $receiveAndPays0 = AccountingDetailReceiveAndPay::where('accountingdetailid', $id)->where('sectiontype', 0)
                ->get(['id', 'date', 'type', 'amount', 'accountgroup', 'bankid', 'note']);

            foreach ($receiveAndPays0 as $data) {
                $obj = (object)array("id" => $data->id, "date" => $data->date, "type" => $data->type, "amount" => $data->amount, "accountgroup" => $data->accountgroup, "bankid" => $data->bankid, "note" => $data->note);
                array_push($receiveAndPayDatas0, $obj);
            }
        }

        $receiveAndPayDatas1 = array();
        $receiveAndPayData1 = SupportRequest::old('receiveAndPayData1');
        if ($receiveAndPayData1 != null && $receiveAndPayData1 != '') {
            $receiveAndPayData1 = json_decode($receiveAndPayData1, true);
            foreach ($receiveAndPayData1 as $data) {
                $obj = (object)array("id" => $data["id"], "date" => $data["date"], "type" => $data["type"], "amount" => $data["amount"], "accountgroup" => $data["accountgroup"], "bankid" => $data["bankid"], "note" => $data["note"]);
                array_push($receiveAndPayDatas1, $obj);
            }
        } else {
            $receiveAndPays1 = AccountingDetailReceiveAndPay::where('accountingdetailid', $id)->where('sectiontype', 1)
                ->get(['id', 'date', 'type', 'amount', 'accountgroup', 'bankid', 'note']);

            foreach ($receiveAndPays1 as $data) {
                $obj = (object)array("id" => $data->id, "date" => $data->date, "type" => $data->type, "amount" => $data->amount, "accountgroup" => $data->accountgroup, "bankid" => $data->bankid, "note" => $data->note);
                array_push($receiveAndPayDatas1, $obj);
            }
        }


        foreach ($accountingdetail->toArray() as $key => $value) {
            if (!in_array($key, $this->arrNotFormatted)) {
                if (is_numeric($value) && (float)$value != 0.00) {
                    $value = number_format($value, 2, '.', ',');
                } else if ($value == null || $value == '' || (is_numeric($value) && (float)$value == 0.00))
                    $value = '-';

                $accountingdetail->$key = $value;
            }
        }

        return view('accountingdetailform',
            ['oper' => 'edit', 'pathPrefix' => '../../', 'accountingdetail' => $accountingdetail,
                'carpaymentselectlist' => $carpaymentselectlist,
                'bankselectlist' => $bankselectlist,
                'bankselectlist2' => implode(";", $bankselectlist2),
                'receiveAndPayDatas0' => $receiveAndPayDatas0,
                'receiveAndPayDatas1' => $receiveAndPayDatas1]);
    }

    public function view($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $tempModel = AccountingDetail::find($id);
        $accountingdetail = (new CarPaymentController)->getforaccountingdetailbyid($tempModel->carpaymentid, 0);
        $accountingdetail->id = $tempModel->id;
        $accountingdetail->invoiceno = $tempModel->invoiceno;
        $accountingdetail->date = date('d-m-Y', strtotime($tempModel->date));
        $accountingdetail->additionalopenbill = $tempModel->additionalopenbill;
        $accountingdetail->insurancefeereceiptcondition = $tempModel->insurancefeereceiptcondition;
        if ($tempModel->payinadvanceamountreimbursementdate != null)
            $accountingdetail->payinadvanceamountreimbursementdate = date('d-m-Y', strtotime($tempModel->payinadvanceamountreimbursementdate));
        else
            $accountingdetail->payinadvanceamountreimbursementdate = null;
        $accountingdetail->payinadvanceamountreimbursementdocno = $tempModel->payinadvanceamountreimbursementdocno;
        $accountingdetail->insurancebilldifferent = $tempModel->insurancebilldifferent;
        if ($tempModel->insurancebilldifferent > 0) {
            $accountingdetail->note1insurancefeeincludevat = $accountingdetail->note1insurancefeeincludevat + $tempModel->insurancebilldifferent;
            $accountingdetail->note1insurancefee = ($accountingdetail->note1insurancefeeincludevat * 100) / 107.00;
            $accountingdetail->note1insurancefeevat = $accountingdetail->note1insurancefeeincludevat - $accountingdetail->note1insurancefee;

            $accountingdetail->incasefinaceinsurancefee = $accountingdetail->incasefinaceinsurancefee + $tempModel->insurancebilldifferent;
            $accountingdetail->totalincasefinace = $accountingdetail->totalincasefinace + $tempModel->insurancebilldifferent;
            $accountingdetail->incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash - $tempModel->insurancebilldifferent;
            $accountingdetail->incasefinacehassubsidisereceivedcash = $accountingdetail->incasefinacehassubsidisereceivedcash - $tempModel->insurancebilldifferent;

            $accountingdetail->ins = $accountingdetail->ins + $tempModel->insurancebilldifferent;
        }
        $accountingdetail->note1insurancefeereceiptcondition = $tempModel->note1insurancefeereceiptcondition;
        $accountingdetail->insurancefeepayment = $tempModel->insurancefeepayment;
        if ($tempModel->insurancefeepayment == 1) {
            $accountingdetail->note2insurancefeewhtax = 0;
            $accountingdetail->totalincasefinace = $accountingdetail->totalincasefinace - $accountingdetail->incasefinaceinsurancefee;
            $accountingdetail->incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash + $accountingdetail->incasefinaceinsurancefee;
            $accountingdetail->incasefinacehassubsidisereceivedcash = $accountingdetail->incasefinacehassubsidisereceivedcash + $accountingdetail->incasefinaceinsurancefee;
            $accountingdetail->incasefinaceinsurancefee = 0;
        }
        if ($tempModel->insurancefeepaidseparatelydate != null)
            $accountingdetail->insurancefeepaidseparatelydate = date('d-m-Y', strtotime($tempModel->insurancefeepaidseparatelydate));
        else
            $accountingdetail->insurancefeepaidseparatelydate = null;
        $accountingdetail->insurancepremiumnet = $tempModel->insurancepremiumnet;
        $accountingdetail->insurancepremiumcom = $tempModel->insurancepremiumcom;
        $accountingdetail->insurancefeepaidseparatelytotal = $tempModel->insurancefeepaidseparatelytotal;

        if ($tempModel->insurancefeereceiptcondition == 0 || $tempModel->insurancefeereceiptcondition == 1) {
            $accountingdetail->hasinsurancefee = 1;
            if ($tempModel->insurancefeereceiptcondition == 0) {
                $accountingdetail->note2insurancefeewhtax = 0;
                $accountingdetail->ins = 0;
            }
        }
        else
            $accountingdetail->hasinsurancefee = 0;

        $accountingdetail->compulsorymotorinsurancefeereceiptcondition = $tempModel->compulsorymotorinsurancefeereceiptcondition;
        $accountingdetail->note1compulsorymotorinsurancefeereceiptcondition = $tempModel->note1compulsorymotorinsurancefeereceiptcondition;
        $accountingdetail->compulsorymotorinsurancefeepayment = $tempModel->compulsorymotorinsurancefeepayment;
        if ($tempModel->compulsorymotorinsurancefeepayment == 1) {
            $accountingdetail->note2compulsorymotorinsurancefeewhtax = 0;
            $accountingdetail->totalincasefinace = $accountingdetail->totalincasefinace - $accountingdetail->incasefinacecompulsorymotorinsurancefee;
            $accountingdetail->incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash + $accountingdetail->incasefinacecompulsorymotorinsurancefee;
            $accountingdetail->incasefinacehassubsidisereceivedcash = $accountingdetail->incasefinacehassubsidisereceivedcash + $accountingdetail->incasefinacecompulsorymotorinsurancefee;
            $accountingdetail->incasefinacecompulsorymotorinsurancefee = 0;
        }
        if ($tempModel->compulsorymotorinsurancefeepaidseparatelydate != null)
            $accountingdetail->compulsorymotorinsurancefeepaidseparatelydate = date('d-m-Y', strtotime($tempModel->compulsorymotorinsurancefeepaidseparatelydate));
        else
            $accountingdetail->compulsorymotorinsurancefeepaidseparatelydate = null;
        $accountingdetail->compulsorymotorinsurancepremiumnet = $tempModel->compulsorymotorinsurancepremiumnet;
        $accountingdetail->compulsorymotorinsurancepremiumcom = $tempModel->compulsorymotorinsurancepremiumcom;
        $accountingdetail->compulsorymotorinsurancefeepaidseparatelytotal = $tempModel->compulsorymotorinsurancefeepaidseparatelytotal;

        if ($tempModel->compulsorymotorinsurancefeereceiptcondition == 0 || $tempModel->compulsorymotorinsurancefeereceiptcondition == 1) {
            $accountingdetail->hascompulsorymotorinsurancefee = 1;
            if ($tempModel->compulsorymotorinsurancefeereceiptcondition == 0) {
                $accountingdetail->note2compulsorymotorinsurancefeewhtax = 0;
                $accountingdetail->prb = 0;
            }
        }
        else
            $accountingdetail->hascompulsorymotorinsurancefee = 0;

        $note2totalwhtax = $accountingdetail->note2insurancefeewhtax + $accountingdetail->note2compulsorymotorinsurancefeewhtax + $accountingdetail->note2subsidisewhtax;
        $accountingdetail->note2totalwhtax = $note2totalwhtax;

        $accountingdetail->cashpledgeredlabelreceiptbookno = $tempModel->cashpledgeredlabelreceiptbookno;
        $accountingdetail->cashpledgeredlabelreceiptno = $tempModel->cashpledgeredlabelreceiptno;
        if ($tempModel->cashpledgeredlabelreceiptdate != null)
            $accountingdetail->cashpledgeredlabelreceiptdate = date('d-m-Y', strtotime($tempModel->cashpledgeredlabelreceiptdate));
        else $accountingdetail->cashpledgeredlabelreceiptdate = null;
        $accountingdetail->cashpledgereceiptbookno = $tempModel->cashpledgereceiptbookno;
        $accountingdetail->cashpledgereceiptno = $tempModel->cashpledgereceiptno;
        $accountingdetail->cashpledgereceiptdate = date('d-m-Y', strtotime($tempModel->cashpledgereceiptdate));
        $accountingdetail->oldcarcomamount = $tempModel->oldcarcomamount;
        if ($tempModel->oldcarcomdate != null)
            $accountingdetail->oldcarcomdate = date('d-m-Y', strtotime($tempModel->oldcarcomdate));
        else $accountingdetail->oldcarcomdate = null;
        $accountingdetail->adj = $tempModel->adj;
        $accountingdetail->incasefinacecomfinamount = $tempModel->incasefinacecomfinamount;
        $accountingdetail->incasefinacecomfinvat = $tempModel->incasefinacecomfinvat;
        $accountingdetail->incasefinacecomfinamountwithvat = $tempModel->incasefinacecomfinamountwithvat;
        $accountingdetail->incasefinacecomfinwhtax = $tempModel->incasefinacecomfinwhtax;
        $accountingdetail->incasefinacecomfintotal = $tempModel->incasefinacecomfintotal;
        $accountingdetail->systemcalincasefinacecomfinamount = $tempModel->systemcalincasefinacecomfinamount;
        $accountingdetail->systemcalincasefinacecomfinvat = $tempModel->systemcalincasefinacecomfinvat;
        $accountingdetail->systemcalincasefinacecomfinamountwithvat = $tempModel->systemcalincasefinacecomfinamountwithvat;
        $accountingdetail->systemcalincasefinacecomfinwhtax = $tempModel->systemcalincasefinacecomfinwhtax;
        $accountingdetail->systemcalincasefinacecomfintotal = $tempModel->systemcalincasefinacecomfintotal;
        $accountingdetail->receivedcashfromfinace = $tempModel->receivedcashfromfinacenet;
        $accountingdetail->receivedcashfromfinacenet = $tempModel->receivedcashfromfinacenet;
        $accountingdetail->receivedcashfromfinaceshort = $tempModel->receivedcashfromfinacenetshort;
        $accountingdetail->receivedcashfromfinacenetshort = $tempModel->receivedcashfromfinacenetshort;
        $accountingdetail->receivedcashfromfinaceover = $tempModel->receivedcashfromfinacenetover;
        $accountingdetail->receivedcashfromfinacenetover = $tempModel->receivedcashfromfinacenetover;

        $accountingdetail->totalacc1 = $tempModel->totalaccount1;
        $accountingdetail->totalaccount1 = $tempModel->totalaccount1;
        $accountingdetail->totalacc1short = $tempModel->totalaccount1short;
        $accountingdetail->totalaccount1short = $tempModel->totalaccount1short;
        $accountingdetail->totalacc1over = $tempModel->totalaccount1over;
        $accountingdetail->totalaccount1over = $tempModel->totalaccount1over;

        $accountingdetail->totalacc2 = $tempModel->totalaccount2;
        $accountingdetail->totalaccount2 = $tempModel->totalaccount2;
        $accountingdetail->totalacc2short = $tempModel->totalaccount2short;
        $accountingdetail->totalaccount2short = $tempModel->totalaccount2short;
        $accountingdetail->totalacc2over = $tempModel->totalaccount2over;
        $accountingdetail->totalaccount2over = $tempModel->totalaccount2over;

        $accountingdetail->totalaccs = $tempModel->totalaccounts;
        $accountingdetail->totalaccounts = $tempModel->totalaccounts;
        $accountingdetail->totalaccsshort = $tempModel->totalaccountsshort;
        $accountingdetail->totalaccountsshort = $tempModel->totalaccountsshort;
        $accountingdetail->totalaccsover = $tempModel->totalaccountsover;
        $accountingdetail->totalaccountsover = $tempModel->totalaccountsover;

        $finalopenbill = $accountingdetail->openbill;
        if ($accountingdetail->additionalopenbill != null && $accountingdetail->additionalopenbill > 0) {
            $finalopenbill = $finalopenbill + $accountingdetail->additionalopenbill;
        }

        if ($finalopenbill == 0) {
            $accountingdetail->finalopenbill = 0;
            $accountingdetail->vatoffinalopenbill = 0;
            $accountingdetail->finalopenbillwithoutvat = 0;
            $accountingdetail->realsalespricewithoutvat = $accountingdetail->realsalesprice;
        } else {
            $accountingdetail->finalopenbill = $finalopenbill;
            $vat = ($finalopenbill * 7.00) / 107.00;
            $accountingdetail->vatoffinalopenbill = $vat;
            $accountingdetail->finalopenbillwithoutvat = ($finalopenbill - $vat);
            $realsalesprice = $accountingdetail->realsalesprice;
            $accountingdetail->realsalespricewithoutvat = ($realsalesprice - $vat);
        }

        $incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash;
        $tradereceivableaccount1amount = $finalopenbill - $incasefinacereceivedcash;
        $accountingdetail->tradereceivableaccount1amount = $tradereceivableaccount1amount;
        $accountingdetail->ar = $tradereceivableaccount1amount;

        $cash = $tradereceivableaccount1amount - $accountingdetail->ins
            - $accountingdetail->prb
            - $accountingdetail->dc
            + $accountingdetail->adj;
        $accountingdetail->cash = $cash;


        $carpayment = CarPayment::where('id', $tempModel->carpaymentid)->with('carpreemption')->first();
        $carpaymentselectlist = array();
        $carpaymentselectlist[$carpayment->id] = $carpayment->carpreemption->bookno . '/' . $carpayment->carpreemption->no;

        $banks = Bank::orderBy('accountgroup', 'asc')->orderBy('name', 'asc')->get();
        $bankselectlist = array();
        $bankselectlist[null] = 'เลือกบัญชี';
        foreach ($banks as $item) {
            $bankselectlist[$item->id] = $item->name . ' ' . substr($item->accountno, -4);
        }

        $bankselectlist2 = array();
        array_push($bankselectlist2, ':เลือกบัญชี');
        foreach ($banks as $item) {
            array_push($bankselectlist2, $item->id . ':' . $item->name . ' ' . substr($item->accountno, -4));
        }

        $receiveAndPayDatas0 = array();
        $receiveAndPayData0 = SupportRequest::old('receiveAndPayData0');
        if ($receiveAndPayData0 != null && $receiveAndPayData0 != '') {
            $receiveAndPayData0 = json_decode($receiveAndPayData0, true);
            foreach ($receiveAndPayData0 as $data) {
                $obj = (object)array("id" => $data["id"], "date" => $data["date"], "type" => $data["type"], "amount" => $data["amount"], "accountgroup" => $data["accountgroup"], "bankid" => $data["bankid"], "note" => $data["note"]);
                array_push($receiveAndPayDatas0, $obj);
            }
        } else {
            $receiveAndPays0 = AccountingDetailReceiveAndPay::where('accountingdetailid', $id)->where('sectiontype', 0)
                ->get(['id', 'date', 'type', 'amount', 'accountgroup', 'bankid', 'note']);

            foreach ($receiveAndPays0 as $data) {
                $obj = (object)array("id" => $data->id, "date" => $data->date, "type" => $data->type, "amount" => $data->amount, "accountgroup" => $data->accountgroup, "bankid" => $data->bankid, "note" => $data->note);
                array_push($receiveAndPayDatas0, $obj);
            }
        }

        $receiveAndPayDatas1 = array();
        $receiveAndPayData1 = SupportRequest::old('receiveAndPayData1');
        if ($receiveAndPayData1 != null && $receiveAndPayData1 != '') {
            $receiveAndPayData1 = json_decode($receiveAndPayData1, true);
            foreach ($receiveAndPayData1 as $data) {
                $obj = (object)array("id" => $data["id"], "date" => $data["date"], "type" => $data["type"], "amount" => $data["amount"], "accountgroup" => $data["accountgroup"], "bankid" => $data["bankid"], "note" => $data["note"]);
                array_push($receiveAndPayDatas1, $obj);
            }
        } else {
            $receiveAndPays1 = AccountingDetailReceiveAndPay::where('accountingdetailid', $id)->where('sectiontype', 1)
                ->get(['id', 'date', 'type', 'amount', 'accountgroup', 'bankid', 'note']);

            foreach ($receiveAndPays1 as $data) {
                $obj = (object)array("id" => $data->id, "date" => $data->date, "type" => $data->type, "amount" => $data->amount, "accountgroup" => $data->accountgroup, "bankid" => $data->bankid, "note" => $data->note);
                array_push($receiveAndPayDatas1, $obj);
            }
        }

        foreach ($accountingdetail->toArray() as $key => $value) {
            if (!in_array($key, $this->arrNotFormatted)) {
                if (is_numeric($value) && (float)$value != 0.00) {
                    $value = number_format($value, 2, '.', ',');
                } else if ($value == null || $value == '' || (is_numeric($value) && (float)$value == 0.00))
                    $value = '-';

                $accountingdetail->$key = $value;
            }
        }

        return view('accountingdetailform',
            ['oper' => 'view', 'pathPrefix' => '../../', 'accountingdetail' => $accountingdetail,
                'carpaymentselectlist' => $carpaymentselectlist,
                'bankselectlist' => $bankselectlist,
                'bankselectlist2' => implode(";", $bankselectlist2),
                'receiveAndPayDatas0' => $receiveAndPayDatas0,
                'receiveAndPayDatas1' => $receiveAndPayDatas1]);
    }
}