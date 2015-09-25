<?php

namespace App\Http\Controllers\Settings;

use App\Models\CarModel;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\CarSubModel;
use App\Repositories\CarSubModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarSubModelController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    /*public function index()
    {
        $carmodels = CarModel::all(['id','name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบรถ');
        foreach($carmodels as $cm){
            array_push($carmodelselectlist,$cm->id.':'.$cm->name);
        }

        return view('settings.carsubmodel', ['carmodelselectlist' => implode(";",$carmodelselectlist)]);
    }*/

    public function readSelectlist($carmodelid)
    {
        $carsubmodels = CarSubModel::where('carmodelid',$carmodelid)->orderBy('name', 'asc')->get(['id', 'name']);
        return $carsubmodels;
    }

    public function read()
    {
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
        GridEncoder::encodeRequestedData(new CarSubModelRepository(), $request);
    }
}