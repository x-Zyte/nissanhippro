<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\Giveaway;
use App\Repositories\GiveawayRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class GiveawayController extends Controller {

    protected $menuPermissionName = "การตั้งค่าการขาย";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        return view('settings.giveaway');
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new GiveawayRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new GiveawayRepository(), $request);
    }

    public function check_saleprice(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $input = $request->only('id','saleprice');
        $model = Giveaway::find($input['id']);
        if($model->saleprice > $input['saleprice']){
            return $model->saleprice;
        }
    }
}