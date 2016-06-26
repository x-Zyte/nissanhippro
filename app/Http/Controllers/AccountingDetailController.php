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
use App\Models\CarPayment;
use App\Repositories\AccountingDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AccountingDetailController extends Controller
{

    protected $menuPermissionName = "รายละเอียดบันทึกบัญชี";

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

        $accountingdetail = new AccountingDetail();

        return view('accountingdetailform',
            ['oper' => 'new', 'pathPrefix' => '../', 'accountingdetail' => $accountingdetail,
                'carpaymentselectlist' => $carpaymentselectlist]);
    }

    public function save(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);


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