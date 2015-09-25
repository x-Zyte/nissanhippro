<?php

namespace App\Http\Controllers\Settings;

use App\Facades\GridEncoder;
use App\Repositories\TeamRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TeamController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.team');
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new TeamRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new TeamRepository(), $request);
    }
}