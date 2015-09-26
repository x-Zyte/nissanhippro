<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Repositories\CustomerRealRepository;
use Illuminate\Support\Facades\Input;


class CustomerRealController extends CustomerController {

    public function __construct()
    {
        $this->middleware('auth');
        $this->viewname = 'customerreal';
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new CustomerRealRepository(), Input::all());
    }
}