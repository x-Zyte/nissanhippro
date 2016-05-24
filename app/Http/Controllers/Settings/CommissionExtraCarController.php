<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarSubModel;
use App\Models\CommissionExtraCar;
use App\Repositories\CommissionExtraCarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CommissionExtraCarController extends Controller {

    protected $menuPermissionName = "การตั้งค่าการขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function readSelectlist($commissionextraid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $models = CommissionExtraCar::where('commissionextraid',$commissionextraid)->orderBy('down', 'asc')->get(['id', 'down']);
        return $models;
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = Input::all();
        if(array_key_exists("filters", $input)){
            if($input['filters'] == null){
                $input['filters'] = json_encode(array("groupOp"=>"AND",
                    "rules"=>array(array("field"=>"commissionextraid","op"=>"eq","data"=>$input["commissionextraid"]))));
            }
            else {
                $filters = json_decode(str_replace('\'', '"', $input['filters']), true);
                array_push($filters['rules'], array("field" => "commissionextraid", "op" => "eq", "data" => $input['commissionextraid']));
                $input['filters'] = json_encode($filters);
            }
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"commissionextraid","op"=>"eq","data"=>$input['commissionextraid'])))));
        }
        GridEncoder::encodeRequestedData(new CommissionExtraCarRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CommissionExtraCarRepository(), $request);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carsubmodels = CarSubModel::has('commissionExtraCars')->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,'0:ทุกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        return ['carsubmodelselectlist'=>implode(";",$carsubmodelselectlist)];
    }

    public function check_dup_carsubmodel(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = $request->only('id','commissionextraid','carmodelid','carsubmodelid');

        $model = CommissionExtraCar::where('id','!=', $input['id'])
            ->where('commissionextraid', $input['commissionextraid'])
            ->where('carmodelid', $input['carmodelid'])
            ->where('carsubmodelid', 0)
            ->first();

        if($model != null){
            return "แบบรถนี้ ทุกรุ่น มีอยู่ในระบบแล้ว";
        }

        if($input['carsubmodelid'] != 0) {
            $model = CommissionExtraCar::where('id', '!=', $input['id'])
                ->where('commissionextraid', $input['commissionextraid'])
                ->where('carmodelid', $input['carmodelid'])
                ->where('carsubmodelid', $input['carsubmodelid'])
                ->first();

            if ($model != null) {
                return "แบบรถนี้ รุ่น " + $model->carsubmodel->name + " มีอยู่ในระบบแล้ว";
            }
        }
    }
}