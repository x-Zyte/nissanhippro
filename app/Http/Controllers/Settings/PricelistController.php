<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\PricelistRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PricelistController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    public function index()
    {
        return view('settings.pricelist');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new PricelistRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new PricelistRepository(), $request);
    }
}