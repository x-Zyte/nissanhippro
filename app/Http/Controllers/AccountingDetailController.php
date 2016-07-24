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

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carpayments = CarPayment::with('carpreemption')->get();
        $carpaymentselectlist = array();
        foreach ($carpayments as $item) {
            array_push($carpaymentselectlist, $item->id . ':' . $item->carpreemption->bookno . '/' . $item->carpreemption->no);
        }

        return view('accountingdetail', ['carpaymentselectlist' => implode(";", $carpaymentselectlist)]);
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
        $carpaymentselectlist = array();
        $carpaymentselectlist[null] = 'เลือกการจอง';
        foreach ($carpayments as $item) {
            $carpaymentselectlist[$item->id] = $item->carpreemption->bookno . '/' . $item->carpreemption->no;
        }

        $banks = Bank::orderBy('name', 'asc')->get();
        $bankselectlist = array();
        $bankselectlist[null] = 'เลือก Bank';
        foreach ($banks as $item) {
            $bankselectlist[$item->id] = $item->accountname . ' (' . $item->name . ')';
        }

        $accountingdetail = new AccountingDetail();
        $carpaymentid = SupportRequest::old('carpaymentid');
        if ($carpaymentid != null && $carpaymentid > 0) {
            $accountingdetail = (new CarPaymentController)->getforaccountingdetailbyid($carpaymentid);
            $additionalopenbill = SupportRequest::old('additionalopenbill');

            $finalopenbill = $accountingdetail->openbill == '-' ? 0 : $accountingdetail->openbill;
            if ($additionalopenbill != null && $additionalopenbill > 0) {
                $finalopenbill = $finalopenbill + $additionalopenbill;
            }

            if ($finalopenbill == 0) {
                $accountingdetail->finalopenbill = '-';
                $accountingdetail->vatoffinalopenbill = '-';
                $accountingdetail->finalopenbillwithoutvat = '-';
                $accountingdetail->realsalespricewithoutvat = $accountingdetail->realsalesprice;
            } else {
                $accountingdetail->finalopenbill = number_format($finalopenbill, 2, '.', '');
                $vat = $finalopenbill * 0.07;
                $accountingdetail->vatoffinalopenbill = number_format($vat, 2, '.', '');
                $accountingdetail->finalopenbillwithoutvat = number_format(($finalopenbill - $vat), 2, '.', '');
                $accountingdetail->realsalespricewithoutvat = $accountingdetail->realsalesprice;
            }

            $incasefinacereceivedcash = $accountingdetail->incasefinacereceivedcash == '-' ? 0 : $accountingdetail->incasefinacereceivedcash;
            $tradereceivableaccount1amount = $finalopenbill - $incasefinacereceivedcash;
            $accountingdetail->tradereceivableaccount1amount = $tradereceivableaccount1amount == 0 ? '-' : number_format($tradereceivableaccount1amount, 2, '.', '');
            $accountingdetail->ar = $tradereceivableaccount1amount == 0 ? '-' : number_format($tradereceivableaccount1amount, 2, '.', '');

            $adj = SupportRequest::old('adj');
            $cash = $tradereceivableaccount1amount - ($accountingdetail->ins == '-' ? 0 : $accountingdetail->ins)
                - ($accountingdetail->prb == '-' ? 0 : $accountingdetail->prb)
                - ($accountingdetail->dc == '-' ? 0 : $accountingdetail->dc)
                + ($adj == null ? 0 : $adj);
            $accountingdetail->cash = $cash == 0 ? '-' : number_format($cash, 2, '.', '');
        }

        return view('accountingdetailform',
            ['oper' => 'new', 'pathPrefix' => '../', 'accountingdetail' => $accountingdetail,
                'carpaymentselectlist' => $carpaymentselectlist,
                'bankselectlist' => $bankselectlist]);
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
            'cashpledgereceiptbookno' => 'required',
            'cashpledgereceiptno' => 'required',
            'cashpledgereceiptdate' => 'required',
            'incasefinacecomfinamount' => 'required_if:purchasetype,1',
            'incasefinacecomfinvat' => 'required_if:purchasetype,1',
            'incasefinacecomfinamountwithvat' => 'required_if:purchasetype,1',
            'incasefinacecomfinwhtax' => 'required_if:purchasetype,1',
            'incasefinacecomfintotal' => 'required_if:purchasetype,1',
            'receivedcashfromfinacedate' => 'required_if:purchasetype,1',
            'receivedcashfromfinacebankid' => 'required_if:purchasetype,1',
            'oldcarcomamount' => 'required',
            'adj' => 'required'
        ],
            [
                'carpaymentid.required' => 'กรุณาเลือกการจอง',
                'invoiceno.required' => 'เลขที่ใบกำกับ จำเป็นต้องกรอก',
                'date.required' => 'วันที่ จำเป็นต้องกรอก',
                'additionalopenbill.required' => 'ราคาเปิดบิลเพิ่มเติม จำเป็นต้องกรอก',
                'insurancefeereceiptcondition.required_if' => 'เงื่อนไข ค่าเบี้ย ป.1 จำเป็นต้องเลือก',
                'compulsorymotorinsurancefeereceiptcondition.required_if' => 'เงื่อนไข ค่า พ.ร.บ. จำเป็นต้องเลือก',
                'cashpledgereceiptbookno.required' => 'ใบรับเงิน เล่มที่ จำเป็นต้องกรอก',
                'cashpledgereceiptno.required' => 'ใบรับเงิน เลขที่ จำเป็นต้องกรอก',
                'cashpledgereceiptdate.required' => 'ใบรับเงิน วันที่ จำเป็นต้องกรอก',
                'incasefinacecomfinamount.required_if' => 'Com Fin... จำเป็นต้องกรอก',
                'incasefinacecomfinvat.required_if' => 'Com Fin Vat... จำเป็นต้องกรอก',
                'incasefinacecomfinamountwithvat.required_if' => 'Com Fin Total... จำเป็นต้องกรอก',
                'incasefinacecomfinwhtax.required_if' => 'Com Fin W/H ถูกหัก จำเป็นต้องกรอก',
                'incasefinacecomfintotal.required_if' => 'Com Fin Net... จำเป็นต้องกรอก',
                'receivedcashfromfinacedate.required_if' => 'วันที่ ที่รับเงินจากไฟแนนซ์ จำเป็นต้องกรอก',
                'receivedcashfromfinacebankid.required_if' => 'Bank ที่รับเงินจากไฟแนนซ์ จำเป็นต้องเลือก',
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

        if ($input['hasinsurancefee'] == 1)
            $model->insurancefeereceiptcondition = $input['insurancefeereceiptcondition'];
        else
            $model->insurancefeereceiptcondition = null;

        if ($input['hascompulsorymotorinsurancefee'] == 1)
            $model->compulsorymotorinsurancefeereceiptcondition = $input['compulsorymotorinsurancefeereceiptcondition'];
        else
            $model->compulsorymotorinsurancefeereceiptcondition = null;

        $model->cashpledgereceiptbookno = $input['cashpledgereceiptbookno'];
        $model->cashpledgereceiptno = $input['cashpledgereceiptno'];
        $model->cashpledgereceiptdate = date('Y-m-d', strtotime($input['cashpledgereceiptdate']));

        if ($input['purchasetype'] == 1) {
            $model->incasefinacecomfinamount = $input['incasefinacecomfinamount'];
            $model->incasefinacecomfinvat = $input['incasefinacecomfinvat'];
            $model->incasefinacecomfinamountwithvat = $input['incasefinacecomfinamountwithvat'];
            $model->incasefinacecomfinwhtax = $input['incasefinacecomfinwhtax'];
            $model->incasefinacecomfintotal = $input['incasefinacecomfintotal'];
            $model->systemcalincasefinacecomfinamount = $input['incasefinacecomfinamount'];
            $model->systemcalincasefinacecomfinvat = $input['incasefinacecomfinvat'];
            $model->systemcalincasefinacecomfinamountwithvat = $input['incasefinacecomfinamountwithvat'];
            $model->systemcalincasefinacecomfinwhtax = $input['incasefinacecomfinwhtax'];
            $model->systemcalincasefinacecomfintotal = $input['incasefinacecomfintotal'];
            $model->receivedcashfromfinacedate = date('Y-m-d', strtotime($input['receivedcashfromfinacedate']));
            $model->receivedcashfromfinacebankid = $input['receivedcashfromfinacebankid'];
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
            $model->receivedcashfromfinacedate = null;
            $model->receivedcashfromfinacebankid = null;
        }

        $model->oldcarcomamount = $input['oldcarcomamount'];
        $model->adj = $input['adj'];
    }

    public function edit($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);


    }

    public function view($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

    }
}