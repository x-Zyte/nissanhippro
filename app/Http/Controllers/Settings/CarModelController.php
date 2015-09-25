<?php

namespace App\Http\Controllers\Settings;

use App\Models\CarType;
use App\Facades\GridEncoder;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Repositories\CarModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CarModelController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartypes = CarType::all(['id','name']);
        $cartypeselectlist = array();
        array_push($cartypeselectlist,':เลือกประเภทรถ');
        foreach($cartypes as $ct){
            array_push($cartypeselectlist,$ct->id.':'.$ct->name);
        }

        $colors = Color::all(['id','code','name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        return view('settings.carmodel',
            ['cartypeselectlist' => implode(";",$cartypeselectlist),
                'colorselectlist' => implode(";",$colorselectlist)]);
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new CarModelRepository(), Input::all());
    }

    public function update(Request $request)
    {
        GridEncoder::encodeRequestedData(new CarModelRepository(), $request);
    }
}