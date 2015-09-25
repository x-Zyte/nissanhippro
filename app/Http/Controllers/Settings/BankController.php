<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BankController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.bank');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new BankRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new BankRepository(), $request);
    }
}