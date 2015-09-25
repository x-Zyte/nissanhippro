<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Car;
use App\Models\SystemDatas\Amphur;
use App\Models\SystemDatas\District;
use App\Models\SystemDatas\Province;
use App\Repositories\BranchRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BranchController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $provinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        array_push($provinceselectlist,':เลือกจังหวัด');
        foreach($provinces as $item){
            array_push($provinceselectlist,$item->id.':'.$item->name);
        }

        $amphurids = Branch::distinct()->lists('amphurid');
        $amphurs = Amphur::whereIn('id', $amphurids)->orderBy('name', 'asc')->get(['id', 'name']);
        $amphurselectlist = array();
        array_push($amphurselectlist,':เลือกเขต/อำเภอ');
        foreach($amphurs as $item){
            array_push($amphurselectlist,$item->id.':'.$item->name);
        }

        $districtids = Branch::distinct()->lists('districtid');
        $districts = District::whereIn('id', $districtids)->orderBy('name', 'asc')->get(['id', 'name']);
        $districtselectlist = array();
        array_push($districtselectlist,':เลือกตำบล/แขวง');
        foreach($districts as $item){
            array_push($districtselectlist,$item->id.':'.$item->name);
        }

        return view('settings.branch',['provinceselectlist' => implode(";",$provinceselectlist),
            'amphurselectlist' => implode(";",$amphurselectlist),
            'districtselectlist' => implode(";",$districtselectlist)]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new BranchRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new BranchRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
        $amphurids = Branch::distinct()->lists('amphurid');
        $amphurs = Amphur::whereIn('id', $amphurids)->orderBy('name', 'asc')->get(['id', 'name']);
        $amphurselectlist = array();
        array_push($amphurselectlist,':เลือกเขต/อำเภอ');
        foreach($amphurs as $item){
            array_push($amphurselectlist,$item->id.':'.$item->name);
        }

        $districtids = Branch::distinct()->lists('districtid');
        $districts = District::whereIn('id', $districtids)->orderBy('name', 'asc')->get(['id', 'name']);
        $districtselectlist = array();
        array_push($districtselectlist,':เลือกตำบล/แขวง');
        foreach($districts as $item){
            array_push($districtselectlist,$item->id.':'.$item->name);
        }

        return ['amphurselectlist'=>implode(";",$amphurselectlist),'districtselectlist'=>implode(";",$districtselectlist)];
    }

    public function check_headquarter(Request $request)
    {
        $input = $request->only('id','provinceid');
        $count = Branch::where('id','!=', $input['id'])
            ->where('provinceid', $input['provinceid'])
            ->where('isheadquarter', true)->count();
        if($count > 0){
            return "true";
        }
    }

    public function check_keyslot(Request $request)
    {
        $input = $request->only('provinceid','keyslot');
        $maxKeyNo = Car::where('provinceid', $input['provinceid'])->where('issold', false)->max('keyno');
        if($maxKeyNo != null && $input['keyslot'] < $maxKeyNo){
            return "true";
        }
    }
}