<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Models\CarPreemption;
use App\Models\Customer;
use App\Models\Employee;
use App\Repositories\RedlabelhistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use stdClass;


class RedlabelhistoryController extends Controller {

    protected $menuPermissionName = "ป้ายแดง";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = Input::all();
        if(array_key_exists("filters", $input)){
            if($input['filters'] == null){
                $input['filters'] = json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"redlabelid","op"=>"eq","data"=>$input['redlabelid']))));
            }
            else {
                $filters = json_decode(str_replace('\'', '"', $input['filters']), true);
                array_push($filters['rules'], array("field" => "redlabelid", "op" => "eq", "data" => $input['redlabelid']));
                $input['filters'] = json_encode($filters);
            }
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"redlabelid","op"=>"eq","data"=>$input['redlabelid'])))));
        }
        GridEncoder::encodeRequestedData(new RedlabelhistoryRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new RedlabelhistoryRepository(), $request);
    }

    public function readCarPreemptionSelectlist($carpreemptionid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        if(Auth::user()->isadmin){
            $carpreemptions = CarPreemption::where('id',$carpreemptionid)
                ->orWhere(function ($query) {
                    $query->whereDoesntHave('redlabelhistories', function($q) {
                        $q->whereNull('returndate');
                        })
                        ->where('status',0)
                        ->where('cashpledgeredlabel','!=',0);
                })
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no','buyercustomerid','salesmanemployeeid']);
        }
        else{
            $carpreemptions = CarPreemption::where('provinceid', Auth::user()->provinceid)
                ->where(function ($query) use($carpreemptionid){
                    $query->where('id',$carpreemptionid)
                        ->orWhere(function ($query) {
                            $query->whereDoesntHave('redlabelhistories', function($q) {
                                $q->whereNull('returndate');
                            })
                            ->where('status',0)
                            ->where('cashpledgeredlabel','!=',0);
                        });
                })
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no','buyercustomerid','salesmanemployeeid']);
        }
        $carpreemptionselectlist = array();
        foreach($carpreemptions as $item){
            $buyercustomer = Customer::find($item->buyercustomerid);
            $buyercustomername = $buyercustomer->title.' '.$buyercustomer->firstname.' '.$buyercustomer->lastname;
            $salesmanemployee = Employee::find($item->salesmanemployeeid);
            $salesmanemployeename = $salesmanemployee->title.' '.$salesmanemployee->firstname.' '.$salesmanemployee->lastname;
            $text = $item->bookno.'/'.$item->no." - ลูกค้า: ".$buyercustomername." - เซล: ".$salesmanemployeename;
            array_push($carpreemptionselectlist,array("id"=>$item->id, "text"=>$text));
        }

        return $carpreemptionselectlist;
    }

    public function readCarPreemptionSelectlistForDisplayInGrid($redlabelid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        if(Auth::user()->isadmin){
            $carpreemptions = CarPreemption::whereHas('redlabelhistories', function($q) use($redlabelid) {
                    $q->where('redlabelid',$redlabelid);
                })
                ->orderBy('bookno', 'asc')
                ->orderBy('no', 'asc')
                ->get(['id','bookno','no','buyercustomerid','salesmanemployeeid']);
        }
        else{
            $carpreemptions = CarPreemption::where('provinceid', Auth::user()->provinceid)
                ->whereHas('redlabelhistories', function($q) use($redlabelid) {
                    $q->where('redlabelid',$redlabelid);
                })
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

        return ['carpreemptionselectlist'=>implode(";",$carpreemptionselectlist)];
    }
}