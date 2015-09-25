<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\ColorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ColorController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.color');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new ColorRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new ColorRepository(), $request);
    }
}