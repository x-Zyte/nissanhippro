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

    protected $menuPermissionName = "พนักงาน";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

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
            array_push($departmentselectlist,$item->id.':'.str_replace('&','\u0026',$item->name));
        }

        $departmentselectlist2 = array();
        array_push($departmentselectlist2,':เลือกแผนก');
        foreach($departments as $item){
            array_push($departmentselectlist2,$item->id.':'."'".$item->name."'");
        }

        //return $departments;


        $teams = Team::all(['id','name']);
        $teamselectlist = array();
        array_push($teamselectlist,':เลือกทีม');
        foreach($teams as $item){
            array_push($teamselectlist,$item->id.':'.$item->name);
        }

        return view('employee',
            ['branchselectlist' => implode(";",$branchselectlist),
            'departmentselectlist' => implode(";",$departmentselectlist),
            'departmentselectlist2' => '{'.implode(",",$departmentselectlist2).'}',
            'teamselectlist' => implode(";",$teamselectlist)]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new EmployeeRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new EmployeeRepository(), $request);
    }
}