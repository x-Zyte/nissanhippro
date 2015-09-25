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

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read()
    {
        $input = Input::all();
        if(in_array("filters", $input)){
            $input = Input::all();
            $filters = json_decode(str_replace('\'','"',$input['filters']), true);
            array_push($filters['rules'],array("field"=>"customerid","op"=>"eq","data"=>$input['customerid']));
            $input['filters'] = json_encode($filters);
        }
        else{
            $input = array_add($input,'filters',json_encode(array("groupOp"=>"AND","rules"=>array(array("field"=>"customerid","op"=>"eq","data"=>$input['customerid'])))));
        }
        GridEncoder::encodeRequestedData(new CustomerExpectationRepository(), $input);
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CustomerExpectationRepository(), $request);
    }
}