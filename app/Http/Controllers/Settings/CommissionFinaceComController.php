<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CommissionFinaceCom;
use App\Repositories\CommissionFinaceComRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommissionFinaceComController extends Controller {

    protected $menuPermissionName = "การตั้งค่าการขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function readSelectlist($commissionfinaceid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $models = CommissionFinaceCom::where('commissionfinaceid',$commissionfinaceid)->orderBy('interestcalculation', 'asc')->get(['id', 'interestcalculation']);
        return $models;
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = Input::all();
        if(array_key_exists("filters", $input)){
            if($input['filters'] == null){
                $input['filters'] = json_encode(array("groupOp"=>"AND",
                    "rules"=>array(array("field"=>"commissionfinaceid","op"=>"eq","data"=>$input["commissionfinaceid"]))));
            }
            else {
                $filters = json_decode(str_replace('\'', '"', $input['filters']), true);
                array_push($filters['rules'], array("field" => "commissionfinaceid", "op" => "eq", "data" => $input['commissionfinaceid']));
                $input['filters'] = json_encode($filters);
            }
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"commissionfinaceid","op"=>"eq","data"=>$input['commissionfinaceid'])))));
        }
        GridEncoder::encodeRequestedData(new CommissionFinaceComRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionFinaceComRepository(), $request);
    }

    public function check_dup_interestcalculation(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = $request->only('id','commissionfinaceid','interestcalculation');
        $model = CommissionFinaceCom::where('id','!=', $input['id'])
            ->where('commissionfinaceid', $input['commissionfinaceid'])
            ->where('interestcalculation', $input['interestcalculation'])
            ->first();

        if($model != null){
            return "x";
        }
    }
}