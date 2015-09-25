<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Facades\GridEncoder;
use App\Repositories\EmployeeRepository;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class EmployeeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branchs = Branch::all(['id','name']);
        $branchselectlist = array();
        array_push($branchselectlist,':เลือกสาขา');
        foreach($branchs as $item){
            array_push($branchselectlist,$item->id.':'.$item->name);
        }

        $departments = Department::all(['id','name']);
        $departmentselectlist = array();
        array_push($departmentselectlist,':เลือกแผนก');
        foreach($departments as $item){
            array_push($departmentselectlist,$item->id.':'.$item->name);
        }

        $teams = Team::all(['id','name']);
        $teamselectlist = array();
        array_push($teamselectlist,':เลือกทีม');
        foreach($teams as $item){
            array_push($teamselectlist,$item->id.':'.$item->name);
        }

        return view('employee',
            ['branchselectlist' => implode(";",$branchselectlist),
            'departmentselectlist' => implode(";",$departmentselectlist),
            'teamselectlist' => implode(";",$teamselectlist)]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new EmployeeRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new EmployeeRepository(), $request);
    }

    /*public function check_username(Request $request)
    {
        $input = $request->only('id','username');

        $count = Employee::where('id','!=', $input['id'])
            ->where('username', $input['username'])->count();
        if($count > 0){
            return "true";
        }
    }

    public function check_email(Request $request)
    {
        $input = $request->only('id','email');

        $count = Employee::where('id','!=', $input['id'])
            ->where('email', $input['email'])->count();
        if($count > 0){
            return "true";
        }
    }*/
}