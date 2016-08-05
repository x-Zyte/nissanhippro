<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BankController extends Controller {

    protected $menuPermissionName = "การตั้งค่าทั่วไป";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        return view('settings.bank');
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new BankRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new BankRepository(), $request);
    }

    public function readSelectlistByAccountGroup($accountgroup)
    {
        if (!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $banks = Bank::where('accountgroup', $accountgroup)->orderBy('name', 'asc')->get(['id', 'name', 'accountno']);
        return $banks;
    }
}