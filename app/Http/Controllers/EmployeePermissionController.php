<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Repositories\EmployeePermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class EmployeePermissionController extends Controller {

    protected $menuPermissionName = "พนักงาน";

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
                $input['filters'] = json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"employeeid","op"=>"eq","data"=>$input['employeeid']))));
            }
            else {
                $filters = json_decode(str_replace('\'', '"', $input['filters']), true);
                array_push($filters['rules'], array("field" => "employeeid", "op" => "eq", "data" => $input['employeeid']));
                $input['filters'] = json_encode($filters);
            }
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"employeeid","op"=>"eq","data"=>$input['employeeid'])))));
        }
        GridEncoder::encodeRequestedData(new EmployeePermissionRepository(), $input);
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new EmployeePermissionRepository(), $request);
    }
}