<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;


use App\Facades\GridEncoder;
use App\Repositories\CustomerExpectationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class CustomerExpectationController extends Controller {

    protected $menuPermissionName = "ลูกค้า";

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
                    "rules"=>array(array("field"=>"customerid","op"=>"eq","data"=>$input["customerid"]),
                        array("field"=>"active","op"=>"eq","data"=>true))));
            }
            else {
                $filters = json_decode(str_replace('\'','"',$input['filters']), true);
                array_push($filters['rules'], array("field" => "customerid", "op" => "eq", "data" => $input["customerid"]),
                    array("field" => "active", "op" => "eq", "data" => true));
                $input["filters"] = json_encode($filters);
            }
        }
        else{
            $input = array_add($input,"filters",json_encode(array("groupOp"=>"AND",
                "rules"=>array(array("field"=>"customerid","op"=>"eq","data"=>$input["customerid"]),
                    array("field"=>"active","op"=>"eq","data"=>true)))));
        }
        GridEncoder::encodeRequestedData(new CustomerExpectationRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CustomerExpectationRepository(), $request);
    }
}