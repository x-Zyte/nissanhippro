<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class ReportController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user.
     *
     * @return Response
     */
    public function index()
    {
        $output = \JasperPHP::list_parameters(
            public_path() . '/report/test.jasper'
        )->execute();

        foreach($output as $parameter_description)
            echo $parameter_description;

        //return view('report');
    }


    public function post()
    {

        $database = \Config::get('database.connections.mysql');
        $output = public_path() . '/report/'.time().'_test';

        $ext = "xls";

        \JasperPHP::process(
            public_path() . '/report/test.jasper',
            $output,
            array($ext),
            array(),
            $database,
            false,
            false
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.time().'_test.'.$ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($output.'.'.$ext));
        flush();
        readfile($output.'.'.$ext);
        unlink($output.'.'.$ext); // deletes the temporary file

        return Redirect::to('/reporting');
    }

}