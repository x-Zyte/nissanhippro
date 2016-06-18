<?php

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Models\Branch;
use App\Models\Car;
use App\Models\CarPreemption;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\RedLabel;
use App\Models\Redlabelhistory;
use App\Models\SystemDatas\Province;
use App\Repositories\RedLabelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RedLabelController extends Controller {

    protected $menuPermissionName = "ป้ายแดง";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $provinces = Province::whereHas('branchs', function($q){
            $q->where('isheadquarter', true);
        })->orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        array_push($provinceselectlist,':เลือกจังหวัด');
        foreach($provinces as $item){
            array_push($provinceselectlist,$item->id.':'.$item->name);
        }

        if(Auth::user()->isadmin){
            $customers = Customer::has('redLabels')
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id', 'title', 'firstname', 'lastname']);
        }
        else {
            $customers = Customer::where('provinceid', Auth::user()->provinceid)
                ->has('redLabels')
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get(['id', 'title', 'firstname', 'lastname']);
        }
        $customerselectlist = array();
        foreach($customers as $item){
            array_push($customerselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        if(Auth::user()->isadmin){
            $cars = Car::has('redLabel')
                ->orderBy('chassisno', 'asc')
                ->orderBy('engineno', 'asc')
                ->get(['id','chassisno','engineno']);
        }
        else{
            $cars = Car::where('provinceid', Auth::user()->provinceid)
                ->has('redLabel')
                ->orderBy('chassisno', 'asc')
                ->orderBy('engineno', 'asc')
                ->get(['id','chassisno','engineno']);
        }
        $carselectlist = array();
        array_push($carselectlist,':เลือกรถ');
        foreach($cars as $item){
            array_push($carselectlist,$item->id.':'.$item->chassisno.'/'.$item->engineno);
        }

        if(Auth::user()->isadmin){
            $carpreemptions = CarPreemption::has('redlabelhistories')
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no','buyercustomerid','salesmanemployeeid']);
        }
        else{
            $carpreemptions = CarPreemption::where('provinceid', Auth::user()->provinceid)
                ->has('redlabelhistories')
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no','buyercustomerid','salesmanemployeeid']);
        }
        $carpreemptionselectlist = array();
        array_push($carpreemptionselectlist,':เลือกการจอง');
        foreach($carpreemptions as $item){
            $buyercustomer = Customer::find($item->buyercustomerid);
            $buyercustomername = $buyercustomer->title.' '.$buyercustomer->firstname.' '.$buyercustomer->lastname;
            $salesmanemployee = Employee::find($item->salesmanemployeeid);
            $salesmanemployeename = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;

            array_push($carpreemptionselectlist,$item->id.':'.str_pad($item->bookno.'/'.$item->no,strlen($item->bookno.'/'.$item->no)+15," ").str_pad($buyercustomername,strlen($buyercustomername)+15," ").$salesmanemployeename);
        }

        $defaultProvince = '';
        if(Auth::user()->isadmin == false){
            $defaultProvince = Auth::user()->provinceid;
        }

        return view('redlabel',
            ['provinceselectlist' => implode(";",$provinceselectlist),
                'customerselectlist' => implode(";",$customerselectlist),
                'carselectlist' => implode(";",$carselectlist),
                'carpreemptionselectlist' => implode(";",$carpreemptionselectlist),
                'defaultProvince'=>$defaultProvince]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new RedLabelRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new RedLabelRepository(), $request);
    }

    public function checkbusy($id)
    {
        $redlabel = Redlabel::find($id);
        if($redlabel->customerid != null && $redlabel->customerid != '')
            return 1;
        else
            return 0;
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $customers = Customer::whereHas('buyerCarPreemptions.redlabelhistories', function($q){
                $q->whereNull('returndate');
            })
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get(['id', 'title','firstname','lastname']);
        $customerselectlist = array();
        foreach($customers as $item){
            array_push($customerselectlist,$item->id.':'.$item->title.' '.$item->firstname.' '.$item->lastname);
        }

        return ['customerselectlist'=>implode(";",$customerselectlist)];
    }
}