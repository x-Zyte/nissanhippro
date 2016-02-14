<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\InsuranceCompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class InsuranceCompanyController extends Controller {

    protected $menuPermissionName = "การตั้งค่าทั่วไป";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        return view('settings.insurancecompany');
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new InsuranceCompanyRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new InsuranceCompanyRepository(), $request);
    }
}