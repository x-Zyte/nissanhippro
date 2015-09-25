<?php namespace App\Http\Controllers;

use App\Branch;
use App\Province;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        /*$province = new Province;
        $province->name = 'กรุงเทพ';
        $province->createdby = 1;
        $province->modifiedby = 1;
        $province->save();

        $province2 = Province::create(['name' => 'Bangkok','createdby' => 1,'modifiedby' => 1]);*/

        //$province3 = new Province;
        //$province3->name = 'ลำปาง';
        //$province3->createdby = 1;
        //$province3->modifiedby = 1;

//        $province = Province::find(3);
//        echo $province->id . ' ' . $province->name;
//        $province->delete();
//
//        echo $province->id . ' ' . $province->name;
        //$province->employeeCreated->username
        //echo $province->id . ' ' . $province->name . ' ' . $province->branches()->first()->name;

		//return "hello world " . $province->id . ' and ' . $province2->id;
		return view('welcome');
	}

}
