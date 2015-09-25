<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DepartmentController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.department');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new DepartmentRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new DepartmentRepository(), $request);
    }
}