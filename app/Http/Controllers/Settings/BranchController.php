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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class BranchController extends Controller {

    protected $menuPermissionName = "การตั้งค่าทั่วไป";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $provinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        array_push($provinceselectlist,':เลือกจังหวัด');
        foreach($provinces as $item){
            array_push($provinceselectlist,$item->id.':'.$item->name);
        }

        $amphurs = Amphur::has('branchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $amphurselectlist = array();
        array_push($amphurselectlist,':เลือกเขต/อำเภอ');
        foreach($amphurs as $item){
            array_push($amphurselectlist,$item->id.':'.$item->name);
        }

        $districts = District::has('branchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $districtselectlist = array();
        array_push($districtselectlist,':เลือกตำบล/แขวง');
        foreach($districts as $item){
            array_push($districtselectlist,$item->id.':'.$item->name);
        }

        $taxamphurs = Amphur::has('taxBranchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $taxamphurselectlist = array();
        array_push($taxamphurselectlist,':เลือกเขต/อำเภอ');
        foreach($taxamphurs as $item){
            array_push($taxamphurselectlist,$item->id.':'.$item->name);
        }

        $taxdistricts = District::has('taxBranchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $taxdistrictselectlist = array();
        array_push($taxdistrictselectlist,':เลือกตำบล/แขวง');
        foreach($taxdistricts as $item){
            array_push($taxdistrictselectlist,$item->id.':'.$item->name);
        }

        return view('settings.branch',
            ['provinceselectlist' => implode(";",$provinceselectlist),
            'amphurselectlist' => implode(";",$amphurselectlist),
            'districtselectlist' => implode(";",$districtselectlist),
                'taxprovinceselectlist' => implode(";",$provinceselectlist),
                'taxamphurselectlist' => implode(";",$taxamphurselectlist),
                'taxdistrictselectlist' => implode(";",$taxdistrictselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new BranchRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new BranchRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $amphurs = Amphur::has('branchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $amphurselectlist = array();
        array_push($amphurselectlist,':เลือกเขต/อำเภอ');
        foreach($amphurs as $item){
            array_push($amphurselectlist,$item->id.':'.$item->name);
        }

        $districts = District::has('branchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $districtselectlist = array();
        array_push($districtselectlist,':เลือกตำบล/แขวง');
        foreach($districts as $item){
            array_push($districtselectlist,$item->id.':'.$item->name);
        }

        $taxamphurs = Amphur::has('taxBranchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $taxamphurselectlist = array();
        array_push($taxamphurselectlist,':เลือกเขต/อำเภอ');
        foreach($taxamphurs as $item){
            array_push($taxamphurselectlist,$item->id.':'.$item->name);
        }

        $taxdistricts = District::has('taxBranchs')->orderBy('name', 'asc')->get(['id', 'name']);
        $taxdistrictselectlist = array();
        array_push($taxdistrictselectlist,':เลือกตำบล/แขวง');
        foreach($taxdistricts as $item){
            array_push($taxdistrictselectlist,$item->id.':'.$item->name);
        }

        return ['amphurselectlist'=>implode(";",$amphurselectlist),
            'districtselectlist'=>implode(";",$districtselectlist),
            'taxamphurselectlist'=>implode(";",$taxamphurselectlist),
            'taxdistrictselectlist'=>implode(";",$taxdistrictselectlist)];
    }

    public function check_headquarter(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

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
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = $request->only('provinceid','keyslot');
        $maxKeyNo = Car::where('provinceid', $input['provinceid'])->where('issold', false)->max('keyno');
        if($maxKeyNo != null && $input['keyslot'] < $maxKeyNo){
            return "true";
        }
    }
}