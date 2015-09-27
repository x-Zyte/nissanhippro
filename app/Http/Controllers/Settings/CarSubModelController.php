<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarSubModel;
use App\Repositories\CarSubModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarSubModelController extends Controller {

    protected $menuPermissionName = "การตั้งค่ารถ";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function readSelectlist($carmodelid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carsubmodels = CarSubModel::where('carmodelid',$carmodelid)->orderBy('name', 'asc')->get(['id', 'name']);
        return $carsubmodels;
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = Input::all();
        if(in_array("filters", $input)){
            $input = Input::all();
            $filters = json_decode(str_replace('\'','"',$input['filters']), true);
            array_push($filters['rules'],array("field"=>"carmodelid","op"=>"eq","data"=>$input['carmodelid']));
            $input['filters'] = json_encode($filters);
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"carmodelid","op"=>"eq","data"=>$input['carmodelid'])))));
        }
        GridEncoder::encodeRequestedData(new CarSubModelRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarSubModelRepository(), $request);
    }
}