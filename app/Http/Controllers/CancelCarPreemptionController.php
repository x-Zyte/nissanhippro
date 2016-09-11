<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Models\CancelCarPreemption;
use App\Models\CarModel;
use App\Models\CarPreemption;
use App\Models\CarSubModel;
use App\Models\Customer;
use App\Models\Employee;
use App\Repositories\CancelCarPreemptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request as SupportRequest;

class CancelCarPreemptionController extends Controller {

    protected $menuPermissionName = "ยกเลิกการจอง";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carpreemptions = CarPreemption::has('cancelCarPreemption')->orderBy('bookno', 'asc')->orderBy('no', 'asc')
            ->get(['id', 'bookno', 'no']);
        $carpreemptionselectlist = array();
        foreach($carpreemptions as $item){
            array_push($carpreemptionselectlist,$item->id.':'.$item->bookno.'/'.$item->no);
        }

        $approversemployees = Employee::has('approveCancelCarPreemptions')->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')->get(['id', 'title', 'firstname', 'lastname']);
        $approversemployeeselectlist = array();
        foreach($approversemployees as $item){
            array_push($approversemployeeselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        return view('cancelcarpreemption',
            ['carpreemptionselectlist' => implode(";",$carpreemptionselectlist),
                'approversemployeeselectlist' => implode(";",$approversemployeeselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CancelCarPreemptionRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CancelCarPreemptionRepository(), $request);
    }

    public function newcancelcarpreemption()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        if(Auth::user()->isadmin){
            $carpreemptions = CarPreemption::where('status',0)
                ->doesntHave('carPayment')
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no']);
        }
        else{
            $carpreemptions = CarPreemption::where('provinceid', Auth::user()->provinceid)
                ->where('status',0)
                ->doesntHave('carPayment')
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
        if($carpreemptionid != null && $carpreemptionid != '') {
            $carpreemption = CarPreemption::find($carpreemptionid);

            $model = Customer::find($carpreemption->buyercustomerid);
            $customer = $model->title.' '.$model->firstname.' '.$model->lastname;

            $model = CarModel::find($carpreemption->carmodelid);
            $model2 = CarSubModel::find($carpreemption->carsubmodelid);
            $carmodel = $model->name.'/'.$model2->name;

            $date = date('d-m-Y', strtotime($carpreemption->date));

            $cashpledge = $carpreemption->cashpledge;

            $model = Employee::find($carpreemption->salesmanemployeeid);
            $salesmanemployee = $model->title.' '.$model->firstname.' '.$model->lastname;
        }
        else{
            $customer = null;
            $carmodel = null;
            $date = null;
            $cashpledge = null;
            $salesmanemployee = null;
        }

        if(Auth::user()->isadmin){
            $toemployees = Employee::where('departmentid', 5)
                //->orWhere(function ($query) {
                    //$query->where('departmentid', 6)
                        //->where('teamid', 1);
                //})
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $toemployees = Employee::where('provinceid', Auth::user()->provinceid)
                ->where('departmentid', 5)
                //->where(function ($query) {
                    //$query->where('departmentid', 5)
                        //->orWhere(function ($query) {
                            //$query->where('departmentid', 6)
                                //->where('teamid', 1);
                        //});
                //})
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $toemployeeselectlist = array();
        $toemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($toemployees as $item){
            $toemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $accountandfinanceemployees = Employee::where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $accountandfinanceemployees = Employee::where('provinceid', Auth::user()->provinceid)
                ->where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $accountandfinanceemployeeselectlist = array();
        $accountandfinanceemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($accountandfinanceemployees as $item){
            $accountandfinanceemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        $cancelcarpreemption = new CancelCarPreemption;

        return view('cancelcarpreemptionform',
            ['oper' => 'new','pathPrefix' => '../','cancelcarpreemption' => $cancelcarpreemption,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'toemployeeselectlist' => $toemployeeselectlist,
                'accountandfinanceemployeeselectlist' => $accountandfinanceemployeeselectlist,
                'customer' => $customer,
                'carmodel' => $carmodel,
                'date' => $date,
                'cashpledge' => $cashpledge,
                'salesmanemployee' => $salesmanemployee
            ]);
    }

    public function save(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $this->validate($request, [
                'carpreemptionid' => 'required',
                'toemployeeid' => 'required',
                'cancelreasontype' => 'required',
                'cancelreasondetails' => 'required_if:cancelreasontype,2',
            'refundamount' => 'required',
            'confiscateamount' => 'required',
                'salesmanemployeedate' => 'required',
                'accountemployeeid' => 'required',
                'accountemployeedate' => 'required',
                'financeemployeeid' => 'required',
                'financeemployeedate' => 'required',
                'approversemployeeid' => 'required',
                'approversemployeedate' => 'required'
            ],
            [
                'carpreemptionid.required' => 'กรุณาเลือกการจอง',
                'toemployeeid.required' => 'กรุณาเลือกคนที่จะเรียนให้ทราบ',
                'cancelreasontype.required' => 'ยกเลิกเนื่องจาก จำเป็นต้องเลือก',
                'cancelreasondetails.required_if' => 'อื่นๆ จำเป็นต้องกรอก',
                'refundamount.required' => 'จำนวนคืนเงิน จำเป็นต้องกรอก',
                'confiscateamount.required' => 'จำนวนไม่คืนเงิน จำเป็นต้องกรอก',
                'salesmanemployeedate.required' => 'วันที่ ของพนักงานขาย จำเป็นต้องเลือก',
                'accountemployeeid.required' => 'พนักงานบัญชี จำเป็นต้องเลือก',
                'accountemployeedate.required' => 'วันที่ ของพนักงานบัญชี จำเป็นต้องเลือก',
                'financeemployeeid.required' => 'พนักงานการเงิน จำเป็นต้องเลือก',
                'financeemployeedate.required' => 'วันที่ ของพนักงานการเงิน จำเป็นต้องเลือก',
                'approversemployeeid.required' => 'ผู้อนุมัติ จำเป็นต้องเลือก',
                'approversemployeedate.required' => 'วันที่ ของผู้อนุมัติ จำเป็นต้องเลือก'
            ]
        );

        $input = $request->all();

        if ($request->has('id')){
            $model = CancelCarPreemption::find($input['id']);
            if($model == null)
                return "ขออภัย!! ไม่พบข้อมูลที่จะทำการแก้ไขในระบบ เนื่องจากอาจถูกลบไปแล้ว";
        }
        else
            $model = new CancelCarPreemption;

        $model->carpreemptionid = $input['carpreemptionid'];
        $model->toemployeeid = $input['toemployeeid'];
        $model->cancelreasontype = $input['cancelreasontype'];

        if($model->cancelreasontype == 2)
            $model->cancelreasondetails = $input['cancelreasondetails'];
        else
            $model->cancelreasondetails = null;

        $model->remark = $input['remark'];

        $model->refundamount = $input['refundamount'];
        if ($input['refunddate'] != null && $input['refunddate'] != '')
            $model->refunddate = date('Y-m-d', strtotime($input['refunddate']));
        else
            $model->refunddate = null;
        $model->refunddocno = $input['refunddocno'];

        $model->confiscateamount = $input['confiscateamount'];
        if ($input['confiscatedate'] != null && $input['confiscatedate'] != '')
            $model->confiscatedate = date('Y-m-d', strtotime($input['confiscatedate']));
        else
            $model->confiscatedate = null;
        $model->confiscatedocno = $input['confiscatedocno'];

        $model->salesmanemployeedate = date('Y-m-d', strtotime($input['salesmanemployeedate']));
        $model->accountemployeeid = $input['accountemployeeid'];
        $model->accountemployeedate = date('Y-m-d', strtotime($input['accountemployeedate']));
        $model->financeemployeeid = $input['financeemployeeid'];
        $model->financeemployeedate = date('Y-m-d', strtotime($input['financeemployeedate']));
        $model->approversemployeeid = $input['approversemployeeid'];
        $model->approversemployeedate = date('Y-m-d', strtotime($input['approversemployeedate']));

        if($model->save()) {
            return redirect()->action('CancelCarPreemptionController@edit',['id' => $model->id]);
        }
        else{
            //hack returning error
            $this->validate($request, ['carpreemptionid' => 'alpha'], ['carpreemptionid.alpha' => 'ไม่สามารถทำการบันทึกข้อมูลการยกเลิกการจองได้ กรุณาติดต่อผู้ดูแลระบบ!!']);
        }
    }

    public function edit($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CancelCarPreemption::find($id);

        $carpreemption = CarPreemption::find($model->carpreemptionid);

        $carpreemptionselectlist = array();
        $carpreemptionselectlist[$carpreemption->id] = $carpreemption->bookno.'/'.$carpreemption->no;

        $model->bookno = $carpreemption->bookno;
        $model->no = $carpreemption->no;

        $customer = Customer::find($carpreemption->buyercustomerid);
        $model->customer = $customer->title.' '.$customer->firstname.' '.$customer->lastname;

        $carmodel = CarModel::find($carpreemption->carmodelid);
        $carsubmodel = CarSubModel::find($carpreemption->carsubmodelid);
        $model->carmodel = $carmodel->name.'/'.$carsubmodel->name;

        $model->carpreemptiondate = date('d-m-Y', strtotime($carpreemption->date));
        $model->cashpledge = $carpreemption->cashpledge;

        if ($model->refunddate != null && $model->refunddate != '')
            $model->refunddate = date('d-m-Y', strtotime($model->refunddate));

        if ($model->confiscatedate != null && $model->confiscatedate != '')
            $model->confiscatedate = date('d-m-Y', strtotime($model->confiscatedate));

        $salesmanemployee = Employee::find($carpreemption->salesmanemployeeid);
        $model->salesmanemployee = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;

        if(Auth::user()->isadmin){
            $toemployees = Employee::where('departmentid', 5)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $toemployees = Employee::where('provinceid', Auth::user()->provinceid)
                ->where('departmentid', 5)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $toemployeeselectlist = array();
        $toemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($toemployees as $item){
            $toemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if(Auth::user()->isadmin){
            $accountandfinanceemployees = Employee::where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        else{
            $accountandfinanceemployees = Employee::where('provinceid', Auth::user()->provinceid)
                ->where('departmentid', 4)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id','title','firstname','lastname']);
        }
        $accountandfinanceemployeeselectlist = array();
        $accountandfinanceemployeeselectlist[null] = 'เลือกพนักงาน';
        foreach($accountandfinanceemployees as $item){
            $accountandfinanceemployeeselectlist[$item->id] = $item->title.' '.$item->firstname.' '.$item->lastname;
        }

        if($model->salesmanemployeedate != null && $model->salesmanemployeedate != '')
            $model->salesmanemployeedate = date('d-m-Y', strtotime($model->salesmanemployeedate));
        if($model->accountemployeedate != null && $model->accountemployeedate != '')
            $model->accountemployeedate = date('d-m-Y', strtotime($model->accountemployeedate));
        if($model->financeemployeedate != null && $model->financeemployeedate != '')
            $model->financeemployeedate = date('d-m-Y', strtotime($model->financeemployeedate));
        if($model->approversemployeedate != null && $model->approversemployeedate != '')
            $model->approversemployeedate = date('d-m-Y', strtotime($model->approversemployeedate));

        return view('cancelcarpreemptionform',
            ['oper' => 'edit','pathPrefix' => '../../','cancelcarpreemption' => $model,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'toemployeeselectlist' => $toemployeeselectlist,
                'accountandfinanceemployeeselectlist' => $accountandfinanceemployeeselectlist]);
    }

    public function view($id)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $model = CancelCarPreemption::find($id);

        $carpreemption = CarPreemption::find($model->carpreemptionid);

        $carpreemptionselectlist = array();
        $carpreemptionselectlist[$carpreemption->id] = $carpreemption->bookno.'/'.$carpreemption->no;

        $model->bookno = $carpreemption->bookno;
        $model->no = $carpreemption->no;

        $customer = Customer::find($carpreemption->buyercustomerid);
        $model->customer = $customer->title.' '.$customer->firstname.' '.$customer->lastname;

        $carmodel = CarModel::find($carpreemption->carmodelid);
        $carsubmodel = CarSubModel::find($carpreemption->carsubmodelid);
        $model->carmodel = $carmodel->name.'/'.$carsubmodel->name;

        $model->carpreemptiondate = date('d-m-Y', strtotime($carpreemption->date));
        $model->cashpledge = $carpreemption->cashpledge;

        if ($model->refunddate != null && $model->refunddate != '')
            $model->refunddate = date('d-m-Y', strtotime($model->refunddate));

        if ($model->confiscatedate != null && $model->confiscatedate != '')
            $model->confiscatedate = date('d-m-Y', strtotime($model->confiscatedate));

        $salesmanemployee = Employee::find($carpreemption->salesmanemployeeid);
        $model->salesmanemployee = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;

        $toemployeeselectlist = array();
        $item = Employee::find($model->toemployeeid);
        $toemployeeselectlist[$item->id] = $item->title . ' ' . $item->firstname . ' ' . $item->lastname;
        $item = Employee::find($model->approversemployeeid);
        $toemployeeselectlist[$item->id] = $item->title . ' ' . $item->firstname . ' ' . $item->lastname;

        $accountandfinanceemployeeselectlist = array();
        $item = Employee::find($model->accountemployeeid);
        $accountandfinanceemployeeselectlist[$item->id] = $item->title . ' ' . $item->firstname . ' ' . $item->lastname;
        $item = Employee::find($model->financeemployeeid);
        $accountandfinanceemployeeselectlist[$item->id] = $item->title . ' ' . $item->firstname . ' ' . $item->lastname;

        if($model->salesmanemployeedate != null && $model->salesmanemployeedate != '')
            $model->salesmanemployeedate = date('d-m-Y', strtotime($model->salesmanemployeedate));
        if($model->accountemployeedate != null && $model->accountemployeedate != '')
            $model->accountemployeedate = date('d-m-Y', strtotime($model->accountemployeedate));
        if($model->financeemployeedate != null && $model->financeemployeedate != '')
            $model->financeemployeedate = date('d-m-Y', strtotime($model->financeemployeedate));
        if($model->approversemployeedate != null && $model->approversemployeedate != '')
            $model->approversemployeedate = date('d-m-Y', strtotime($model->approversemployeedate));

        return view('cancelcarpreemptionform',
            ['oper' => 'view','pathPrefix' => '../../','cancelcarpreemption' => $model,
                'carpreemptionselectlist' => $carpreemptionselectlist,
                'toemployeeselectlist' => $toemployeeselectlist,
                'accountandfinanceemployeeselectlist' => $accountandfinanceemployeeselectlist]);
    }
}