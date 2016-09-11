<?php

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Models\CarPayment;
use App\Models\RedLabel;
use App\Repositories\ReturnCashPledgeRedLabelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ReturnCashPledgeRedLabelController extends Controller
{

    protected $menuPermissionName = "คืนเงินมัดจำป้ายแดง";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        if (Auth::user()->isadmin) {
            $redlabels = RedLabel::get(['id', 'no']);
        } else {
            $redlabels = RedLabel::where('provinceid', Auth::user()->provinceid)
                ->get(['id', 'no']);
        }
        $redlabelselectlist = array();
        foreach ($redlabels as $item) {
            array_push($redlabelselectlist, $item->id . ':' . $item->no);
        }

        if (Auth::user()->isadmin) {
            $carpayments = CarPayment::whereHas('carpreemption', function ($q) {
                $q->whereHas('redlabelhistories', function ($q2) {
                    $q2->whereNotNull('returndate');
                });
            })
                ->with('carpreemption.buyerCustomer', 'car')
                ->get();
        } else {
            $carpayments = CarPayment::where('provinceid', Auth::user()->provinceid)
                ->whereHas('carpreemption', function ($q) {
                    $q->whereHas('redlabelhistories', function ($q2) {
                        $q2->whereNotNull('returndate');
                    });
                })
                ->with('carpreemption.buyerCustomer', 'car')
                ->get();
        }
        $carselectlist = array();
        $customerselectlist = array();
        $cashpledgeselectlist = array();
        foreach ($carpayments as $item) {
            array_push($carselectlist, $item->carpreemption->id . ':' . $item->car->engineno . '/' . $item->car->chassisno);
            array_push($customerselectlist, $item->carpreemption->id . ':' . $item->carpreemption->buyerCustomer->title . $item->carpreemption->buyerCustomer->firstname . ' ' . $item->carpreemption->buyerCustomer->lastname);
            array_push($cashpledgeselectlist, $item->carpreemption->id . ':' . $item->carpreemption->cashpledgeredlabel);
        }

        return view('returncashpledgeredlabel',
            ['redlabelselectlist' => implode(";", $redlabelselectlist),
                'carselectlist' => implode(";", $carselectlist),
                'customerselectlist' => implode(";", $customerselectlist),
                'cashpledgeselectlist' => implode(";", $cashpledgeselectlist)]);
    }

    public function read()
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new ReturnCashPledgeRedLabelRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new ReturnCashPledgeRedLabelRepository(), $request);
    }
}