<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

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
        return view('report');
    }


    public function post()
    {
        Excel::create('testfile', function($excel) {
            // Set the title
            $excel->setTitle('no title');
            $excel->setCreator('no no creator')->setCompany('no company');
            $excel->setDescription('report file');

            $excel->sheet('sheet1', function($sheet) {
                $data = array(
                    array('header1', 'header2','header3','header4','header5','header6','header7'),
                    array('data1', 'data2', 300, 400, 500, 0, 100),
                    array('data1', 'data2', 300, 400, 500, 0, 100),
                    array('data1', 'data2', 300, 400, 500, 0, 100),
                    array('data1', 'data2', 300, 400, 500, 0, 100),
                    array('data1', 'data2', 300, 400, 500, 0, 100),
                    array('data1', 'data2', 300, 400, 500, 0, 100)
                );
                $sheet->fromArray($data, null, 'A1', false, false);
                $sheet->cells('A1:G1', function($cells) {
                    $cells->setBackground('#AAAAFF');

                });
            });
        })->download('xlsx');
        //return Redirect::to('/reporting');
    }

}