<?php
/**
 * Created by PhpStorm.
 * User: xZyte
 * Date: 8/12/2015
 * Time: 20:59
 */

namespace App\Http\Controllers;


use App\Facades\GridEncoder;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\CarSubModel;
use App\Models\Color;
use App\Models\Customer;
use App\Models\SystemDatas\Amphur;
use App\Models\SystemDatas\District;
use App\Models\SystemDatas\Province;
use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class CarController extends Controller {

    protected $menuPermissionName = "รถ";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $provinces = Province::whereHas('branchs', function($q){
            $q->where('isheadquarter', true);
        })->orderBy('name', 'asc')->get(['id', 'name']);
        $provinceselectlist = array();
        array_push($provinceselectlist,':เลือกจังหวัด');
        foreach($provinces as $item){
            array_push($provinceselectlist,$item->id.':'.$item->name);
        }

        $carmodels = CarModel::whereHas("carbrand", function($q)
        {
            $q->where('ismain',true);

        })->orderBy('name', 'asc')->get(['id', 'name']);
        $carmodelselectlist = array();
        array_push($carmodelselectlist,':เลือกแบบ');
        foreach($carmodels as $item){
            array_push($carmodelselectlist,$item->id.':'.$item->name);
        }

        $carsubmodels = CarSubModel::has('cars')->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        $colors = Color::has('cars')->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        $defaultProvince = '';
        if(!Auth::user()->isadmin){
            $defaultProvince =  Auth::user()->provinceid;
        }

        return view('car',
            ['provinceselectlist' => implode(";",$provinceselectlist),
            'carmodelselectlist' => implode(";",$carmodelselectlist),
            'carsubmodelselectlist' => implode(";",$carsubmodelselectlist),
            'colorselectlist' => implode(";",$colorselectlist),
            'defaultProvince'=>$defaultProvince]);
    }

    public function read()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarRepository(), Input::all());
    }

    public function update(Request $request)
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        GridEncoder::encodeRequestedData(new CarRepository(), $request);
    }

    public function upload()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $error = false;

        if(Input::hasFile('receivecarfile') && Input::file('receivecarfile')->isValid()){
            $error = true;

            $uploaddir = base_path().'/uploads/images/';
            $engineno = Input::get('engineno');
            $chassisno = Input::get('chassisno');

            $car = Car::where('engineno', $engineno)->where('chassisno',$chassisno)->first();

            $extension = Input::file('receivecarfile')->getClientOriginalExtension();
            $fileName = $engineno.'_'.$chassisno.'_received'.'.'.$extension;
            $upload_success = Input::file('receivecarfile')->move($uploaddir, $fileName);
            if($upload_success) {
                $car->receivecarfilepath = '/uploads/images/' . $fileName;
                $car->save();
                $error = false;
            }
        }

        $data = ($error) ? array('error' => 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์') : array('success' => 'อัพโหลดไฟล์สำเร็จ');
        echo json_encode($data);
    }

    public function readSelectlistForDisplayInGrid()
    {
        if(!$this->hasPermission($this->menuPermissionName)) return view($this->viewPermissiondeniedName);

        $carsubmodels = CarSubModel::has('cars')->orderBy('name', 'asc')->get(['id', 'name']);
        $carsubmodelselectlist = array();
        array_push($carsubmodelselectlist,':เลือกรุ่น');
        foreach($carsubmodels as $item){
            array_push($carsubmodelselectlist,$item->id.':'.$item->name);
        }

        $colors = Color::has('cars')->orderBy('code', 'asc')->get(['id', 'code', 'name']);
        $colorselectlist = array();
        array_push($colorselectlist,':เลือกสี');
        foreach($colors as $item){
            array_push($colorselectlist,$item->id.':'.$item->code.' - '.$item->name);
        }

        return ['carsubmodelselectlist'=>implode(";",$carsubmodelselectlist),
            'colorselectlist'=>implode(";",$colorselectlist)];
    }

    public function import()
    {
        try {
            $file = Input::file('file');
            //$path = Input::file('pricelist')->getRealPath();
            $temp = null;
            Excel::load($file, function ($reader) use ($temp) {
                //$reader->dump();
                $reader->skip(1);
                // Loop through all rows
                $reader->each(function ($row) {

                    $carBrand = CarBrand::where('name', 'NISSAN')->first();
                    $color = Color::where('code', trim($row->p))->first();
                    $carModel = CarModel::firstOrCreate(['name' => trim($row->c) . ' ' . trim($row->d), 'cartypeid' => $row->b, 'carbrandid' => $carBrand->id]);
                    $carSubModel = CarSubModel::firstOrCreate(['code' => trim($row->f), 'name' => trim($row->e), 'carmodelid' => $carModel->id]);

                    $car = Car::firstOrNew([
                        'engineno' => trim($row->m), 'chassisno' => trim($row->n)
                    ]);
                    $car->datatype = 0;
                    $car->provinceid = trim($row->a);
                    $car->carmodelid = $carModel->id;
                    $car->carsubmodelid = $carSubModel->id;
                    $car->receivetype = trim($row->g);
                    $car->dealername = trim($row->h);
                    $car->no = trim($row->i);
                    $car->dodate = trim($row->j);
                    $car->dono = trim($row->k);
                    if ($row->l != null && $row->l != '')
                        $car->receiveddate = trim($row->l);
                    if ($row->o != null && $row->o != '')
                        $car->keyno = trim($row->o);
                    $car->colorid = $color->id;
                    $car->objective = trim($row->q);
                    if ($row->r != null && $row->r != '')
                        $car->parklocation = trim($row->r);
                    if ($row->s != null && $row->s != '')
                        $car->notifysolddate = trim($row->s);

                    if ($row->u != null && $row->u != '' && $row->v != null && $row->v != '') {
                        $customer = Customer::firstOrNew([
                            'provinceid' => trim($row->a), 'title' => trim($row->u), 'firstname' => trim($row->v), 'lastname' => trim($row->w)
                        ]);
                        $customer->isreal = true;
                        if ($row->x != null && $row->x != '')
                            $customer->phone1 = trim($row->x);
                        if ($row->y != null && $row->y != '')
                            $customer->occupationid = trim($row->y);
                        if ($row->z != null && $row->z != '')
                            $customer->birthdate = trim($row->z);
                        if ($row->aa != null && $row->aa != '')
                            $customer->address = trim($row->aa);

                        $district = District::where('name', trim($row->ab))->first();
                        $amphur = Amphur::where('name', trim($row->ac))->first();
                        $province = Province::where('name', trim($row->ad))->first();

                        if ($district != null)
                            $customer->districtid = $district->id;
                        if ($amphur != null)
                            $customer->amphurid = $amphur->id;
                        if ($province != null)
                            $customer->addprovinceid = $province->id;
                        if ($row->ad != null && $row->ad != '')
                            $customer->zipcode = trim($row->ae);
                        $customer->save();
                        $car->issold = true;
                        $car->buyercustomerid = $customer->id;

                        if ($row->t != null && $row->t != '')
                            $car->isdelivered = true;
                    }

                    $car->save();
                });

            });
        } catch (Exception $e) {
            return 'Message: ' . $e->getMessage();
        }

        return redirect()->action('CarController@index');
    }
}