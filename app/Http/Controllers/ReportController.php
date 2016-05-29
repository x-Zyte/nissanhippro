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
                $carpreemptions = DB::select('select * from report_carstock_carpreemptions');
                $carRequired = DB::select('select * from report_carstock_carrequired');

                $rsCount = count($results);
                $carpreemptionsCount = count($carpreemptions);
                $carRequiredCount = count($carRequired);

                $sheet->setAutoSize(true);
                $rowIndex = 0;

                $rowIndex++;
                $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A'.$rowIndex.':R'.$rowIndex);
                $sheet->row($rowIndex, array('บริษัทสยามนิสสัน'));

                $rowIndex++;
                $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A'.$rowIndex.':R'.$rowIndex);
                $sheet->row($rowIndex, array('สต็อครถ ประจำวันที่ '.date("d/m/Y")));

                $rowIndex++;
                $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A'.$rowIndex.':R'.$rowIndex);
                $sheet->row($rowIndex, array('เรียงตามวันที่รับรถ'));

                $rowIndex++;
                $sheet->mergeCells('A'.$rowIndex.':R'.$rowIndex);

                $rowIndex++;
                $sheet->row($rowIndex, array(
                    'ลำดับ', 'คันที่', 'วันที่ออก Do', 'วันที่รับรถเข้า', 'จำนวนวัน', 'เลขเครื่อง', 'เลขตัวถัง', 'กุญแจ', 'แบบ',
                    'รุ่น', 'สี', 'จอด', 'วันที่แจ้งขาย', 'ชื่อลูกค้า', 'SALE', 'FN/สด', 'สถานะ', 'วันที่คาดส่ง'
                ));
                $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->setBorder('A'.$rowIndex.':R'.$rowIndex, 'thin');

                $rowNum = 1;
                foreach($results as $item){
                    $rowIndex++;

                    if($item->datewantgetcar != null && $item->datewantgetcar != '')
                        $item->datewantgetcar = date('d/m/Y', strtotime($item->datewantgetcar));

                    $sheet->row($rowIndex, array(
                        $rowNum, $item->no, date('d/m/Y', strtotime($item->dodate)),date('d/m/Y', strtotime($item->receiveddate)),
                        $item->days,$item->engineno,$item->chassisno, $item->keyno,$item->model,$item->submodel,$item->color,
                        $item->parklocation,null,$item->custname,$item->empname,$item->fn,$item->documentstatus,$item->datewantgetcar
                    ));

                    $sheet->cells('A'.$rowIndex.':H'.$rowIndex, function($cells) {
                        $cells->setAlignment('center');
                    });

                    if($item->days > 360){
                        $sheet->cell('K'.$rowIndex, function($cell) {
                            $cell->setBackground('#ff99ff');
                        });
                    }
                    elseif($item->days > 180 && $item->days <= 360){
                        $sheet->cell('K'.$rowIndex, function($cell) {
                            $cell->setBackground('#00ff00');
                        });
                    }
                    elseif($item->days >= 90 && $item->days <= 180){
                        $sheet->cell('K'.$rowIndex, function($cell) {
                            $cell->setBackground('#ffff66');
                        });
                    }

                    $sheet->cell('K'.$rowIndex, function($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->cells('M'.$rowIndex.':R'.$rowIndex, function($cells) {
                        $cells->setAlignment('center');
                    });

                    $sheet->setBorder('A'.$rowIndex.':R'.$rowIndex, 'thin');

                    $rowNum++;
                }

                $rowIndex+=2;
                $sheet->row($rowIndex, array(null,null,null,null,null,null,null,null,'สีชมพู คือ เกิน 1ปีขึ้นไป'));
                $sheet->cell('i'.$rowIndex, function($cell) {
                    $cell->setBackground('#ff99ff');
                    $cell->setAlignment('center');
                });
                $rowIndex++;
                $sheet->row($rowIndex, array(null,null,null,null,null,null,null,null,'สีเขียว คือ 6-12 เดือน'));
                $sheet->cell('i'.$rowIndex, function($cell) {
                    $cell->setBackground('#00ff00');
                    $cell->setAlignment('center');
                });
                $rowIndex++;
                $sheet->row($rowIndex, array(null,null,null,null,null,null,null,null,'สีเหลือง คือ 90-180 วัน'));
                $sheet->cell('i'.$rowIndex, function($cell) {
                    $cell->setBackground('#ffff66');
                    $cell->setAlignment('center');
                });


                $rowIndex+=2;
                $sheet->row($rowIndex, array(null,null,null,null,'STOCK',$rsCount,null,null,'จับคู่',null,$carpreemptionsCount-$carRequiredCount));
                $sheet->cells('E'.$rowIndex.':F'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                });
                $sheet->cell('I'.$rowIndex, function($cell) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(14);
                });
                $sheet->cell('K'.$rowIndex, function($cell) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(14);
                    $cell->setAlignment('center');
                });
                $rowIndex++;
                $sheet->row($rowIndex, array(null,null,null,null,'ฝากจอด',0,null,null,'ว่าง',null,$rsCount-($carpreemptionsCount-$carRequiredCount)));
                $sheet->cells('E'.$rowIndex.':F'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                });
                $sheet->cell('I'.$rowIndex, function($cell) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(14);
                });
                $sheet->cell('F'.$rowIndex, function($cell) {
                    $cell->setBorder('none', 'none', 'thin', 'none');
                });
                $sheet->cell('K'.$rowIndex, function($cell) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(14);
                    $cell->setAlignment('center');
                    $cell->setBorder('none', 'none', 'thin', 'none');
                });
                $rowIndex++;
                $sheet->row($rowIndex, array(null,null,null,null,null,$rsCount,null,null,null,null,$rsCount));
                $sheet->cell('F'.$rowIndex, function($cell) {
                    $cell->setFontColor('#ff0000');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(14);
                    $cell->setAlignment('center');
                    $cell->setBorder('none', 'none', 'double', 'none');
                });
                $sheet->cell('K'.$rowIndex, function($cell) {
                    $cell->setFontColor('#ff0000');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(14);
                    $cell->setAlignment('center');
                    $cell->setBorder('none', 'none', 'double', 'none');
                });


                $rowIndex+=2;
                $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A'.$rowIndex.':R'.$rowIndex);
                $sheet->row($rowIndex, array('รถที่ต้องการหา'));
                $rowIndex++;
                $sheet->row($rowIndex, array(
                    'ลำดับ', 'แบบ',null, 'รุ่น', 'สี', 'ชื่อลูกค้า', 'SALE', 'FN/สด', 'สถานะ','ทำสัญญา', 'วันที่คาดส่ง','หมายเหตุ',null
                ));
                $sheet->mergeCells('B'.$rowIndex.':C'.$rowIndex);
                $sheet->mergeCells('L'.$rowIndex.':N'.$rowIndex);
                $sheet->cells('A'.$rowIndex.':N'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->setBorder('A'.$rowIndex.':N'.$rowIndex, 'thin');

                $rowNum = 1;
                foreach($carRequired as $item){
                    $rowIndex++;

                    $sheet->row($rowIndex, array(
                        $rowNum, $item->model,null,$item->submodel,$item->color,$item->custname,$item->empname,$item->fn,
                            $item->documentstatus,null,date('d/m/Y', strtotime($item->datewantgetcar)),$item->remark
                    ));

                    $sheet->mergeCells('B'.$rowIndex.':C'.$rowIndex);
                    $sheet->mergeCells('L'.$rowIndex.':N'.$rowIndex);

                    $sheet->cell('A'.$rowIndex, function($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->cells('E'.$rowIndex.':K'.$rowIndex, function($cells) {
                        $cells->setAlignment('center');
                    });

                    $sheet->setBorder('A'.$rowIndex.':N'.$rowIndex, 'thin');

                    $rowNum++;
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