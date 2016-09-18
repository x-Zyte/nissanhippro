<?php namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('home');
	}

	public function import()
	{
		try {
			$file = Input::file('file');
			//$path = Input::file('pricelist')->getRealPath();
			$temp = null;
			Excel::load($file, function ($reader) use ($temp) {
				//$reader->dump();
				// Loop through all rows
				$reader->each(function ($row) {

				});

			});
		} catch (Exception $e) {
			return 'Message: ' . $e->getMessage();
		}

		return redirect()->action('HomeController@index');
	}

}
