<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;

use App\Facades\GridEncoder;
use App\Repositories\CustomerExpectRepository;
use Illuminate\Support\Facades\Input;


class CustomerExpectController extends CustomerController {

    public function __construct()
    {
        $this->middleware('auth');
        $this->viewname = 'customerexpect';
    }

    public function read()
    {
        GridEncoder::encodeRequestedData(new CustomerExpectRepository(), Input::all());
    }
}