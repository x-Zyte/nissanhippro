<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarModelColor;
use App\Models\Color;
use App\Repositories\CarModelColorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarModelColorController extends Controller {

    protected $menuPermissionName = "การตั้งค่ารถ";

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
                $input['filters'] = json_encode(array("groupOp"=>"AND",
                    "rules"=>array(array("field"=>"carmodelid","op"=>"eq","data"=>$input["carmodelid"]),
                        array("field"=>"active","op"=>"eq","data"=>true))));
            }
            else {
                $filters = json_decode(str_replace('\'', '"', $input['filters']), true);
                array_push($filters['rules'], array("field" => "carmodelid", "op" => "eq", "data" => $input['carmodelid']));
                $input['filters'] = json_encode($filters);
            }
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"carmodelid","op"=>"eq","data"=>$input['carmodelid'])))));
        }
        GridEncoder::encodeRequestedData(new CarModelColorRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarModelColorRepository(), $request);
    }

    public function readSelectlist($carmodelid)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $colorids = CarModelColor::where('carmodelid',$carmodelid)->lists('colorid');
        $colors = Color::whereIn('id', $colorids)->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        return $colors;
    }

    public function check_color(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = $request->only('id','carmodelid','colorid');
        $carmodelcolor = CarModelColor::where('id','!=', $input['id'])
            ->where('carmodelid', $input['carmodelid'])
            ->where('colorid', $input['colorid'])->first();

        if($carmodelcolor != null){
            return $carmodelcolor->color->code;
        }
    }
}