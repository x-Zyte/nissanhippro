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
            'receivedcashfromfinacedate' => 'required_if:purchasetype,1',
            'receivedcashfromfinacebankid' => 'required_if:purchasetype,1'
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
                'receivedcashfromfinacedate.required_if' => 'วันที่ ที่รับเงินจากไฟแนนซ์ จำเป็นต้องกรอก',
                'receivedcashfromfinacebankid.required_if' => 'Bank ที่รับเงินจากไฟแนนซ์ จำเป็นต้องเลือก'
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
            $model->receivedcashfromfinacedate = date('Y-m-d', strtotime($input['receivedcashfromfinacedate']));
            $model->receivedcashfromfinacebankid = $input['receivedcashfromfinacebankid'];
        } else {
            $model->receivedcashfromfinacedate = null;
            $model->receivedcashfromfinacebankid = null;
        }
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