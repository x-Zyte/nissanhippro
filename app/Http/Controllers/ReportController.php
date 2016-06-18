<?php namespace App\Http\Controllers;

use App\Models\SystemDatas\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

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
        if(Auth::user()->isadmin){
            $provincebranchs = Province::whereHas('branchs', function($q){
                $q->where('isheadquarter', true);
            })->orderBy('name', 'asc')->get(['id', 'name']);
        }
        else{
            $provincebranchs = Province::where('id', Auth::user()->provinceid)
                ->whereHas('branchs', function($q){
                    $q->where('isheadquarter', true);
                })
                ->orderBy('name', 'asc')->get(['id', 'name']);
        }
        $provincebranchselectlist = array();
        foreach($provincebranchs as $item){
            $provincebranchselectlist[$item->id] = $item->name;
        }

        return view('report',['provincebranchselectlist' => $provincebranchselectlist]);
    }


    public function carstock(Request $request)
    {
        $this->validate($request, [
                'date' => 'required'
            ],
            [
                'date.required' => 'รายงาน สต็อครถ - วันที่ จำเป็นต้องเลือก'
            ]
        );

        $input = $request->all();
        $provinceid = $input['provinceid'];
        $orderbytype = $input['orderbytype'];
        $date = date('Y-m-d', strtotime($input['date']));

        $province = Province::find($provinceid);
        if($orderbytype == 1)
            $orderbytext = 'วันที่รับรถ';
        else
            $orderbytext = 'แบบ/รุ่นและวันที่รับรถ';

        $filename = 'สต็อครถ'.$province->name.'_เรียงตาม'.$orderbytext.'_'.date("d/m/Y");

        Excel::create($filename, function($excel) use($province,$orderbytype,$orderbytext,$date){
            // Set the title
            $excel->setTitle('no title');
            $excel->setCreator('no no creator')->setCompany('no company');
            $excel->setDescription('report file');

            $excel->sheet('sheet1', function($sheet) use($province,$orderbytype,$orderbytext,$date){

                $results = DB::select('CALL report_carstock('.$province->id.','."'".$date."'".','.$orderbytype.')');
                $carRequired = DB::select('CALL report_carstock_carrequired('.$province->id.','."'".$date."'".')');
                $carTestDrives = DB::select('select * from car_test_drive where provinceid = '.$province->id.' and dodate <= '."'".$date."'");
                $carTestUses = DB::select('select * from car_test_use where provinceid = '.$province->id.' and dodate <= '."'".$date."'");

                $rsCount = count($results);
                $matchedCount = 0;
                $carRequiredCount = count($carRequired);
                $carTestDriveCount = count($carTestDrives);
                $carTestUseCount = count($carTestUses);
                $maxRowCarTest = $carTestDriveCount;
                if($carTestUseCount > $carTestDriveCount)
                    $maxRowCarTest = $carTestUseCount;

                $sheet->setAutoSize(true);
                $rowIndex = 0;

                $rowIndex++;
                $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                });
                $sheet->mergeCells('A'.$rowIndex.':R'.$rowIndex);
                $sheet->row($rowIndex, array('บริษัทสยามนิสสัน'.$province->name));

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
                $sheet->row($rowIndex, array('เรียงตาม'.$orderbytext));

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
                $modelGroup = '';
                foreach($results as $item){
                    if($item->custname != null && $item->custname != '') $matchedCount++;

                    if($orderbytype == 2 && $item->model != $modelGroup) {
                        $modelGroup = $item->model;
                        $rowIndex++;
                        $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                            $cells->setFontWeight('bold');
                        });
                        $sheet->mergeCells('A'.$rowIndex.':R'.$rowIndex);
                        $sheet->row($rowIndex, array($modelGroup));

                    }

                    $rowIndex++;
                    if($item->receiveddate != null && $item->receiveddate != '')
                        $item->receiveddate = date('d/m/Y', strtotime($item->receiveddate));

                    if($item->datewantgetcar != null && $item->datewantgetcar != '')
                        $item->datewantgetcar = date('d/m/Y', strtotime($item->datewantgetcar));

                    if($item->notifysolddate != null && $item->notifysolddate != '')
                        $item->notifysolddate = date('d/m/Y', strtotime($item->notifysolddate));

                    $sheet->row($rowIndex, array(
                        $rowNum, $item->no, date('d/m/Y', strtotime($item->dodate)),$item->receiveddate,
                        $item->days,$item->engineno,$item->chassisno, $item->keyno,$item->model,$item->submodel,$item->color,
                        $item->parklocation,$item->notifysolddate,$item->custname,$item->empname,$item->fn,$item->documentstatus,$item->datewantgetcar
                    ));

                    $sheet->cells('A'.$rowIndex.':H'.$rowIndex, function($cells) {
                        $cells->setAlignment('center');
                    });

                    if($item->days > 360){
                        $sheet->cell('E'.$rowIndex, function($cell) {
                            $cell->setBackground('#ff99ff');
                        });
                    }
                    elseif($item->days > 180 && $item->days <= 360){
                        $sheet->cell('E'.$rowIndex, function($cell) {
                            $cell->setBackground('#00ff00');
                        });
                    }
                    elseif($item->days >= 90 && $item->days <= 180){
                        $sheet->cell('E'.$rowIndex, function($cell) {
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

                //car test
                $rowIndex+=2;
                $sheet->row($rowIndex, array(null,'ลำดับ','รถ TEST DRIVE',null,null,null,null,null,null,null,'ลำดับ','รถ TEST ใช้งาน'));
                $sheet->mergeCells('C'.$rowIndex.':G'.$rowIndex);
                $sheet->mergeCells('L'.$rowIndex.':Q'.$rowIndex);
                $sheet->cells('A'.$rowIndex.':R'.$rowIndex, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->setBorder('B'.$rowIndex.':G'.$rowIndex, 'thin');
                $sheet->setBorder('K'.$rowIndex.':Q'.$rowIndex, 'thin');

                $rowNum = 0;
                for ($i = 0; $i < $maxRowCarTest; $i++) {
                    $rowIndex++;
                    $rowNum++;

                    if($rowNum <= count($carTestDrives) && $rowNum <= count($carTestUses)) {
                        $carTestDrive = $carTestDrives[$i];
                        $carTestUse = $carTestUses[$i];
                        $sheet->row($rowIndex, array(null, $rowNum, $carTestDrive->model, null, $carTestDrive->submodel, $carTestDrive->color
                            , null, null, null, null, $rowNum, $carTestUse->model, null, null, $carTestUse->submodel, $carTestUse->color));
                        $sheet->setBorder('B'.$rowIndex.':G'.$rowIndex, 'thin');
                        $sheet->mergeCells('C'.$rowIndex.':D'.$rowIndex);
                        $sheet->setBorder('K'.$rowIndex.':Q'.$rowIndex, 'thin');
                        $sheet->mergeCells('L'.$rowIndex.':N'.$rowIndex);
                    }
                    elseif($rowNum <= count($carTestDrives)){
                        $carTestDrive = $carTestDrives[$i];
                        $sheet->row($rowIndex, array(null, $rowNum, $carTestDrive->model, null, $carTestDrive->submodel, $carTestDrive->color));
                        $sheet->setBorder('B'.$rowIndex.':G'.$rowIndex, 'thin');
                        $sheet->mergeCells('C'.$rowIndex.':D'.$rowIndex);
                    }
                    else{
                        $carTestUse = $carTestUses[$i];
                        $sheet->row($rowIndex, array(null, null, null, null, null, null
                        , null, null, null, null, $rowNum, $carTestUse->model, null, null, $carTestUse->submodel, $carTestUse->color));
                        $sheet->setBorder('K'.$rowIndex.':Q'.$rowIndex, 'thin');
                        $sheet->mergeCells('L'.$rowIndex.':N'.$rowIndex);
                    }
                    $sheet->cells('A' . $rowIndex . ':R' . $rowIndex, function ($cells) {
                        $cells->setAlignment('center');
                    });
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
                $sheet->row($rowIndex, array(null,null,null,null,'STOCK',$rsCount,null,null,'จับคู่',null,$matchedCount));
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
                $sheet->row($rowIndex, array(null,null,null,null,'ฝากจอด',0,null,null,'ว่าง',null,$rsCount-$matchedCount));
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

                $rowNum = 0;
                foreach($carRequired as $item){
                    $rowIndex++;
                    $rowNum++;

                    if($item->contractdate != null && $item->contractdate != '')
                        $item->contractdate = date('d/m/Y', strtotime($item->contractdate));

                    $sheet->row($rowIndex, array(
                        $rowNum, $item->model,null,$item->submodel,$item->color,$item->custname,$item->empname,$item->fn,
                            $item->documentstatus,$item->contractdate,date('d/m/Y', strtotime($item->datewantgetcar)),$item->remark
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