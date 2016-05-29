<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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


    public function carstock()
    {
        Excel::create('สต็อครถ_'.date("d/m/Y"), function($excel) {
            // Set the title
            $excel->setTitle('no title');
            $excel->setCreator('no no creator')->setCompany('no company');
            $excel->setDescription('report file');

            $excel->sheet('sheet1', function($sheet) {
                $results = DB::select('select * from report_carstock');
                $rsCount = count($results);

                $sheet->cells('A1:R1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A1:R1');
                $sheet->row(1, array('บริษัทสยามนิสสัน'));

                $sheet->cells('A2:R2', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A2:R2');
                $sheet->row(2, array('สต็อครถ ประจำวันที่ '.date("d/m/Y")));

                $sheet->cells('A3:R3', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A3:R3');
                $sheet->row(3, array('เรียงตามวันที่รับรถ'));

                $sheet->mergeCells('A4:R4');

                $sheet->row(5, array(
                    'ลำดับ', 'คันที่', 'วันที่ออก Do', 'วันที่รับรถเข้า', 'จำนวนวัน', 'เลขเครื่อง', 'เลขตัวถัง', 'กุญแจ', 'แบบ',
                    'รุ่น', 'สี', 'จอด', 'วันที่แจ้งขาย', 'ชื่อลูกค้า', 'SALE', 'FN/สด', 'สถานะ', 'วันที่คาดส่ง'
                ));
                $sheet->cells('A5:R5', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->setBorder('A5:R5', 'thin');

                $i = 6;
                foreach($results as $item){
                    if($item->datewantgetcar != null && $item->datewantgetcar != '')
                        $item->datewantgetcar = date('d/m/Y', strtotime($item->datewantgetcar));

                    $sheet->row($i, array(
                        $i-5, $item->no, date('d/m/Y', strtotime($item->dodate)),date('d/m/Y', strtotime($item->receiveddate)),
                        $item->days,$item->engineno,$item->chassisno, $item->keyno,$item->model,$item->submodel,$item->color,
                        $item->parklocation,null,$item->custname,$item->empname,$item->fn,$item->documentstatus,$item->datewantgetcar
                    ));

                    $sheet->cells('A'.$i.':H'.$i, function($cells) {
                        $cells->setAlignment('center');
                    });
                    $sheet->cells('K'.$i, function($cells) {
                        $cells->setAlignment('center');
                    });
                    $sheet->cells('M'.$i.':R'.$i, function($cells) {
                        $cells->setAlignment('center');
                    });

                    $sheet->setBorder('A'.$i.':R'.$i, 'thin');

                    $i++;
                }

                /*$data = array(
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

                });*/
            });
        })->download('xlsx');
        //return Redirect::to('/reporting');
    }

}