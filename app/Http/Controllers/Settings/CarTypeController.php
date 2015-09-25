<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\CarTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarTypeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.cartype');
    }

    public function read(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarTypeRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarTypeRepository(), $request);
    }
}