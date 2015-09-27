<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Repositories\TeamRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TeamController extends Controller {

    protected $menuPermissionName = "การตั้งค่าส่วนกลาง";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        return view('settings.team');
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);
        
        GridEncoder::encodeRequestedData(new TeamRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new TeamRepository(), $request);
    }
}