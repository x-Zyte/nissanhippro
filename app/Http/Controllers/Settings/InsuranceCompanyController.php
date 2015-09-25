<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\InsuranceCompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class InsuranceCompanyController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.insurancecompany');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new InsuranceCompanyRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new InsuranceCompanyRepository(), $request);
    }
}