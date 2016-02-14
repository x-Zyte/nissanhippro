<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CommissionFinaceInterest;
use App\Repositories\CommissionFinaceInterestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommissionFinaceInterestController extends Controller {

    protected $menuPermissionName = "การตั้งค่าการขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function readSelectlist($commissionfinaceid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $models = CommissionFinaceInterest::where('commissionfinaceid',$commissionfinaceid)->orderBy('downfrom', 'asc')->get(['id', 'downfrom', 'downto']);
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
        GridEncoder::encodeRequestedData(new CommissionFinaceInterestRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionFinaceInterestRepository(), $request);
    }

    public function check_dup_down(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = $request->only('id','commissionfinaceid','down');
        $model = CommissionFinaceInterest::where('id','!=', $input['id'])
            ->where('commissionfinaceid', $input['commissionfinaceid'])
            ->where('downfrom','<=', $input['down'])
            ->where('downto','>=', $input['down'])->first();

        if($model != null){
            return "x";
        }
    }
}